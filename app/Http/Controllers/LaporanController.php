<?php

namespace App\Http\Controllers;

use App\Models\BadanUsaha;
use App\Models\Income;
use App\Models\Spending;
use App\Models\PembayaranFotoCopy;
use App\Models\PengeluaranFotoCopy;
use App\Models\BriLinkSetorTunai;
use App\Models\BriLinkTarikTunai;
use App\Models\BriLinkBayarTagihanPln;
use App\Models\Simpanan;
use App\Models\PengambilanSimpanan;
use App\Models\Pinjamans;
use App\Models\PengembalianPinjamans;
use App\Models\PengeluaranSimpanPinjam;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Get all badan usaha for filter dropdown
        $badanUsahaList = BadanUsaha::all();

        // Set default date range to current month if not provided
        $tanggalMulai = $request->get('start') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $tanggalSelesai = $request->get('end') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $badanUsahaId = $request->get('badan_usaha_id');

        // Data collection arrays
        $danaMasuk = [];
        $danaKeluar = [];
        $summaryDanaMasuk = [];
        $summaryDanaKeluar = [];
        $totalDanaMasuk = 0;
        $totalDanaKeluar = 0;

        // 1. General Income & Spending
        $incomeQuery = Income::with('badan_usaha')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tanggal', 'asc');

        $spendingQuery = Spending::whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tanggal', 'asc');

        if ($badanUsahaId) {
            $incomeQuery->where('badan_usaha_id', $badanUsahaId);
        }

        $incomes = $incomeQuery->get();
        $spendings = $spendingQuery->get();

        $danaMasuk['umum'] = $incomes;
        $danaKeluar['umum'] = $spendings;

        $summaryDanaMasuk['umum'] = $incomes->sum(function ($item) {
            return (float) str_replace(['Rp. ', '.'], '', $item->getOriginalNominalAttribute());
        });

        $summaryDanaKeluar['umum'] = $spendings->sum(function ($item) {
            return (float) str_replace(['Rp. ', '.'], '', $item->getOriginalNominalAttribute());
        });

        $totalDanaMasuk += $summaryDanaMasuk['umum'];
        $totalDanaKeluar += $summaryDanaKeluar['umum'];

        // 2. Foto Copy data
        $fotocopyPembayaran = PembayaranFotoCopy::whereBetween('tgl_pembayaran', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tgl_pembayaran')
            ->get();

        $fotocopyPengeluaran = PengeluaranFotoCopy::whereBetween('tgl_pengeluaran', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tgl_pengeluaran')
            ->get();

        $danaMasuk['fotocopy'] = $fotocopyPembayaran;
        $danaKeluar['fotocopy'] = $fotocopyPengeluaran;

        $summaryDanaMasuk['fotocopy'] = $fotocopyPembayaran->sum('total_pembayaran');
        $summaryDanaKeluar['fotocopy'] = $fotocopyPengeluaran->sum('jumlah');

        $totalDanaMasuk += $summaryDanaMasuk['fotocopy'];
        $totalDanaKeluar += $summaryDanaKeluar['fotocopy'];

        // 3. BRI Link data
        $brilinkSetorTunai = BriLinkSetorTunai::whereBetween('tgl_setor_tunai', [$tanggalMulai, $tanggalSelesai])->get();
        $brilinkTarikTunai = BriLinkTarikTunai::whereBetween('tgl_tarik_tunai', [$tanggalMulai, $tanggalSelesai])->get();
        $brilinkBayarPln = BriLinkBayarTagihanPln::whereBetween('tgl_transaksi', [$tanggalMulai, $tanggalSelesai])->get();

        $brilinkTransaksi = [
            'setor_tunai' => $brilinkSetorTunai,
            'tarik_tunai' => $brilinkTarikTunai,
            'bayar_pln' => $brilinkBayarPln
        ];

        // BRI Link income is from admin fees
        $summaryDanaMasuk['brilink'] = $brilinkSetorTunai->sum(function ($item) {
            return (float) ($item->admin_fee ?? 0);
        }) + $brilinkTarikTunai->sum(function ($item) {
            return (float) ($item->admin_fee ?? 0);
        }) + $brilinkBayarPln->sum(function ($item) {
            return (float) ($item->admin_fee ?? 0);
        });

        $totalDanaMasuk += $summaryDanaMasuk['brilink'];

        // 4. Simpan Pinjam data
        $simpanan = Simpanan::whereBetween('tgl_simpan', [$tanggalMulai, $tanggalSelesai])->get();
        $pengambilanSimpanan = PengambilanSimpanan::whereBetween('tgl_pengambilan', [$tanggalMulai, $tanggalSelesai])->get();
        $pinjaman = Pinjamans::whereBetween('tgl_pinjam', [$tanggalMulai, $tanggalSelesai])->get();
        $pengembalianPinjaman = PengembalianPinjamans::whereBetween('tgl_pengembalian_sementara', [$tanggalMulai, $tanggalSelesai])->get();
        $pengeluaranSP = PengeluaranSimpanPinjam::whereBetween('tgl_pengeluaran', [$tanggalMulai, $tanggalSelesai])->get();

        $simpanPinjamData = [
            'simpanan' => $simpanan,
            'pengambilan_simpanan' => $pengambilanSimpanan,
            'pinjaman' => $pinjaman,
            'pengembalian_pinjaman' => $pengembalianPinjaman,
            'pengeluaran' => $pengeluaranSP
        ];

        // Simpan Pinjam income: simpanan, pengembalian pinjaman (termasuk bunga)
        $summaryDanaMasuk['simpan_pinjam'] = $simpanan->sum(function ($item) {
            return (float) $item->getOriginalNominalAttribute();
        }) + $pengembalianPinjaman->sum(function ($item) {
            return (float) $item->getOriginalNominalCicilanAttribute();
        });

        // Simpan Pinjam expenses: pengambilan simpanan, pinjaman, pengeluaran
        $summaryDanaKeluar['simpan_pinjam'] = $pengambilanSimpanan->sum(function ($item) {
            return (float) $item->getOriginalNominalAttribute();
        }) + $pinjaman->sum(function ($item) {
            return (float) $item->getOriginalNominalAttribute();
        }) + $pengeluaranSP->sum(function ($item) {
            return (float) $item->getOriginalJumlahAttribute();
        });

        $totalDanaMasuk += $summaryDanaMasuk['simpan_pinjam'];
        $totalDanaKeluar += $summaryDanaKeluar['simpan_pinjam'];

        return view('laporan.index', compact(
            'tanggalMulai',
            'tanggalSelesai',
            'badanUsahaId',
            'badanUsahaList',
            'danaMasuk',
            'danaKeluar',
            'summaryDanaMasuk',
            'summaryDanaKeluar',
            'totalDanaMasuk',
            'totalDanaKeluar',
            'brilinkTransaksi',
            'simpanPinjamData'
        ));
    }

    /**
     * Export laporan ke PDF berdasarkan jenis dan periode
     */
    public function exportPdf(Request $request)
    {
        // Validate input
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'type' => 'required|in:all,income,expense',
            'badan_usaha_id' => 'nullable|exists:badan_usahas,id'
        ]);

        $tanggalMulai = $request->start;
        $tanggalSelesai = $request->end;
        $type = $request->type;
        $badanUsahaId = $request->badan_usaha_id;

        // Call the same logic as index but in compact form for PDF generation
        $data = $this->collectReportData($tanggalMulai, $tanggalSelesai, $badanUsahaId);

        // Format dates for display
        $periodeDisplay = Carbon::parse($tanggalMulai)->format('d/m/Y') . ' - ' . Carbon::parse($tanggalSelesai)->format('d/m/Y');

        // Format date for filename
        $periodeFilename = Carbon::parse($tanggalMulai)->format('d-m-Y') . ' sampai ' . Carbon::parse($tanggalSelesai)->format('d-m-Y');

        $viewData = [
            'data' => $data,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'periodeDisplay' => $periodeDisplay,
            'badanUsahaId' => $badanUsahaId,
            'badanUsahaList' => BadanUsaha::all(),
            'tanggalCetak' => Carbon::now()->format('d/m/Y H:i:s'),
            'type' => $type
        ];

        // Determine which PDF view to use based on type
        $view = 'laporan.pdf.' . $type . '_pdf';

        // Generate the PDF
        try {
            $pdf = PDF::loadView($view, $viewData);
            $pdf->setPaper('a4', 'landscape');

            $title = $type === 'all' ? 'Laporan Keuangan' : ($type === 'income' ? 'Laporan Dana Masuk' : 'Laporan Dana Keluar');

            $fileName = $title . ' - ' . $periodeFilename . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    /**
     * Helper function to collect report data for both index and exportPdf
     */
    private function collectReportData($tanggalMulai, $tanggalSelesai, $badanUsahaId = null)
    {
        // Data collection arrays
        $danaMasuk = [];
        $danaKeluar = [];
        $summaryDanaMasuk = [];
        $summaryDanaKeluar = [];
        $totalDanaMasuk = 0;
        $totalDanaKeluar = 0;

        // 1. General Income & Spending
        $incomeQuery = Income::with('badan_usaha')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tanggal', 'asc');

        $spendingQuery = Spending::whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tanggal', 'asc');

        if ($badanUsahaId) {
            $incomeQuery->where('badan_usaha_id', $badanUsahaId);
        }

        $incomes = $incomeQuery->get();
        $spendings = $spendingQuery->get();

        $danaMasuk['umum'] = $incomes;
        $danaKeluar['umum'] = $spendings;

        $summaryDanaMasuk['umum'] = $incomes->sum(function ($item) {
            return (float) str_replace(['Rp. ', '.'], '', $item->getOriginalNominalAttribute());
        });

        $summaryDanaKeluar['umum'] = $spendings->sum(function ($item) {
            return (float) str_replace(['Rp. ', '.'], '', $item->getOriginalNominalAttribute());
        });

        $totalDanaMasuk += $summaryDanaMasuk['umum'];
        $totalDanaKeluar += $summaryDanaKeluar['umum'];

        // 2. Foto Copy data
        $fotocopyPembayaran = PembayaranFotoCopy::whereBetween('tgl_pembayaran', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tgl_pembayaran')
            ->get();

        $fotocopyPengeluaran = PengeluaranFotoCopy::whereBetween('tgl_pengeluaran', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tgl_pengeluaran')
            ->get();

        $danaMasuk['fotocopy'] = $fotocopyPembayaran;
        $danaKeluar['fotocopy'] = $fotocopyPengeluaran;

        $summaryDanaMasuk['fotocopy'] = $fotocopyPembayaran->sum('total_pembayaran');
        $summaryDanaKeluar['fotocopy'] = $fotocopyPengeluaran->sum('jumlah');

        $totalDanaMasuk += $summaryDanaMasuk['fotocopy'];
        $totalDanaKeluar += $summaryDanaKeluar['fotocopy'];

        // 3. BRI Link data
        $brilinkSetorTunai = BriLinkSetorTunai::whereBetween('tgl_setor_tunai', [$tanggalMulai, $tanggalSelesai])->get();
        $brilinkTarikTunai = BriLinkTarikTunai::whereBetween('tgl_tarik_tunai', [$tanggalMulai, $tanggalSelesai])->get();
        $brilinkBayarPln = BriLinkBayarTagihanPln::whereBetween('tgl_transaksi', [$tanggalMulai, $tanggalSelesai])->get();

        $brilinkTransaksi = [
            'setor_tunai' => $brilinkSetorTunai,
            'tarik_tunai' => $brilinkTarikTunai,
            'bayar_pln' => $brilinkBayarPln
        ];

        // BRI Link income is from admin fees
        $summaryDanaMasuk['brilink'] = $brilinkSetorTunai->sum(function ($item) {
            return (float) ($item->admin_fee ?? 0);
        }) + $brilinkTarikTunai->sum(function ($item) {
            return (float) ($item->admin_fee ?? 0);
        }) + $brilinkBayarPln->sum(function ($item) {
            return (float) ($item->admin_fee ?? 0);
        });

        $totalDanaMasuk += $summaryDanaMasuk['brilink'];

        // 4. Simpan Pinjam data
        $simpanan = Simpanan::whereBetween('tgl_simpan', [$tanggalMulai, $tanggalSelesai])->get();
        $pengambilanSimpanan = PengambilanSimpanan::whereBetween('tgl_pengambilan', [$tanggalMulai, $tanggalSelesai])->get();
        $pinjaman = Pinjamans::whereBetween('tgl_pinjam', [$tanggalMulai, $tanggalSelesai])->get();
        $pengembalianPinjaman = PengembalianPinjamans::whereBetween('tgl_pengembalian_sementara', [$tanggalMulai, $tanggalSelesai])->get();
        $pengeluaranSP = PengeluaranSimpanPinjam::whereBetween('tgl_pengeluaran', [$tanggalMulai, $tanggalSelesai])->get();

        $simpanPinjamData = [
            'simpanan' => $simpanan,
            'pengambilan_simpanan' => $pengambilanSimpanan,
            'pinjaman' => $pinjaman,
            'pengembalian_pinjaman' => $pengembalianPinjaman,
            'pengeluaran' => $pengeluaranSP
        ];

        // Simpan Pinjam income: simpanan, pengembalian pinjaman (termasuk bunga)
        $summaryDanaMasuk['simpan_pinjam'] = $simpanan->sum(function ($item) {
            return (float) $item->getOriginalNominalAttribute();
        }) + $pengembalianPinjaman->sum(function ($item) {
            return (float) $item->getOriginalNominalCicilanAttribute();
        });

        // Simpan Pinjam expenses: pengambilan simpanan, pinjaman, pengeluaran
        $summaryDanaKeluar['simpan_pinjam'] = $pengambilanSimpanan->sum(function ($item) {
            return (float) $item->getOriginalNominalAttribute();
        }) + $pinjaman->sum(function ($item) {
            return (float) $item->getOriginalNominalAttribute();
        }) + $pengeluaranSP->sum(function ($item) {
            return (float) $item->getOriginalJumlahAttribute();
        });

        $totalDanaMasuk += $summaryDanaMasuk['simpan_pinjam'];
        $totalDanaKeluar += $summaryDanaKeluar['simpan_pinjam'];

        return [
            'danaMasuk' => $danaMasuk,
            'danaKeluar' => $danaKeluar,
            'summaryDanaMasuk' => $summaryDanaMasuk,
            'summaryDanaKeluar' => $summaryDanaKeluar,
            'totalDanaMasuk' => $totalDanaMasuk,
            'totalDanaKeluar' => $totalDanaKeluar,
            'brilinkTransaksi' => $brilinkTransaksi,
            'simpanPinjamData' => $simpanPinjamData
        ];
    }
}
