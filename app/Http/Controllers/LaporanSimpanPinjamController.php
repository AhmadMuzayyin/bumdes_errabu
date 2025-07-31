<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;
use App\Models\Simpanan;
use App\Models\PengambilanSimpanan;
use App\Models\Pinjamans;
use App\Models\PengembalianPinjamans;
use App\Models\PengeluaranSimpanPinjam;
use Carbon\Carbon;

class LaporanSimpanPinjamController extends Controller
{
    /**
     * Tampilan halaman laporan utama
     */
    public function index(Request $request)
    {
        $tanggalAwal = $request->get('start', date('Y-m-01'));
        $tanggalAkhir = $request->get('end', date('Y-m-t'));

        // Simpanan
        $simpanan = Simpanan::with('nasabah')
            ->whereBetween('tgl_simpan', [$tanggalAwal, $tanggalAkhir])
            ->get();
        $total_simpanan = $simpanan->sum('OriginalNominal');

        // Pengambilan Simpanan
        $pengambilan = PengambilanSimpanan::with('nasabah')
            ->whereBetween('tgl_pengambilan', [$tanggalAwal, $tanggalAkhir])
            ->get();
        $total_pengambilan = $pengambilan->sum('OriginalNominal');

        // Pinjaman
        $pinjaman = Pinjamans::with('nasabah')
            ->whereBetween('tgl_pinjam', [$tanggalAwal, $tanggalAkhir])
            ->get();
        $total_pinjaman = $pinjaman->sum('OriginalNominal');

        // Pengembalian Pinjaman
        $pengembalian = PengembalianPinjamans::with(['pinjaman.nasabah'])
            ->whereBetween('tgl_pengembalian_sementara', [$tanggalAwal, $tanggalAkhir])
            ->get();
        $total_pengembalian = $pengembalian->sum('OriginalNominalCicilan');

        // Pengeluaran
        $pengeluaran = PengeluaranSimpanPinjam::whereBetween('tgl_pengeluaran', [$tanggalAwal, $tanggalAkhir])
            ->get();
        $total_pengeluaran = $pengeluaran->sum(function ($item) {
            return $item->harga * $item->jumlah;
        });

        return view('simpan-pinjam.laporan.index', compact(
            'simpanan',
            'total_simpanan',
            'pengambilan',
            'total_pengambilan',
            'pinjaman',
            'total_pinjaman',
            'pengembalian',
            'total_pengembalian',
            'pengeluaran',
            'total_pengeluaran',
            'tanggalAwal',
            'tanggalAkhir'
        ));
    }
}
