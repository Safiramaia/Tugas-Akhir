<?php

namespace App\Http\Controllers;


use App\Models\Patroli;
use App\Models\LokasiPatroli;
use App\Models\JadwalPatroli;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PetugasSecurityController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::user()->id;
        $today = Carbon::today()->toDateString(); // tanggal hari ini

        // Total semua lokasi patroli
        $totalLokasi = LokasiPatroli::count();

        // Lokasi yang sudah dipatroli hari ini oleh user
        $patroliHariIni = Patroli::with('lokasiPatroli')
            ->where('user_id', $userId)
            ->whereDate('tanggal_patroli', $today)
            ->get();

        $lokasiDipatroliIds = $patroliHariIni->pluck('lokasi_id')->unique();

        $jumlahSelesai = $lokasiDipatroliIds->count();
        $jumlahBelum = $totalLokasi - $jumlahSelesai;
        $persentase = $totalLokasi > 0 ? ($jumlahSelesai / $totalLokasi) * 100 : 0;

        // Lokasi yang belum dipatroli
        $belumDipatroli = LokasiPatroli::whereNotIn('id', $lokasiDipatroliIds)->get();

        // Aktivitas patroli terbaru hari ini, maksimum 5 data
        $aktivitasTerakhir = $patroliHariIni->sortByDesc('waktu_patroli')->take(5);

        return view('petugas-security.dashboard', compact(
            'totalLokasi',
            'jumlahSelesai',
            'jumlahBelum',
            'persentase',
            'belumDipatroli',
            'aktivitasTerakhir'
        ));
    }

    public function scanQr()
    {
        $userId = Auth::id();
        $today = now()->toDateString();

        $jadwalHariIni = JadwalPatroli::where('user_id', $userId)
            ->where('tanggal', $today)
            ->exists();

        if (!$jadwalHariIni) {
            return redirect()->route('petugas-security.dashboard')
                ->with('error', 'Anda tidak dijadwalkan patroli hari ini.');
        }

        return view('petugas-security.scan-qr');
    }

    public function riwayatPatroli(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $riwayatPatroli = Patroli::with('lokasiPatroli')
            ->where('user_id', Auth::id())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('lokasiPatroli', function ($subQuery) use ($search) {
                        $subQuery->where('nama_lokasi', 'like', '%' . $search . '%');
                    })
                        ->orWhere('tanggal_patroli', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('tanggal_patroli')
            ->paginate(10)
            ->withQueryString();

        return view('petugas-security.riwayat-patroli', compact('riwayatPatroli'));
    }
}
