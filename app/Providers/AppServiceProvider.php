<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Patroli;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Set locale Carbon ke bahasa Indonesia
        Carbon::setLocale('id');

        // Share jumlah validasi pending ke semua view
        View::composer('*', function ($view) {
            $jumlahValidasiPending = Patroli::where('status', 'darurat')
                ->whereNull('validasi_darurat')
                ->count();

            $view->with('jumlahValidasiPending', $jumlahValidasiPending);
        });
    }
}
