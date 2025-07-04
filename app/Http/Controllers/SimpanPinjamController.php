<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;
use App\Models\Simpanan;
use App\Models\PengambilanSimpanan;
use App\Models\Pinjamans;
use App\Models\PengembalianPinjaman;
use App\Models\PengeluaranSimpanPinjam;
use App\Models\SettingPinjaman;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SimpanPinjamController extends Controller
{
    public function dashboard()
    {
        // Statistik dasar
        $totalNasabah = Nasabah::count();
        $totalSimpanan = Simpanan::sum('nominal');
        $totalPengambilanSimpanan = PengambilanSimpanan::sum('nominal');
        $saldoSimpanan = $totalSimpanan - $totalPengambilanSimpanan;

        // Total pinjaman, pengembalian dan pengeluaran
        $totalPinjaman = Pinjamans::sum('nominal');
        $totalPengembalian = PengembalianPinjaman::sum('nominal_cicilan');
        $pinjamanBelumLunas = Pinjamans::where('status', 'Belum Lunas')->sum('nominal');

        // Pinjaman dengan nominal pengembalian
        $totalNominalPengembalian = Pinjamans::whereNotNull('nominal_pengembalian')->sum('nominal_pengembalian');
        $totalBungaPinjaman = $totalNominalPengembalian - Pinjamans::whereNotNull('nominal_pengembalian')->sum('nominal');

        // Pengeluaran
        $totalPengeluaran = PengeluaranSimpanPinjam::sum('jumlah');

        // Pengaturan bunga pinjaman
        $settingBunga = SettingPinjaman::first();
        $persentaseBunga = $settingBunga ? $settingBunga->original_bunga : 0;

        // Data untuk chart simpanan per bulan (12 bulan terakhir)
        $simpananData = Simpanan::select(
            DB::raw('MONTH(tgl_simpan) as bulan'),
            DB::raw('YEAR(tgl_simpan) as tahun'),
            DB::raw('SUM(nominal) as total_simpanan')
        )
            ->whereDate('tgl_simpan', '>=', Carbon::now()->subMonths(12)->startOfMonth())
            ->whereDate('tgl_simpan', '<=', Carbon::now())
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        // Format data untuk chart
        $chartSimpanan = [];
        $bulan = [];

        // Pastikan ada data untuk 12 bulan terakhir (dari bulan saat ini mundur 11 bulan)
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        for ($i = 0; $i < 12; $i++) {
            $currentMonth = $startMonth->copy()->addMonths($i);
            $monthKey = $currentMonth->format('Y-m');
            $bulan[$monthKey] = $currentMonth->locale('id')->isoFormat('MMM YYYY');
            $chartSimpanan[$monthKey] = 0;
        }

        foreach ($simpananData as $data) {
            $key = $data->tahun . '-' . str_pad($data->bulan, 2, '0', STR_PAD_LEFT);
            $chartSimpanan[$key] = $data->total_simpanan;
        }

        //  Data untuk chart pinjaman per bulan
        $pinjamanData = Pinjamans::select(
            DB::raw('MONTH(tgl_pinjam) as bulan'),
            DB::raw('YEAR(tgl_pinjam) as tahun'),
            DB::raw('SUM(nominal) as total_pinjaman')
        )
            ->whereDate('tgl_pinjam', '>=', Carbon::now()->subMonths(12)->startOfMonth())
            ->whereDate('tgl_pinjam', '<=', Carbon::now())
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        // Format data untuk chart pinjaman
        $chartPinjaman = [];
        foreach ($bulan as $key => $label) {
            $chartPinjaman[$key] = 0;
        }

        foreach ($pinjamanData as $data) {
            $key = $data->tahun . '-' . str_pad($data->bulan, 2, '0', STR_PAD_LEFT);
            $chartPinjaman[$key] = $data->total_pinjaman;
        }

        // Nasabah dengan pinjaman terbesar (Top 5)
        $topPeminjam = Pinjamans::with('nasabah')
            ->select('nasabah_id', DB::raw('SUM(nominal) as total_pinjaman'))
            ->groupBy('nasabah_id')
            ->orderBy('total_pinjaman', 'desc')
            ->limit(5)
            ->get();

        // Data untuk chart status pinjaman (Doughnut Chart)
        $statusPinjaman = Pinjamans::select('status', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('status')
            ->get();

        // Persentase Pengembalian dari Total Pinjaman
        $persentasePengembalian = 0;
        if ($totalPinjaman > 0) {
            $persentasePengembalian = round(($totalPengembalian / $totalPinjaman) * 100, 1);
        }

        return view('simpan-pinjam.dashboard', compact(
            'totalNasabah',
            'totalSimpanan',
            'totalPengambilanSimpanan',
            'saldoSimpanan',
            'totalPinjaman',
            'totalPengembalian',
            'pinjamanBelumLunas',
            'totalPengeluaran',
            'persentaseBunga',
            'totalBungaPinjaman',
            'chartSimpanan',
            'chartPinjaman',
            'bulan',
            'topPeminjam',
            'statusPinjaman',
            'persentasePengembalian'
        ));
    }
}
