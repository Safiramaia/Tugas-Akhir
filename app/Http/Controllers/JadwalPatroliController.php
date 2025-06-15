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
        $monthParam = $request->input('month');
        $currentMonth = $monthParam ? Carbon::parse($monthParam . '-01') : Carbon::now();

        $daysInMonth = $currentMonth->daysInMonth;
        $existingDays = JadwalPatroli::whereYear('tanggal', $currentMonth->year)
            ->whereMonth('tanggal', $currentMonth->month)
            ->distinct()
            ->count('tanggal');

        if ($existingDays < $daysInMonth) {
            $this->generateJadwalOtomatis($currentMonth, false);
        }

        $jadwalPatroliRaw = JadwalPatroli::with('user')
            ->whereYear('tanggal', $currentMonth->year)
            ->whereMonth('tanggal', $currentMonth->month)
            ->get();

        $jadwalPatroli = $jadwalPatroliRaw->groupBy(function ($item) {
            return Carbon::parse($item->tanggal)->format('Y-m-d');
        });

        $users = User::where('role', 'petugas_security')->get();

        return view('admin.jadwal-patroli.index', compact('jadwalPatroli', 'currentMonth', 'users'));
    }

    private function generateJadwalOtomatis(Carbon $bulan, bool $overwrite = false, ?Carbon $overrideStartDate = null)
    {
        $startDate = $overrideStartDate ?? $bulan->copy()->startOfMonth();
        $endDate = $bulan->copy()->endOfMonth();

        // Ambil semua petugas aktif hingga akhir bulan
        $users = User::where('role', 'petugas_security')
            ->whereDate('created_at', '<=', $endDate->format('Y-m-d'))
            ->orderBy('id')
            ->get();

        $userCount = $users->count();
        if ($userCount === 0)
            return;

        // Cari user terakhir yang bertugas sebelum startDate
        $lastPatrol = JadwalPatroli::whereDate('tanggal', '<', $startDate->format('Y-m-d'))
            ->orderBy('tanggal', 'desc')
            ->first();

        $foundIndex = $users->search(fn($user) => $user->id == optional($lastPatrol)->user_id);
        $lastUserIndex = $foundIndex !== false ? ($foundIndex + 1) % $userCount : 0;

        foreach (Carbon::parse($startDate)->daysUntil($endDate) as $tanggal) {
            if ($overwrite || !JadwalPatroli::whereDate('tanggal', $tanggal->format('Y-m-d'))->exists()) {
                JadwalPatroli::updateOrCreate(
                    ['tanggal' => $tanggal->format('Y-m-d')],
                    ['user_id' => $users[$lastUserIndex % $userCount]->id]
                );
                $lastUserIndex++;
            }
        }
    }

    public function generateUlang(Request $request)
    {
        $monthParam = $request->input('month');
        $startDateInput = $request->input('start_date');
        $currentMonth = $monthParam ? Carbon::parse($monthParam . '-01') : Carbon::now();
        $startDate = $startDateInput ? Carbon::parse($startDateInput) : $currentMonth->copy()->startOfMonth();

        // Hapus hanya jadwal dari startDate hingga akhir bulan
        JadwalPatroli::whereDate('tanggal', '>=', $startDate->format('Y-m-d'))
            ->whereDate('tanggal', '<=', $currentMonth->copy()->endOfMonth()->format('Y-m-d'))
            ->delete();

        // Generate ulang dari startDate sampai akhir bulan,
        // hanya menggunakan user yang dibuat sebelum atau sama dengan startDate
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

        // Cek apakah sudah ada jadwal di tanggal tersebut
        $exists = JadwalPatroli::whereDate('tanggal', $request->tanggal)->exists();

        if ($exists) {
            return redirect()->route('jadwal-patroli.index')
                ->with('error', 'Tanggal tersebut sudah memiliki petugas yang dijadwalkan.')
                ->withInput();
        }

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