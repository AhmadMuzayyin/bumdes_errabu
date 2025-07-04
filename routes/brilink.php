<?php

use App\Http\Controllers\BriLink\BriLinkDashboardController;
use App\Http\Controllers\BriLink\SetorTunaiController;
use App\Http\Controllers\BriLink\TarikTunaiController;
use App\Http\Controllers\BriLink\BayarTagihanPlnController;
use App\Http\Controllers\BriLink\LaporanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'brilink'])->name('brilink.')->group(function () {
    // Dashboard BRI Link
    Route::get('/brilink/dashboard', [BriLinkDashboardController::class, 'index'])->name('dashboard');

    // Route Setor Tunai
    Route::resource('/brilink/setor-tunai', SetorTunaiController::class);

    // Route Tarik Tunai
    Route::resource('/brilink/tarik-tunai', TarikTunaiController::class);

    // Route Bayar Tagihan PLN
    Route::resource('/brilink/bayar-tagihan-pln', BayarTagihanPlnController::class);

    // Route Laporan
    Route::get('/brilink/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/brilink/laporan/export-pdf/{type?}', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
});
