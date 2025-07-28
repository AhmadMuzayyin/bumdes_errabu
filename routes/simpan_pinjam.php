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
use App\Models\Spending;

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
    Route::get('pengembalian-pinjaman/kategori/{kategori}', [PengembalianPinjamanController::class, 'kategori'])
        ->name('pengembalian-pinjaman.kategori');
    Route::get('get-pinjaman-by-nasabah/{id}', [PengembalianPinjamanController::class, 'getPinjamanByNasabah'])
        ->name('get-pinjaman-by-nasabah');

    // Pengeluaran dan Pemasukan
    Route::resource('pengeluaran', PengeluaranSimpanPinjamController::class);
    Route::get('pemasukan', function () {
        $incomes = Spending::where('badan_usaha_id', auth()->user()->badan_usaha->id)
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

        // Form laporan (simpan untuk compatibility)
        Route::get('form-simpanan', [LaporanSimpanPinjamController::class, 'formSimpanan'])
            ->name('form-simpanan');
        Route::get('form-pengambilan-simpanan', [LaporanSimpanPinjamController::class, 'formPengambilanSimpanan'])
            ->name('form-pengambilan-simpanan');
        Route::get('form-pinjaman', [LaporanSimpanPinjamController::class, 'formPinjaman'])
            ->name('form-pinjaman');
        Route::get('form-pengembalian-pinjaman', [LaporanSimpanPinjamController::class, 'formPengembalianPinjaman'])
            ->name('form-pengembalian-pinjaman');
        Route::get('form-pengeluaran', [LaporanSimpanPinjamController::class, 'formPengeluaran'])
            ->name('form-pengeluaran');
        Route::get('form-rekap-simpanan', [LaporanSimpanPinjamController::class, 'formRekapSimpanan'])
            ->name('form-rekap-simpanan');
        Route::get('form-rekap-pinjaman', [LaporanSimpanPinjamController::class, 'formRekapPinjaman'])
            ->name('form-rekap-pinjaman');

        // API endpoints untuk data laporan
        Route::prefix('api')->group(function () {
            Route::post('simpanan', [LaporanSimpanPinjamController::class, 'apiSimpanan'])
                ->name('api.simpanan');
            Route::post('pengambilan', [LaporanSimpanPinjamController::class, 'apiPengambilan'])
                ->name('api.pengambilan');
            Route::post('pinjaman', [LaporanSimpanPinjamController::class, 'apiPinjaman'])
                ->name('api.pinjaman');
            Route::post('pengembalian', [LaporanSimpanPinjamController::class, 'apiPengembalian'])
                ->name('api.pengembalian');
            Route::post('pengeluaran', [LaporanSimpanPinjamController::class, 'apiPengeluaran'])
                ->name('api.pengeluaran');
        });

        // Endpoints untuk laporan PDF
        Route::post('update', [LaporanSimpanPinjamController::class, 'updateLaporan'])
            ->name('update');
        Route::post('simpanan', [LaporanSimpanPinjamController::class, 'laporanSimpanan'])
            ->name('simpanan');
        Route::post('pengambilan-simpanan', [LaporanSimpanPinjamController::class, 'laporanPengambilanSimpanan'])
            ->name('pengambilan-simpanan');
        Route::post('pinjaman', [LaporanSimpanPinjamController::class, 'laporanPinjaman'])
            ->name('pinjaman');
        Route::post('pengembalian-pinjaman', [LaporanSimpanPinjamController::class, 'laporanPengembalianPinjaman'])
            ->name('pengembalian-pinjaman');
        Route::post('pengeluaran', [LaporanSimpanPinjamController::class, 'laporanPengeluaran'])
            ->name('pengeluaran');
        Route::post('rekap-simpanan', [LaporanSimpanPinjamController::class, 'rekapSimpananNasabah'])
            ->name('rekap-simpanan');
        Route::post('rekap-pinjaman', [LaporanSimpanPinjamController::class, 'rekapPinjamanNasabah'])
            ->name('rekap-pinjaman');
    });
});
