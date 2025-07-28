<?php

namespace App\Http\Controllers\BriLink;

use App\Http\Controllers\Controller;
use App\Models\BriLinkSetorTunai;
use App\Models\BriLinkTarikTunai;
use App\Models\BriLinkBayarTagihanPln;
use App\Models\PengeluaranBriLink;
use App\Models\Spending;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $start_date = $request->start_date ?? Carbon::now()->subMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? Carbon::now()->format('Y-m-d');

        $start = Carbon::parse($start_date)->startOfDay();
        $end = Carbon::parse($end_date)->endOfDay();

        $setor_tunai = BriLinkSetorTunai::whereBetween('tgl_setor_tunai', [$start, $end])->get();
        $tarik_tunai = BriLinkTarikTunai::whereBetween('tgl_tarik_tunai', [$start, $end])->get();
        $bayar_tagihan = BriLinkBayarTagihanPln::whereBetween('tgl_transaksi', [$start, $end])->get();

        $total_setor = $setor_tunai->sum('nominal');
        $total_tarik = $tarik_tunai->sum('nominal');
        $total_bayar_pln = $bayar_tagihan->sum('nominal');

        $pengeluaran = PengeluaranBriLink::whereBetween('tgl_pengeluaran', [$start, $end])
            ->orderBy('tgl_pengeluaran')
            ->get();
        $pemasukan = Spending::where('badan_usaha_id', auth()->user()->badan_usaha->id)
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal')
            ->get();

        $laporan = [
            'setor_tunai' => $setor_tunai,
            'tarik_tunai' => $tarik_tunai,
            'bayar_tagihan' => $bayar_tagihan,
            'total_setor' => $total_setor,
            'total_tarik' => $total_tarik,
            'total_bayar_pln' => $total_bayar_pln,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'pengeluaran' => $pengeluaran,
            'pemasukan' => $pemasukan,
        ];

        return view('brilink.laporan.index', $laporan);
    }

    /**
     * Export laporan ke PDF
     */
    public function exportPdf(Request $request, $type = 'all')
    {
        // Log untuk debugging
        Log::info("Export PDF dipanggil untuk tipe: " . $type);
        Log::info("Parameter request: ", $request->all());

        $start_date = $request->start_date ?? Carbon::now()->subMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? Carbon::now()->format('Y-m-d');

        $start = Carbon::parse($start_date)->startOfDay();
        $end = Carbon::parse($end_date)->endOfDay();

        // Format tanggal untuk tampilan di PDF
        $periode = Carbon::parse($start_date)->format('d/m/Y') . ' - ' . Carbon::parse($end_date)->format('d/m/Y');

        // Format tanggal untuk nama file (tanpa karakter ilegal)
        $periodeFile = Carbon::parse($start_date)->format('d-m-Y') . ' sampai ' . Carbon::parse($end_date)->format('d-m-Y');

        // Data untuk laporan
        $setor_tunai = BriLinkSetorTunai::whereBetween('tgl_setor_tunai', [$start, $end])->get();
        $tarik_tunai = BriLinkTarikTunai::whereBetween('tgl_tarik_tunai', [$start, $end])->get();
        $bayar_tagihan = BriLinkBayarTagihanPln::whereBetween('tgl_transaksi', [$start, $end])->get();

        $total_setor = $setor_tunai->sum('nominal');
        $total_tarik = $tarik_tunai->sum('nominal');
        $total_bayar_pln = $bayar_tagihan->sum('nominal');
        $pengeluaran = PengeluaranBriLink::whereBetween('tgl_pengeluaran', [$start, $end])
            ->orderBy('tgl_pengeluaran')
            ->get();
        $pemasukan = Spending::where('badan_usaha_id', auth()->user()->badan_usaha->id)
            ->whereBetween('tanggal', [$start, $end])
            ->orderBy('tanggal')
            ->get();
        $data = [
            'setor_tunai' => $setor_tunai,
            'tarik_tunai' => $tarik_tunai,
            'bayar_tagihan' => $bayar_tagihan,
            'total_setor' => $total_setor,
            'total_tarik' => $total_tarik,
            'total_bayar_pln' => $total_bayar_pln,
            'periode' => $periode,
            'periodeFile' => $periodeFile,
            'tanggal_cetak' => Carbon::now()->format('d/m/Y H:i:s'),
            'pengeluaran' => $pengeluaran,
            'pemasukan' => $pemasukan,
            'type' => $type
        ];

        $title = "";
        $view = "";

        switch ($type) {
            case 'setor-tunai':
                $title = "Laporan Setor Tunai BRI Link";
                $view = 'brilink.laporan.pdf.setor_tunai_pdf';
                break;
            case 'tarik-tunai':
                $title = "Laporan Tarik Tunai BRI Link";
                $view = 'brilink.laporan.pdf.tarik_tunai_pdf';
                break;
            case 'bayar-tagihan':
                $title = "Laporan Bayar Tagihan PLN BRI Link";
                $view = 'brilink.laporan.pdf.bayar_tagihan_pdf';
                break;
            case 'pengeluaran':
                $title = "Laporan Pengeluaran BRI Link";
                $view = 'brilink.laporan.pdf.pengeluaran_pdf';
                break;
            case 'pemasukan':
                $title = "Laporan Pemasukan BRI Link";
                $view = 'brilink.laporan.pdf.pemasukan_pdf';
                break;
            default:
                $title = "Laporan BRI Link";
                $view = 'brilink.laporan.pdf.all_pdf';
                break;
        }

        try {
            $pdf = PDF::loadView($view, $data);
            $pdf->setPaper('a4', 'landscape');

            Log::info("PDF berhasil dibuat untuk view: " . $view);

            // Menggunakan format tanggal yang aman untuk nama file
            $fileName = $title . ' - ' . $periodeFile . '.pdf';

            Log::info("Nama file PDF: " . $fileName);

            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            Log::error("Gagal membuat PDF: " . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat PDF: ' . $e->getMessage()], 500);
        }
    }
}
