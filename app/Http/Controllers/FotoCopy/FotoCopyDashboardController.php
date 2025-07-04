<?php

namespace App\Http\Controllers\FotoCopy;

use App\Http\Controllers\Controller;
use App\Models\HargaFotoCopy;
use App\Models\PembayaranFotoCopy;
use App\Models\PengeluaranFotoCopy;
use Illuminate\Support\Facades\Auth;

class FotoCopyDashboardController extends Controller
{
    public function __invoke()
    {
        // Pastikan user yang login adalah operator foto copy
        if (Auth::user()->role != 'operator foto copy' && Auth::user()->role != 'admin') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses');
        }

        $harga = HargaFotoCopy::first();

        // Data pembayaran dan pengeluaran per bulan untuk grafik
        $pembayaranPerBulan = PembayaranFotoCopy::selectRaw('SUM(total_pembayaran) as total, DATE_FORMAT(tgl_pembayaran, "%Y-%m") as month')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $pengeluaranPerBulan = PengeluaranFotoCopy::selectRaw('SUM(harga) as total, DATE_FORMAT(tgl_pengeluaran, "%Y-%m") as month')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Data terakhir
        $pembayaranTerakhir = PembayaranFotoCopy::latest('tgl_pembayaran')->take(5)->get();
        $pengeluaranTerakhir = PengeluaranFotoCopy::latest('tgl_pengeluaran')->take(5)->get();

        // Total pembayaran dan pengeluaran
        $totalPembayaran = PembayaranFotoCopy::sum('total_pembayaran');
        $totalPengeluaran = PengeluaranFotoCopy::sum('harga');

        return view('fotocopy.dashboard', compact(
            'harga',
            'pembayaranPerBulan',
            'pengeluaranPerBulan',
            'pembayaranTerakhir',
            'pengeluaranTerakhir',
            'totalPembayaran',
            'totalPengeluaran'
        ));
    }
}
