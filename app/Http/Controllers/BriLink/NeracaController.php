<?php

namespace App\Http\Controllers\BriLink;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NeracaController extends Controller
{
    public function index()
    {
        $userBadanUsahaId = auth()->user()->badan_usaha->id;
        $tanggalPemasukanAwal = DB::table('income_badan_usahas')
            ->where('badan_usaha_id', $userBadanUsahaId)
            ->min(DB::raw('DATE(created_at)'));
        $tanggalPengeluaranAwal = DB::table('pengeluaran_bri_links')
            ->min('tgl_pengeluaran');
        $tanggalAwal = min($tanggalPemasukanAwal, $tanggalPengeluaranAwal);
        $incomes = DB::table('income_badan_usahas')
            ->where('badan_usaha_id', $userBadanUsahaId)
            ->whereDate('created_at', '>=', $tanggalAwal)
            ->select(
                'id',
                DB::raw('DATE(created_at) as tanggal'),
                'jenis_pemasukan as uraian',
                'nominal as pemasukan',
                DB::raw('0 as pengeluaran')
            );
        $spendings = DB::table('pengeluaran_bri_links')
            ->whereDate('tgl_pengeluaran', '>=', $tanggalAwal)
            ->select(
                'id',
                'tgl_pengeluaran as tanggal',
                'jenis_pengeluaran as uraian',
                DB::raw('0 as pemasukan'),
                DB::raw('(jumlah * harga) as pengeluaran')
            );
        $combined = $incomes
            ->unionAll($spendings)
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();
        $saldo = 0;
        $neraca = $combined->map(function ($item) use (&$saldo) {
            $saldo += ($item->pemasukan - $item->pengeluaran);
            $item->saldo = $saldo;
            return $item;
        });
        return view('neraca.index', compact('neraca'));
    }
}
