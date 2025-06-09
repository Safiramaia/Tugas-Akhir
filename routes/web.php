<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\JadwalPatroliController;
use App\Http\Controllers\KabidDukbisController;
use App\Http\Controllers\LokasiPatroliController;
use App\Http\Controllers\PatroliController;
use App\Http\Controllers\PetugasSecurityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing-page');
});
// Route::get('/landing-page', function () {
//     return view('landing-page');
// });

// Authenticated Routes (Sudah Login)
Route::middleware('auth')->group(function () {
    // PROFILE ROUTES
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // ADMIN ROUTES
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Data Pengguna
        Route::prefix('data-pengguna')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('data-pengguna.index');
            Route::get('/create', [UserController::class, 'create'])->name('data-pengguna.create');
            Route::post('/', [UserController::class, 'store'])->name('data-pengguna.store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('data-pengguna.edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('data-pengguna.update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('data-pengguna.destroy');
        });

        // Jadwal Patroli
        Route::prefix('jadwal-patroli')->group(function () {
            Route::get('/', [JadwalPatroliController::class, 'index'])->name('jadwal-patroli.index');
            Route::post('/generate-ulang', [JadwalPatroliController::class, 'generateUlang'])->name('jadwal-patroli.generate-ulang');
            Route::post('/', [JadwalPatroliController::class, 'store'])->name('jadwal-patroli.store');
            Route::put('/{jadwalPatroli}', [JadwalPatroliController::class, 'update'])->name('jadwal-patroli.update');
            Route::delete('/{jadwalPatroli}', [JadwalPatroliController::class, 'destroy'])->name('jadwal-patroli.destroy');
            Route::get('/cetak', [JadwalPatroliController::class, 'cetakJadwal'])->name('jadwal-patroli.cetak-jadwal');

        });

        // Lokasi Patroli 
        Route::prefix('lokasi-patroli')->group(function () {
            Route::get('/', [LokasiPatroliController::class, 'index'])->name('lokasi-patroli.index');
            Route::get('/create', [LokasiPatroliController::class, 'create'])->name('lokasi-patroli.create');
            Route::post('/', [LokasiPatroliController::class, 'store'])->name('lokasi-patroli.store');
            Route::get('/{lokasiPatroli}/edit', [LokasiPatroliController::class, 'edit'])->name('lokasi-patroli.edit');
            Route::put('/{lokasiPatroli}', [LokasiPatroliController::class, 'update'])->name('lokasi-patroli.update');
            Route::delete('/{lokasiPatroli}', [LokasiPatroliController::class, 'destroy'])->name('lokasi-patroli.destroy');
            Route::get('/{lokasiPatroli}/download-qr', [LokasiPatroliController::class, 'downloadQrCode'])->name('lokasi-patroli.downloadQrCode');
        });

        Route::get('/data-patroli', [AdminController::class, 'dataPatroli'])->name('admin.data-patroli');
    });

    // PETUGAS SECURITY ROUTES
    Route::middleware('role:petugas_security')->prefix('petugas-security')->group(function () {
        Route::get('/dashboard', [PetugasSecurityController::class, 'dashboard'])->name('petugas-security.dashboard');
        Route::get('/scan-qr', [PetugasSecurityController::class, 'scanQR'])->name('petugas-security.scan-qr');
        Route::get('/riwayat-patroli', [PetugasSecurityController::class, 'riwayatPatroli'])->name('petugas-security.riwayat-patroli');

        Route::get('/patroli/create', [PatroliController::class, 'create'])->name('patroli.create');
        Route::post('/patroli/store', [PatroliController::class, 'store'])->name('patroli.store');
    });

    // KABID DUKBIS ROUTES
    Route::middleware('role:kabid_dukbis')->prefix('kabid-dukbis')->group(function () {
        Route::get('/dashboard', [KabidDukbisController::class, 'dashboard'])->name('kabid-dukbis.dashboard');
        Route::get('/data-petugas-security', [KabidDukbisController::class, 'dataPetugas'])->name('kabid-dukbis.data-petugas-security');
        Route::get('/data-lokasi-patroli', [KabidDukbisController::class, 'dataLokasiPatroli'])->name('kabid-dukbis.data-lokasi-patroli');
        Route::get('/laporan-patroli', [KabidDukbisController::class, 'laporanPatroli'])->name('kabid-dukbis.laporan-patroli');
        Route::get('/laporan-patroli/cetak', [KabidDukbisController::class, 'cetakLaporan'])->name('kabid-dukbis.cetak-laporan-patroli');
    });
});

require __DIR__ . '/auth.php';  // Menambahkan route auth
