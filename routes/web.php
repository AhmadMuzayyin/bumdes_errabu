<?php

use App\Http\Controllers\BadanUsahaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\SpendingController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class);

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');
Route::controller(BadanUsahaController::class)->as('badan_usaha.')->group(function () {
    Route::get('/badan_usaha', 'index')->name('index');
    Route::post('/badan_usaha', 'store')->name('store');
    Route::post('/badan_usaha/{badan_usaha}/update', 'update')->name('update');
    Route::get('/badan_usaha/{badan_usaha}/destroy', 'destroy')->name('destroy');
});
Route::controller(OperatorController::class)->as('operator.')->group(function () {
    Route::get('/operator', 'index')->name('index');
    Route::post('/operator', 'store')->name('store');
    Route::post('/operator/{operator}/update', 'update')->name('update');
    Route::get('/operator/{operator}/destroy', 'destroy')->name('destroy');
});
Route::controller(IncomeController::class)->as('income.')->group(function () {
    Route::get('/income', 'index')->name('index');
    Route::post('/income', 'store')->name('store');
    Route::post('/income/{income}/update', 'update')->name('update');
    Route::get('/income/{income}/destroy', 'destroy')->name('destroy');
});
Route::controller(SpendingController::class)->as('spending.')->group(function () {
    Route::get('/spending', 'index')->name('index');
    Route::post('/spending', 'store')->name('store');
    Route::post('/spending/{spending}/update', 'update')->name('update');
    Route::get('/spending/{spending}/destroy', 'destroy')->name('destroy');
});
Route::controller(TransactionController::class)->as('transaction.')->group(function () {
    Route::get('/transaction', 'index')->name('index');
    Route::post('/transaction', 'store')->name('store');
    Route::post('/transaction/{transaction}/update', 'update')->name('update');
    Route::get('/transaction/{transaction}/destroy', 'destroy')->name('destroy');
});
Route::controller(LaporanController::class)->middleware(['auth', 'admin'])->as('laporan.umum.')->group(function () {
    Route::get('/laporan-umum', 'index')->name('index');
    Route::post('/laporan-umum/export-pdf', 'exportPdf')->name('export-pdf');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/simpan_pinjam.php';
require __DIR__ . '/fotocopy.php';
