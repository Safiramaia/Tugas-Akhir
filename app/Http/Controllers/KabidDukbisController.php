<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LokasiPatroli;
use App\Models\Patroli;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class KabidDukbisController extends Controller
{
    public function dashboard()
    {
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

    public function dataPetugas(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->where('role', 'petugas_security')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('nip', 'like', '%' . $search . '%')
                        ->orWhere('no_telepon', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('kabid-dukbis.data-petugas-security', compact('users', 'search'));
    }

    public function dataLokasiPatroli(Request $request)
    {
        $search = $request->input('search');

        $lokasiPatroli = LokasiPatroli::query()
            ->when($search, function ($query, $search) {
                $query->where('nama_lokasi', 'like', '%' . $search . '%');
            })
            ->orderBy('nama_lokasi')
            ->paginate(5)
            ->withQueryString();

        return view('kabid-dukbis.data-lokasi-patroli', compact('lokasiPatroli', 'search'));
    }

    public function laporanPatroli(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $dataPatroli = Patroli::with(['user', 'lokasiPatroli'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($qUser) use ($search) {
                        $qUser->where('nama', 'like', '%' . $search . '%');
                    })
                        ->orWhereHas('lokasiPatroli', function ($qLokasi) use ($search) {
                            $qLokasi->where('nama_lokasi', 'like', '%' . $search . '%');
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

        return view('kabid-dukbis.laporan-patroli', compact('dataPatroli', 'search','status'));
    }

    public function cetakLaporan(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ]);

        $validated->after(function ($validator) use ($request) {
            $start = Carbon::parse($request->tanggal_mulai);
            $end = Carbon::parse($request->tanggal_selesai);

            if ($start->format('Y-m') !== $end->format('Y-m')) {
                $validator->errors()->add(
                    'tanggal_selesai',
                    'Tanggal mulai dan tanggal selesai harus berada dalam bulan dan tahun yang sama.'
                );
            }

            if ($start->diffInMonths($end) > 1) {
                $validator->errors()->add(
                    'tanggal_selesai',
                    'Rentang tanggal tidak boleh lebih dari satu bulan.'
                );
            }
        });

        if ($validated->fails()) {
            $errorMessage = $validated->errors()->first();
            return back()
                ->withInput()
                ->with('error', $errorMessage);
        }
        $startDate = $request->tanggal_mulai;
        $endDate = $request->tanggal_selesai;

        // Ambil data patroli
        $dataPatroli = Patroli::with(['user', 'lokasiPatroli'])
            ->whereBetween('tanggal_patroli', [$startDate, $endDate])
            ->get();

        if ($dataPatroli->isEmpty()) {
            return back()->with('error', 'Data patroli tidak ditemukan dalam rentang tanggal tersebut.');
        }

        $user = Auth::user();

        $qrText = "Laporan Patroli\n" .
            "Disahkan oleh : " . ($user ? $user->nama : '-') . "\n" .
            "NIP : " . ($user ? $user->nip : '-') . "\n" .
            "Tanggal : " . now()->translatedFormat('d F Y') . "\n" .
            "(SUCOFINDO - Cabang Cilacap)";

        $qrCode = base64_encode(QrCode::format('png')->size(150)->generate($qrText));

        // Generate PDF
        $pdf = Pdf::loadView('kabid-dukbis.cetak-laporan-patroli', [
            'data' => $dataPatroli,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'qrCode' => $qrCode,
        ])->setPaper('A4', 'portrait');

        $fileName = 'laporan_patroli_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($fileName);
    }
}
