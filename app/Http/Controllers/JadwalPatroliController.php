<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JadwalPatroli;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class JadwalPatroliController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter bulan dari permintaan, jika tidak ada gunakan bulan saat ini
        $monthParam = $request->input('month');
        $currentMonth = $monthParam ? Carbon::parse($monthParam . '-01') : Carbon::now();

        // Ambil semua data jadwal patroli untuk bulan tersebut, termasuk data petugas (relasi user)
        $jadwalPatroliRaw = JadwalPatroli::with('user')
            ->whereYear('tanggal', $currentMonth->year)
            ->whereMonth('tanggal', $currentMonth->month)
            ->get();

        // Kelompokkan jadwal berdasarkan tanggal 
        $jadwalPatroli = $jadwalPatroliRaw->groupBy(function ($item) {
            return Carbon::parse($item->tanggal)->format('Y-m-d');
        });

        // Ambil semua pengguna dengan peran sebagai petugas security
        $users = User::where('role', 'petugas_security')->get();

        return view('admin.jadwal-patroli.index', compact('jadwalPatroli', 'currentMonth', 'users'));
    }

    // Fungsi untuk membuat jadwal patroli otomatis selama satu bulan tertentu
    private function generateJadwalOtomatis(Carbon $bulan, bool $overwrite = false, ?Carbon $overrideStartDate = null)
    {
        // Tentukan tanggal mulai dan akhir dari bulan yang dipilih
        $startDate = $overrideStartDate ?? $bulan->copy()->startOfMonth();
        $endDate = $bulan->copy()->endOfMonth();

        // Ambil semua petugas security yang aktif (tanpa filter tanggal dulu)
        $allUsers = User::where('role', 'petugas_security')
            ->orderBy('id')
            ->get();

        // Jika tidak ada petugas sama sekali, hentikan proses
        if ($allUsers->isEmpty())
            return;

        // Cari petugas terakhir yang bertugas sebelum tanggal mulai
        $lastPatrol = JadwalPatroli::whereDate('tanggal', '<', $startDate->format('Y-m-d'))
            ->orderBy('tanggal', 'desc')
            ->first();

        // Jalankan loop per tanggal
        foreach (Carbon::parse($startDate)->daysUntil($endDate) as $tanggal) {
            // Filter hanya user yang sudah aktif pada tanggal tersebut
            $activeUsers = $allUsers->filter(function ($user) use ($tanggal) {
                return $user->created_at->lte($tanggal);
            })->values(); // reset index

            // Lewati jika belum ada petugas aktif
            if ($activeUsers->isEmpty())
                continue;

            // Cari index giliran berdasarkan jadwal terakhir
            $foundIndex = $activeUsers->search(fn($user) => $user->id == optional($lastPatrol)->user_id);
            $lastUserIndex = $foundIndex !== false ? ($foundIndex + 1) % $activeUsers->count() : 0;

            // Generate jadwal hanya jika belum ada atau diizinkan overwrite
            if ($overwrite || !JadwalPatroli::whereDate('tanggal', $tanggal->format('Y-m-d'))->exists()) {
                $assignedUser = $activeUsers[$lastUserIndex % $activeUsers->count()];
                JadwalPatroli::updateOrCreate(
                    ['tanggal' => $tanggal->format('Y-m-d')],
                    ['user_id' => $assignedUser->id]
                );

                // Update "patroli terakhir" agar giliran tetap berlanjut
                $lastPatrol = (object) ['user_id' => $assignedUser->id];
            }
        }
    }

    // Fungsi untuk generate ulang jadwal patroli dari tanggal tertentu
    public function generateUlang(Request $request)
    {
        //Mengambil parameter bulan dan tanggal mulai dari request
        $monthParam = $request->input('month');
        $startDateInput = $request->input('start_date');

        //Jika tidak ada, gunakan bulan dan tanggal saat ini
        $currentMonth = $monthParam ? Carbon::parse($monthParam . '-01') : Carbon::now();
        $startDate = $startDateInput ? Carbon::parse($startDateInput) : $currentMonth->copy()->startOfMonth();

        //Mencegah proses jika admin mencoba generate ulang untuk bulan yang sudah lewat
        if ($currentMonth->lt(Carbon::now()->startOfMonth())) {
            return redirect()->route('jadwal-patroli.index', ['month' => $currentMonth->format('Y-m')])
                ->with('error', 'Tidak dapat generate ulang jadwal untuk bulan yang sudah lewat.');
        }

        //Menghapus jadwal patroli dari tanggal mulai hingga akhir bulan
        JadwalPatroli::whereDate('tanggal', '>=', $startDate->format('Y-m-d'))->delete();

        //Membuat ulang jadwal patroli mulai dari tanggal yang ditentukan
        $this->generateJadwalOtomatis($currentMonth, false, $startDate);

        return redirect()->route('jadwal-patroli.index', ['month' => $currentMonth->format('Y-m')])
            ->with('success', 'Jadwal patroli berhasil digenerate ulang mulai tanggal ' . $startDate->format('d M Y') . '.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
        ]);

        // Ambil bulan dari tanggal yang dimasukkan
        $currentMonth = Carbon::parse($request->tanggal)->startOfMonth();

        // Mencegah input jadwal jika bulan yang dipilih sudah lewat
        if ($currentMonth->lt(Carbon::now()->startOfMonth())) {
            return redirect()->route('jadwal-patroli.index', ['month' => $currentMonth->format('Y-m')])
                ->with('error', 'Tidak dapat menambahkan jadwal untuk bulan yang sudah lewat.')
                ->withInput();
        }

        // Mengecek apakah tanggal sudah memiliki jadwal
        $exists = JadwalPatroli::whereDate('tanggal', $request->tanggal)->exists();
        if ($exists) {
            return redirect()->route('jadwal-patroli.index')
                ->with('error', 'Tanggal tersebut sudah memiliki petugas yang dijadwalkan.')
                ->withInput();
        }

        // Simpan data jadwal baru
        JadwalPatroli::create([
            'user_id' => $request->user_id,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('jadwal-patroli.index')
            ->with('success', 'Jadwal patroli berhasil ditambahkan.');
    }

    public function update(Request $request, JadwalPatroli $jadwalPatroli)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $jadwalPatroli->update([
            'user_id' => $request->user_id,
        ]);

        return redirect()->route('jadwal-patroli.index')
            ->with('success', 'Jadwal patroli berhasil diperbarui.');
    }


    public function destroy(JadwalPatroli $jadwalPatroli)
    {
        $jadwalPatroli->delete();

        return redirect()->route('jadwal-patroli.index')
            ->with('success', 'Jadwal patroli berhasil dihapus.');
    }

    public function cetakJadwal(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $currentMonth = Carbon::createFromFormat('Y-m', $month);

        // Ambil dan kelompokkan jadwal patroli berdasarkan tanggal
        $jadwalPatroli = JadwalPatroli::with('user')
            ->whereMonth('tanggal', $currentMonth->month)
            ->whereYear('tanggal', $currentMonth->year)
            ->orderBy('tanggal')
            ->get()
            ->groupBy('tanggal');
        return Pdf::loadView('admin.jadwal-patroli.cetak-jadwal', [
            'jadwalPatroli' => $jadwalPatroli,
            'currentMonth' => $currentMonth,
        ])->setPaper('a4', 'landscape')->download('jadwal-patroli-' . $currentMonth->format('Y-m') . '.pdf');
    }
}
