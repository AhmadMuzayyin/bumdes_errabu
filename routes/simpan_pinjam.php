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

// Dashboard Simpan Pinjam
Route::get('/simpan-pinjam/dashboard', [SimpanPinjamController::class, 'dashboard'])
    ->name('simpan-pinjam.dashboard')
    ->middleware(['auth', 'simpan.pinjam']);

// Nasabah
Route::resource('nasabah', NasabahController::class)->middleware(['auth', 'simpan.pinjam']);

// Simpanan
Route::resource('simpanan', SimpananController::class)->middleware(['auth', 'simpan.pinjam']);
Route::get('simpanan/kategori/{kategori}', [SimpananController::class, 'kategori'])
    ->name('simpanan.kategori')
    ->middleware(['auth', 'simpan.pinjam']);

// Pengambilan Simpanan
Route::resource('pengambilan-simpanan', PengambilanSimpananController::class)->middleware(['auth', 'simpan.pinjam']);
Route::get('pengambilan-simpanan/kategori/{kategori}', [PengambilanSimpananController::class, 'kategori'])
    ->name('pengambilan-simpanan.kategori')
    ->middleware(['auth', 'simpan.pinjam']);

// Pinjaman
Route::resource('pinjaman', PinjamanController::class)->middleware(['auth', 'simpan.pinjam']);
Route::get('pinjaman/kategori/{kategori}', [PinjamanController::class, 'kategori'])
    ->name('pinjaman.kategori')
    ->middleware(['auth', 'simpan.pinjam']);

// Pengembalian Pinjaman
Route::resource('pengembalian-pinjaman', PengembalianPinjamanController::class)->middleware(['auth', 'simpan.pinjam']);
Route::get('pengembalian-pinjaman/kategori/{kategori}', [PengembalianPinjamanController::class, 'kategori'])
    ->name('pengembalian-pinjaman.kategori')
    ->middleware(['auth', 'simpan.pinjam']);
Route::get('get-pinjaman-by-nasabah/{id}', [PengembalianPinjamanController::class, 'getPinjamanByNasabah'])
    ->name('get-pinjaman-by-nasabah')
    ->middleware(['auth', 'simpan.pinjam']);

// Pengeluaran
Route::resource('pengeluaran', PengeluaranSimpanPinjamController::class)->middleware(['auth', 'simpan.pinjam']);

// Setting Pinjaman
Route::get('setting-pinjaman', [SettingPinjamanController::class, 'index'])->name('setting-pinjaman.index')->middleware(['auth', 'simpan.pinjam']);
Route::put('setting-pinjaman/{settingPinjaman}', [SettingPinjamanController::class, 'update'])->name('setting-pinjaman.update')->middleware(['auth', 'simpan.pinjam']);

// Laporan
Route::prefix('laporan')->middleware(['auth', 'simpan.pinjam'])->name('laporan.')->group(function () {
    Route::get('/', [LaporanSimpanPinjamController::class, 'index'])->name('index');

    // Form laporan (simpan untuk compatibility)
    Route::get('form-simpanan', [LaporanSimpanPinjamController::class, 'formSimpanan'])->name('form-simpanan');
    Route::get('form-pengambilan-simpanan', [LaporanSimpanPinjamController::class, 'formPengambilanSimpanan'])->name('form-pengambilan-simpanan');
    Route::get('form-pinjaman', [LaporanSimpanPinjamController::class, 'formPinjaman'])->name('form-pinjaman');
    Route::get('form-pengembalian-pinjaman', [LaporanSimpanPinjamController::class, 'formPengembalianPinjaman'])->name('form-pengembalian-pinjaman');
    Route::get('form-pengeluaran', [LaporanSimpanPinjamController::class, 'formPengeluaran'])->name('form-pengeluaran');
    Route::get('form-rekap-simpanan', [LaporanSimpanPinjamController::class, 'formRekapSimpanan'])->name('form-rekap-simpanan');
    Route::get('form-rekap-pinjaman', [LaporanSimpanPinjamController::class, 'formRekapPinjaman'])->name('form-rekap-pinjaman');

    // API endpoints untuk data laporan
    Route::post('api/simpanan', [LaporanSimpanPinjamController::class, 'apiSimpanan'])->name('api.simpanan');
    Route::post('api/pengambilan', [LaporanSimpanPinjamController::class, 'apiPengambilan'])->name('api.pengambilan');
    Route::post('api/pinjaman', [LaporanSimpanPinjamController::class, 'apiPinjaman'])->name('api.pinjaman');
    Route::post('api/pengembalian', [LaporanSimpanPinjamController::class, 'apiPengembalian'])->name('api.pengembalian');
    Route::post('api/pengeluaran', [LaporanSimpanPinjamController::class, 'apiPengeluaran'])->name('api.pengeluaran');

    // Endpoints untuk laporan PDF
    Route::post('update', [LaporanSimpanPinjamController::class, 'updateLaporan'])->name('update');
    Route::post('simpanan', [LaporanSimpanPinjamController::class, 'laporanSimpanan'])->name('simpanan');
    Route::post('pengambilan-simpanan', [LaporanSimpanPinjamController::class, 'laporanPengambilanSimpanan'])->name('pengambilan-simpanan');
    Route::post('pinjaman', [LaporanSimpanPinjamController::class, 'laporanPinjaman'])->name('pinjaman');
    Route::post('pengembalian-pinjaman', [LaporanSimpanPinjamController::class, 'laporanPengembalianPinjaman'])->name('pengembalian-pinjaman');
    Route::post('pengeluaran', [LaporanSimpanPinjamController::class, 'laporanPengeluaran'])->name('pengeluaran');
    Route::post('rekap-simpanan', [LaporanSimpanPinjamController::class, 'rekapSimpananNasabah'])->name('rekap-simpanan');
    Route::post('rekap-pinjaman', [LaporanSimpanPinjamController::class, 'rekapPinjamanNasabah'])->name('rekap-pinjaman');
});
