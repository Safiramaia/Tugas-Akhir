<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Patroli;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function dashboard()
    {
        $unitId = Auth::user()->unit_id;

        $totalPatroli = Patroli::where('unit_id', $unitId)->count();

        $statusPatroli = [
            'aman' => Patroli::where('unit_id', $unitId)->where('status', 'aman')->count(),
            'darurat' => Patroli::where('unit_id', $unitId)->where('status', 'darurat')->count(),
        ];

        // Data harian 7 hari terakhir
        $harianLabels = [];
        $harianData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $harianLabels[] = Str::title($date->isoFormat('dddd')); // Contoh: "Senin"
            $harianData[] = Patroli::whereDate('tanggal_patroli', $date)
                ->where('unit_id', $unitId)
                ->count();
        }

        // Data mingguan 4 minggu terakhir
        $mingguanLabels = [];
        $mingguanData = [];

        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->startOfWeek()->subWeeks($i);
            $endOfWeek = $startOfWeek->copy()->endOfWeek();
            $mingguanLabels[] = 'Minggu ke-' . (4 - $i);
            $mingguanData[] = Patroli::whereBetween('tanggal_patroli', [$startOfWeek, $endOfWeek])
                ->where('unit_id', $unitId)
                ->count();
        }

        // Data bulanan 12 bulan terakhir
        $bulananLabels = [];
        $bulananData = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $bulananLabels[] = Str::title($month->isoFormat('MMMM'));
            $bulananData[] = Patroli::whereYear('tanggal_patroli', $month->year)
                ->whereMonth('tanggal_patroli', $month->month)
                ->where('unit_id', $unitId)
                ->count();
        }

        return view('unit.dashboard', compact(
            'totalPatroli',
            'statusPatroli',
            'harianLabels',
            'harianData',
            'mingguanLabels',
            'mingguanData',
            'bulananLabels',
            'bulananData',
        ));
    }

    public function patroliPetugas(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $unitId = Auth::user()->unit_id;

        $patroli = Patroli::with(['user', 'lokasiPatroli'])
            ->where(function ($query) use ($unitId) {
                $query->where('unit_id', $unitId)
                    ->orWhereHas('lokasiPatroli', function ($subQuery) use ($unitId) {
                        $subQuery->where('unit_id', $unitId);
                    });
            })
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
            ->orderByDesc('tanggal_patroli')
            ->paginate(10)
            ->withQueryString();

        return view('unit.riwayat-patroli-petugas', compact('patroli', 'search', 'status'));
    }
}
