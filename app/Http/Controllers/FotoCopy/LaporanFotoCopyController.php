<?php

namespace App\Http\Controllers\FotoCopy;

use App\Http\Controllers\Controller;
use App\Models\PembayaranFotoCopy;
use App\Models\PengeluaranFotoCopy;
use Illuminate\Http\Request;

class LaporanFotoCopyController extends Controller
{
    /**
     * Menampilkan laporan fotocopy
     */
    public function index(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai') ?? now()->startOfMonth()->format('Y-m-d');
        $tanggalSelesai = $request->input('tanggal_selesai') ?? now()->endOfMonth()->format('Y-m-d');

        $pembayaran = PembayaranFotoCopy::whereBetween('tgl_pembayaran', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tgl_pembayaran')
            ->get();

        $pengeluaran = PengeluaranFotoCopy::whereBetween('tgl_pengeluaran', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tgl_pengeluaran')
            ->get();

        return view('fotocopy.laporan.index', compact('pembayaran', 'pengeluaran', 'tanggalMulai', 'tanggalSelesai'));
    }

    /**
     * Menghasilkan laporan fotocopy
     */
    public function generate(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;

        $pembayaran = PembayaranFotoCopy::whereBetween('tgl_pembayaran', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tgl_pembayaran')
            ->get();

        $pengeluaran = PengeluaranFotoCopy::whereBetween('tgl_pengeluaran', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tgl_pengeluaran')
            ->get();

        return view('fotocopy.laporan.hasil', compact('pembayaran', 'pengeluaran', 'tanggalMulai', 'tanggalSelesai'));
    }
}
