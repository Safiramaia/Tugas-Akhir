<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LokasiPatroli;
use App\Models\Patroli;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Menghitung jumlah pengguna, lokasi patroli, dan patroli
        $jumlahPengguna = User::count();
        $jumlahLokasiPatroli = LokasiPatroli::count();
        $jumlahPatroli = Patroli::count();

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

        // Menghitung status patroli
        $statusPatroli = Patroli::select('status', DB::raw('count(*) as total'))
            ->whereIn('status', ['aman', 'darurat'])
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', compact(
            'jumlahPengguna',
            'jumlahLokasiPatroli',
            'jumlahPatroli',
            'harianLabels',
            'harianData',
            'mingguanLabels',
            'mingguanData',
            'bulananLabels',
            'bulananData',
            'statusPatroli'
        ));
    }

    public function dataPatroli(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $patroli = Patroli::with(['user', 'lokasiPatroli'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($subQuery) use ($search) {
                        $subQuery->where('nama', 'like', '%' . $search . '%');
                    })
                        ->orWhereHas('lokasiPatroli', function ($subQuery) use ($search) {
                            $subQuery->where('nama_lokasi', 'like', '%' . $search . '%');
                        })
                        ->orWhere('tanggal_patroli', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            // Mengurutkan berdasarkan tanggal patroli terbaru
            ->orderBy('tanggal_patroli', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.data-patroli', compact('patroli', 'search', 'status'));
    }
}
