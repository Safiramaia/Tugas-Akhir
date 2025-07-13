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
        //Mengambil ID user yang sedang login dan tanggal hari ini
        $userId = Auth::user()->id;
        $today = Carbon::today()->toDateString();

        //Mengecek apakah petugas dijadwalkan hari ini
        $dijadwalkan = JadwalPatroli::where('user_id', $userId)
            ->whereDate('tanggal', $today)
            ->exists();

        //Jika tidak dijadwalkan, dashboard kosong
        if (!$dijadwalkan) {
            return view('petugas-security.dashboard', [
                'totalLokasi' => 0,
                'jumlahSelesai' => 0,
                'jumlahBelum' => 0,
                'persentase' => 0,
                'belumDipatroli' => collect(),
                'aktivitasTerakhir' => collect()
            ]);
        }

        //Menghitung total lokasi patroli
        $totalLokasi = LokasiPatroli::count();

        //Mengambil data patroli hari ini untuk petugas yang sedang login
        $patroliHariIni = Patroli::with('lokasiPatroli')
            ->where('user_id', $userId)
            ->whereDate('tanggal_patroli', $today)
            ->get();

        //Mengambil ID lokasi yang sudah dipatroli hari ini
        $lokasiDipatroliIds = $patroliHariIni->pluck('lokasi_id')->unique();

        //Menghitung jumlah lokasi yang sudah dipatroli
        $jumlahSelesai = $lokasiDipatroliIds->count();

        //Menghitung jumlah lokasi yang belum dipatroli
        $jumlahBelum = $totalLokasi - $jumlahSelesai;

        //Menghitung persentase lokasi yang sudah dipatroli
        $persentase = $totalLokasi > 0 ? ($jumlahSelesai / $totalLokasi) * 100 : 0;

        //Mengambil lokasi yang belum dipatroli
        $belumDipatroli = LokasiPatroli::whereNotIn('id', $lokasiDipatroliIds)->get();

        //Mengambil 5 aktivitas patroli terakhir hari ini
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
        //Mengambil ID user yang sedang login dan tanggal hari ini
        $userId = Auth::id();
        $today = now()->toDateString();

        //Mengecek apakah petugas dijadwalkan hari ini
        $jadwalHariIni = JadwalPatroli::where('user_id', $userId)
            ->where('tanggal', $today)
            ->exists();

        //Jika tidak dijadwalkan, redirect ke dashboard dengan pesan error
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

        $riwayatPatroli = Patroli::with(['lokasiPatroli']) // load lokasi saja, tidak perlu unit
            ->where('user_id', Auth::id())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('lokasiPatroli', function ($subQuery) use ($search) {
                        $subQuery->where('nama_lokasi', 'like', '%' . $search . '%');
                    })->orWhere('tanggal_patroli', 'like', '%' . $search . '%');
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
