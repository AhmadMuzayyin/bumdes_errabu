<?php

use App\Http\Controllers\FotoCopy\DanaMasukController;
use App\Http\Controllers\FotoCopy\FotoCopyDashboardController;
use App\Http\Controllers\FotoCopy\HargaFotoCopyController;
use App\Http\Controllers\FotoCopy\PembayaranFotoCopyController;
use App\Http\Controllers\FotoCopy\PengeluaranFotoCopyController;
use App\Http\Controllers\FotoCopy\LaporanFotoCopyController;
use App\Http\Controllers\FotoCopy\NeracaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'fotocopy'])->as('fotocopy.')->group(function () {
    // Dashboard
    Route::get('/fotocopy/dashboard', FotoCopyDashboardController::class)->name('dashboard');
    Route::controller(DanaMasukController::class)->prefix('fotocopy/pemasukan')->as('pemasukan.')->group(function () {
        Route::get('/', 'index')->name('index');
    });
    // Pengeluaran Foto Copy
    Route::controller(PengeluaranFotoCopyController::class)->prefix('fotocopy/pengeluaran')->as('pengeluaran.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/{pengeluaran}', 'update')->name('update');
        Route::delete('/{pengeluaran}', 'destroy')->name('destroy');
    });
    // Harga Foto Copy
    Route::controller(HargaFotoCopyController::class)->prefix('fotocopy/harga')->as('harga.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/{harga}', 'update')->name('update');
        Route::delete('/{harga}', 'destroy')->name('destroy');
    });
    // Pembayaran Foto Copy
    Route::controller(PembayaranFotoCopyController::class)->prefix('fotocopy/pembayaran')->as('pembayaran.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/{pembayaran}', 'update')->name('update');
        Route::delete('/{pembayaran}', 'destroy')->name('destroy');
    });
    Route::resource('neraca-fotocopy', NeracaController::class);
    // Laporan Foto Copy
    Route::controller(LaporanFotoCopyController::class)->prefix('fotocopy/laporan')->as('laporan.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/generate', 'generate')->name('generate');
    });
});
