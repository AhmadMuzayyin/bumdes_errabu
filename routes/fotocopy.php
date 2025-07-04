<?php

use App\Http\Controllers\FotoCopy\FotoCopyDashboardController;
use App\Http\Controllers\FotoCopy\HargaFotoCopyController;
use App\Http\Controllers\FotoCopy\PembayaranFotoCopyController;
use App\Http\Controllers\FotoCopy\PengeluaranFotoCopyController;
use App\Http\Controllers\FotoCopy\LaporanFotoCopyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'fotocopy'])->group(function () {
    // Dashboard
    Route::get('/fotocopy/dashboard', FotoCopyDashboardController::class)->name('fotocopy.dashboard');

    // Harga Foto Copy
    Route::controller(HargaFotoCopyController::class)->prefix('fotocopy/harga')->as('fotocopy.harga.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/{harga}', 'update')->name('update');
        Route::delete('/{harga}', 'destroy')->name('destroy');
    });

    // Pembayaran Foto Copy
    Route::controller(PembayaranFotoCopyController::class)->prefix('fotocopy/pembayaran')->as('fotocopy.pembayaran.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/{pembayaran}', 'update')->name('update');
        Route::delete('/{pembayaran}', 'destroy')->name('destroy');
    });

    // Pengeluaran Foto Copy
    Route::controller(PengeluaranFotoCopyController::class)->prefix('fotocopy/pengeluaran')->as('fotocopy.pengeluaran.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/{pengeluaran}', 'update')->name('update');
        Route::delete('/{pengeluaran}', 'destroy')->name('destroy');
    });

    // Laporan Foto Copy
    Route::controller(LaporanFotoCopyController::class)->prefix('fotocopy/laporan')->as('fotocopy.laporan.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/generate', 'generate')->name('generate');
    });
});
