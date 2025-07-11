<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JadwalPatroli;
use App\Models\PergantianPetugas;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class JadwalPatroliController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter bulan (default: bulan ini)
        $monthParam = $request->input('month');
        $currentMonth = $monthParam ? Carbon::parse($monthParam . '-01') : Carbon::now();

        // Ambil jadwal patroli lengkap bulan ini (dengan user)
        $jadwalPatroliRaw = JadwalPatroli::with('user')
            ->whereYear('tanggal', $currentMonth->year)
            ->whereMonth('tanggal', $currentMonth->month)
            ->get();

        // Kelompokkan berdasarkan tanggal (Y-m-d)
        $jadwalPatroli = $jadwalPatroliRaw->groupBy(function ($item) {
            return Carbon::parse($item->tanggal)->format('Y-m-d');
        });

        // Ambil semua petugas
        $users = User::where('role', 'petugas_security')->get();

        // Hitung jumlah patroli per user (untuk rekap)
        $jumlahPatroliPerUser = JadwalPatroli::selectRaw('user_id, count(*) as total')
            ->whereYear('tanggal', $currentMonth->year)
            ->whereMonth('tanggal', $currentMonth->month)
            ->groupBy('user_id')
            ->with('user')
            ->get();

        // Ambil histori pergantian petugas untuk bulan ini
        $historiPergantian = PergantianPetugas::with(['jadwal', 'petugasLama', 'petugasBaru'])
            ->whereHas('jadwal', function ($query) use ($currentMonth) {
                $query->whereYear('tanggal', $currentMonth->year)
                    ->whereMonth('tanggal', $currentMonth->month);
            })
            ->orderByDesc('waktu_pergantian')
            ->get();

        // Kirim ke view
        return view('admin.jadwal-patroli.index', compact(
            'jadwalPatroli',
            'currentMonth',
            'users',
            'jumlahPatroliPerUser',
            'historiPergantian'
        ));
    }

    // Fungsi untuk membuat jadwal patroli otomatis selama satu bulan tertentu
    private function generateJadwalOtomatis(Carbon $bulan, bool $overwrite = false, ?Carbon $overrideStartDate = null)
    {
        // Menentukan rentang tanggal
        $startDate = $overrideStartDate ?? $bulan->copy()->startOfMonth();
        $endDate = $bulan->copy()->endOfMonth();

        // Ambil semua user petugas
        $allUsers = User::where('role', 'petugas_security')->orderBy('id')->get();
        if ($allUsers->isEmpty())
            return;

        // Hitung total shift dalam bulan ini
        $totalShiftBulan = 0;
        foreach (Carbon::parse($startDate)->daysUntil($endDate) as $tanggal) {
            $hari = $tanggal->dayOfWeek;
            $totalShiftBulan += in_array($hari, [0, 6]) ? 2 : 1;
        }

        $jumlahPetugas = $allUsers->count();
        $maxPatroliPerUser = ceil($totalShiftBulan / max($jumlahPetugas, 1));

        // Inisialisasi counter: user_id => jumlah jadwal
        $jadwalCounter = [];
        foreach ($allUsers as $user) {
            $jadwalCounter[$user->id] = 0;
        }

        // Loop per tanggal
        foreach (Carbon::parse($startDate)->daysUntil($endDate) as $tanggal) {
            $hari = $tanggal->dayOfWeek;
            $shifts = in_array($hari, [0, 6]) ? ['pagi', 'sore'] : ['sore'];

            foreach ($shifts as $shift) {
                // Ambil user yang aktif pada hari itu
                $activeUsers = $allUsers->filter(fn($u) => $u->created_at->lte($tanggal))->pluck('id');

                // Pilih user dengan jumlah jadwal paling sedikit, yang belum mencapai maksimal
                $userTerpilih = collect($jadwalCounter)
                    ->only($activeUsers)
                    ->filter(fn($count) => $count < $maxPatroliPerUser)
                    ->sort()
                    ->keys()
                    ->first();

                // Lewati jika tidak ada yang bisa ditugaskan
                if (!$userTerpilih)
                    continue;

                // Skip jika sudah ada jadwal dan overwrite = false
                if (!$overwrite && JadwalPatroli::whereDate('tanggal', $tanggal)->where('shift', $shift)->exists()) {
                    continue;
                }

                // Simpan ke database
                JadwalPatroli::updateOrCreate(
                    ['tanggal' => $tanggal->format('Y-m-d'), 'shift' => $shift],
                    ['user_id' => $userTerpilih]
                );

                // Tambahkan counter
                $jadwalCounter[$userTerpilih]++;
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
            'shift' => 'required|in:pagi,sore',
        ]);

        $tanggal = Carbon::parse($request->tanggal);
        $hari = $tanggal->dayOfWeek; // 0 = Minggu, 6 = Sabtu

        // Jika hari bukan Sabtu/Minggu dan shift pagi dipilih, tolak
        if (!in_array($hari, [0, 6]) && $request->shift == 'pagi') {
            return back()->with('error', 'Shift pagi hanya diperbolehkan pada hari Sabtu dan Minggu.');
        }

        // Cek duplikasi shift pada tanggal dan shift yang sama
        $exists = JadwalPatroli::whereDate('tanggal', $tanggal)
            ->where('shift', $request->shift)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Shift pada tanggal tersebut sudah diisi.')->withInput();
        }

        JadwalPatroli::create([
            'user_id' => $request->user_id,
            'tanggal' => $tanggal,
            'shift' => $request->shift,
        ]);

        return redirect()->route('jadwal-patroli.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, JadwalPatroli $jadwalPatroli)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift' => 'required|in:pagi,sore',
            'alasan_pergantian' => 'nullable|string|max:255',
        ]);

        $tanggal = $jadwalPatroli->tanggal;
        $hari = Carbon::parse($tanggal)->dayOfWeek;

        if (!in_array($hari, [0, 6]) && $request->shift == 'pagi') {
            return back()->withInput()->with('error', 'Shift pagi hanya diperbolehkan pada hari Sabtu dan Minggu.');
        }

        if (
            $jadwalPatroli->user_id != $request->user_id ||
            $jadwalPatroli->shift != $request->shift
        ) {
            $exists = JadwalPatroli::where('tanggal', $tanggal)
                ->where('shift', $request->shift)
                ->where('user_id', $request->user_id)
                ->where('id', '!=', $jadwalPatroli->id)
                ->exists();

            if ($exists) {
                return back()->withInput()->with('error', 'Shift pada tanggal tersebut sudah diisi.');
            }
        }

        if ($jadwalPatroli->user_id != $request->user_id) {
            $bulanIni = Carbon::now();
            $jumlahPergantian = PergantianPetugas::where('petugas_lama_id', $jadwalPatroli->user_id)
                ->whereMonth('waktu_pergantian', $bulanIni->month)
                ->whereYear('waktu_pergantian', $bulanIni->year)
                ->count();

            if ($jumlahPergantian >= 2) {
                return back()->withInput()->with('error', 'Petugas ini sudah diganti 2x dalam bulan ini.');
            }

            PergantianPetugas::create([
                'jadwal_id' => $jadwalPatroli->id,
                'petugas_lama_id' => $jadwalPatroli->user_id,
                'petugas_baru_id' => $request->user_id,
                'waktu_pergantian' => now(),
                'alasan' => $request->alasan_pergantian ?? 'Diganti oleh admin',
            ]);
        }

        $jadwalPatroli->update([
            'user_id' => $request->user_id,
            'shift' => $request->shift,
        ]);

        return redirect()->route('jadwal-patroli.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
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
