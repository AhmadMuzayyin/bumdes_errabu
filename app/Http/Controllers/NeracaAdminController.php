<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NeracaAdminController extends Controller
{
    public function index()
    {
        $tanggalIncomePalingAwal = DB::table('incomes')->min('tanggal');
        $tanggalSpendingPalingAwal = DB::table('spendings')->min('tanggal');
        $tanggalAwal = min($tanggalIncomePalingAwal, $tanggalSpendingPalingAwal);
        $totalPemasukan = DB::table('incomes')
            ->where('tanggal', '<', $tanggalAwal)
            ->sum('nominal');
        $totalPengeluaran = DB::table('spendings')
            ->where('tanggal', '<', $tanggalAwal)
            ->sum('nominal');
        $saldoAwal = $totalPemasukan - $totalPengeluaran;
        $incomes = DB::table('incomes')
            ->where('tanggal', '>=', $tanggalAwal)
            ->select('id', 'tanggal', 'sumber_dana as uraian', 'nominal as pemasukan', DB::raw('0 as pengeluaran'));
        $spendings = DB::table('spendings')
            ->where('tanggal', '>=', $tanggalAwal)
            ->select('id', 'tanggal', 'keterangan as uraian', DB::raw('0 as pemasukan'), 'nominal as pengeluaran');
        $combined = $incomes
            ->unionAll($spendings)
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();
        $saldoBerjalan = $saldoAwal;
        $neraca = $combined->map(function ($item) use (&$saldoBerjalan) {
            $saldoBerjalan += ($item->pemasukan - $item->pengeluaran);
            $item->saldo = $saldoBerjalan;
            return $item;
        });
        return view('neraca.index', compact('neraca'));
    }
}
