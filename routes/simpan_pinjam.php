<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SimpanPinjamController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\PengambilanSimpananController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\PengembalianPinjamanController;
use App\Http\Controllers\PengeluaranSimpanPinjamController;
use App\Http\Controllers\LaporanSimpanPinjamController;
use App\Http\Controllers\SettingPinjamanController;
use App\Models\IncomeBadanUsaha;

// Route Simpan Pinjam di-grup berdasarkan fungsionalitas
Route::middleware(['auth', 'simpan.pinjam'])->group(function () {
    // Dashboard Simpan Pinjam
    Route::get('/simpan-pinjam/dashboard', [SimpanPinjamController::class, 'dashboard'])
        ->name('simpan-pinjam.dashboard');

    // Nasabah
    Route::resource('nasabah', NasabahController::class);

    // Simpanan
    Route::resource('simpanan', SimpananController::class);
    Route::get('simpanan/kategori/{kategori}', [SimpananController::class, 'kategori'])
        ->name('simpanan.kategori');

    // Pengambilan Simpanan
    Route::resource('pengambilan-simpanan', PengambilanSimpananController::class);
    Route::get('pengambilan-simpanan/kategori/{kategori}', [PengambilanSimpananController::class, 'kategori'])
        ->name('pengambilan-simpanan.kategori');

    // Pinjaman
    Route::resource('pinjaman', PinjamanController::class);
    Route::get('pinjaman/kategori/{kategori}', [PinjamanController::class, 'kategori'])
        ->name('pinjaman.kategori');

    // Pengembalian Pinjaman
    Route::resource('pengembalian-pinjaman', PengembalianPinjamanController::class);
    Route::get('get-pinjaman-by-nasabah/{id}', [PengembalianPinjamanController::class, 'getPinjamanByNasabah'])
        ->name('get-pinjaman-by-nasabah');

    // Pengeluaran dan Pemasukan
    Route::resource('pengeluaran', PengeluaranSimpanPinjamController::class);
    Route::get('pemasukan', function () {
        $incomes = IncomeBadanUsaha::where('badan_usaha_id', auth()->user()->badan_usaha->id)
            ->get();
        return view('simpan-pinjam.pemasukan.index', compact('incomes'));
    })->name('pemasukan.index');

    // Setting Pinjaman
    Route::get('setting-pinjaman', [SettingPinjamanController::class, 'index'])
        ->name('setting-pinjaman.index');
    Route::put('setting-pinjaman/{settingPinjaman}', [SettingPinjamanController::class, 'update'])
        ->name('setting-pinjaman.update');

    // Laporan - Grouped with prefix and name
    Route::prefix('laporan')->name('laporan.')->group(function () {
        // Halaman utama laporan
        Route::get('/', [LaporanSimpanPinjamController::class, 'index'])->name('index');
    });
});
