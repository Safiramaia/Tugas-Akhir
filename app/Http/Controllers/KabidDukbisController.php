<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LokasiPatroli;
use App\Models\Patroli;
use App\Models\UnitKerja;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Crypt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class KabidDukbisController extends Controller
{
    public function dashboard()
    {
        // Menghitung jumlah petugas, lokasi patroli, dan laporan patroli
        $jumlahPetugas = User::where('role', 'petugas_security')->count();
        $jumlahLokasiPatroli = LokasiPatroli::count();
        $jumlahLaporanPatroli = Patroli::count();

        // Data harian 7 hari terakhir
        $harianLabels = [];
        $harianData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $harianLabels[] = Str::title($date->isoFormat('dddd'));
            $harianData[] = Patroli::whereDate('tanggal_patroli', $date)->count();
        }

        // Data mingguan 4 minggu terakhir
        $mingguanLabels = [];
        $mingguanData = [];

        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->startOfWeek()->subWeeks($i);
            $endOfWeek = $startOfWeek->copy()->endOfWeek();
            $mingguanLabels[] = 'Minggu ke-' . (4 - $i);
            $mingguanData[] = Patroli::whereBetween('tanggal_patroli', [$startOfWeek, $endOfWeek])->count();
        }

        // Data bulanan 12 bulan terakhir
        $bulananLabels = [];
        $bulananData = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $bulananLabels[] = Str::title($month->isoFormat('MMMM'));
            $bulananData[] = Patroli::whereYear('tanggal_patroli', $month->year)
                ->whereMonth('tanggal_patroli', $month->month)
                ->count();
        }

        return view('kabid-dukbis.dashboard', compact(
            'jumlahPetugas',
            'jumlahLokasiPatroli',
            'jumlahLaporanPatroli',
            'harianLabels',
            'harianData',
            'mingguanLabels',
            'mingguanData',
            'bulananLabels',
            'bulananData'
        ));
    }

    //Menampilkan data petugas security
    public function dataPetugas(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->where('role', 'petugas_security')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('nomor_induk', 'like', '%' . $search . '%')
                        ->orWhere('no_telepon', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('kabid-dukbis.data-petugas-security', compact('users', 'search'));
    }

    //Menampilkan data lokasi patroli
    public function dataLokasiPatroli(Request $request)
    {
        $search = $request->input('search');

        $lokasiPatroli = LokasiPatroli::with('unit') 
            ->when($search, function ($query, $search) {
                $query->where('nama_lokasi', 'like', '%' . $search . '%');
            })
            ->orderBy('nama_lokasi')
            ->paginate(10)
            ->withQueryString();

        return view('kabid-dukbis.data-lokasi-patroli', compact('lokasiPatroli', 'search'));
    }

    //Menampilkan laporan patroli
    public function laporanPatroli(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $dataPatroli = Patroli::with(['user', 'lokasiPatroli', 'unit'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($qUser) use ($search) {
                        $qUser->where('nama', 'like', '%' . $search . '%');
                    })
                        ->orWhereHas('lokasiPatroli', function ($qLokasi) use ($search) {
                            $qLokasi->where('nama_lokasi', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('unit', function ($qUnit) use ($search) {
                            $qUnit->where('nama_unit', 'like', '%' . $search . '%');
                        })
                        ->orWhere('tanggal_patroli', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('tanggal_patroli', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('kabid-dukbis.laporan-patroli', compact('dataPatroli', 'search', 'status'));
    }

    public function redirectCetakLaporan(Request $request)
    {
        // Validasi input awal
        $validated = Validator::make($request->all(), [
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date'],
            'status' => ['nullable', 'in:aman,darurat'],
        ], [
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai harus berupa tanggal yang valid.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Tanggal selesai harus berupa tanggal yang valid.',
            'status.in' => 'Status yang dipilih tidak valid.',
        ]);

        // Validasi lanjutan: tanggal
        $validated->after(function ($validator) use ($request) {
            if ($request->filled(['tanggal_mulai', 'tanggal_selesai'])) {
                $start = Carbon::parse($request->tanggal_mulai);
                $end = Carbon::parse($request->tanggal_selesai);

                if ($start->greaterThan($end)) {
                    $validator->errors()->add('tanggal_mulai', 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.');
                }

                if ($start->format('Y-m') !== $end->format('Y-m')) {
                    $validator->errors()->add('tanggal_mulai', 'Tanggal mulai dan selesai harus dalam bulan dan tahun yang sama.');
                }

                if ($start->diffInMonths($end) > 1) {
                    $validator->errors()->add('tanggal_mulai', 'Rentang tanggal tidak boleh lebih dari satu bulan.');
                }
            }
        });

        if ($validated->fails()) {
            return back()->withInput()->with('error', $validated->errors()->first());
        }

        // Enkripsi parameter menjadi token
        $token = Crypt::encrypt([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
        ]);

        return redirect()->route('kabid-dukbis.cetak-laporan-patroli', ['token' => $token]);
    }

    public function cetakLaporanTerenkripsi($token)
    {
        try {
            $params = Crypt::decrypt($token);
        } catch (\Exception $e) {
            abort(403, 'Token tidak valid atau rusak.');
        }

        $startDate = $params['tanggal_mulai'];
        $endDate = $params['tanggal_selesai'];
        $status = $params['status'];

        $dataPatroli = Patroli::with(['user', 'lokasiPatroli', 'unit'])
            ->whereBetween('tanggal_patroli', [$startDate, $endDate])
            ->when($status, fn($query) => $query->where('status', $status))
            ->get();

        if ($dataPatroli->isEmpty()) {
            return back()->with('error', 'Tidak ditemukan data patroli pada rentang tanggal dan status yang dipilih.');
        }

        $user = Auth::user();

        // Enkripsi parameter
        $tokenVerifikasi = Crypt::encrypt([
            'tanggal_mulai' => $startDate,
            'tanggal_selesai' => $endDate,
            'status' => $status,
        ]);

        // Gunakan route dengan token saja
        $linkVerifikasi = route('verifikasi-laporan-patroli-token', ['token' => $tokenVerifikasi]);


        $qrText = "Laporan Patroli\nDisahkan oleh : " . ($user->nama ?? '-') .
            "\nNomor Induk : " . ($user->nomor_induk ?? '-') .
            "\nTanggal : " . now()->translatedFormat('d F Y') .
            "\n\nVerifikasi :\n" . $linkVerifikasi;

        $qrCode = base64_encode(QrCode::format('png')->size(150)->generate($qrText));

        $pdf = Pdf::loadView('kabid-dukbis.cetak-laporan-patroli', [
            'data' => $dataPatroli,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'qrCode' => $qrCode,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('laporan_patroli_' . now()->format('Ymd_His') . '.pdf');
    }

    public function verifikasiLaporanToken($token)
    {
        try {
            $params = Crypt::decrypt($token);
        } catch (\Exception $e) {
            abort(403, 'Token tidak valid atau telah rusak.');
        }

        $startDate = $params['tanggal_mulai'];
        $endDate = $params['tanggal_selesai'];
        $status = $params['status'];

        $dataPatroli = Patroli::with(['user', 'lokasiPatroli', 'unit'])
            ->whereBetween('tanggal_patroli', [$startDate, $endDate])
            ->when($status, fn($q) => $q->where('status', $status))
            ->get();

        // Ambil nama kepala dari user role kabid_dukbis (bisa disesuaikan)
        $kepala = User::where('role', 'kabid_dukbis')->first();
        $namaKepala = $kepala?->nama ?? 'Nama Kepala';
        $nomorInduk = $kepala?->nomor_induk ?? '-';

        // Buat ulang QR Code seperti di cetak
        $qrText = "Laporan Patroli\nDisahkan oleh : $namaKepala" .
            "\nNomor Induk : $nomorInduk" .
            "\nTanggal : " . now()->translatedFormat('d F Y') .
            "\n\nVerifikasi :\n" . url('/verifikasi-laporan/' . $token);

        $qrCode = base64_encode(QrCode::format('png')->size(150)->generate($qrText));

        return view('kabid-dukbis.cetak-laporan-patroli', [
            'data' => $dataPatroli,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'qrCode' => $qrCode,
            'preview' => true,
            'namaKepala' => $namaKepala,
        ]);
    }
}