<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LokasiPatroli;
use App\Models\UnitKerja;
use App\Models\Patroli;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jumlahPengguna = User::count();
        $jumlahLokasiPatroli = LokasiPatroli::count();
        $jumlahPatroli = Patroli::count();

        // Aktivitas Harian
        $harianLabels = [];
        $harianData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $harianLabels[] = Str::title($date->isoFormat('dddd'));
            $harianData[] = Patroli::whereDate('tanggal_patroli', $date)->count();
        }

        // Aktivitas Mingguan
        $mingguanLabels = [];
        $mingguanData = [];
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->startOfWeek()->subWeeks($i);
            $endOfWeek = $startOfWeek->copy()->endOfWeek();
            $mingguanLabels[] = 'Minggu ke-' . (4 - $i);
            $mingguanData[] = Patroli::whereBetween('tanggal_patroli', [$startOfWeek, $endOfWeek])->count();
        }

        // Aktivitas Bulanan
        $bulananLabels = [];
        $bulananData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $bulananLabels[] = Str::title($month->isoFormat('MMMM'));
            $bulananData[] = Patroli::whereYear('tanggal_patroli', $month->year)
                ->whereMonth('tanggal_patroli', $month->month)
                ->count();
        }

        // Pie Chart Status
        $statusPatroli = Patroli::select('status', DB::raw('count(*) as total'))
            ->whereIn('status', ['aman', 'darurat'])
            ->groupBy('status')
            ->pluck('total', 'status');

        //Notifikasi Petugas Kurang Patroli
        $currentMonth = Carbon::now();
        $minimalPatroli = 8;

        $petugasKurangPatroli = User::where('role', 'petugas_security')->get()->filter(function ($user) use ($currentMonth, $minimalPatroli) {
            $jumlah = Patroli::where('user_id', $user->id)
                ->whereMonth('tanggal_patroli', $currentMonth->month)
                ->whereYear('tanggal_patroli', $currentMonth->year)
                ->count();
            return $jumlah < $minimalPatroli;
        });

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
            'statusPatroli',
            'petugasKurangPatroli',
        ));
    }

    public function dataPatroli(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $patroli = Patroli::with(['user', 'lokasiPatroli','unit'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($subQuery) use ($search) {
                        $subQuery->where('nama', 'like', '%' . $search . '%');
                    })
                        ->orWhereHas('lokasiPatroli', function ($subQuery) use ($search) {
                            $subQuery->where('nama_lokasi', 'like', '%' . $search . '%');
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

        return view('admin.data-patroli', compact('patroli', 'search', 'status'));
    }
}
