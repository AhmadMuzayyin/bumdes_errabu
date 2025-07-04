<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;
use App\Models\Simpanan;
use App\Models\PengambilanSimpanan;
use App\Models\Pinjamans;
use App\Models\PengembalianPinjamans;
use App\Models\PengeluaranSimpanPinjam;
use App\Models\SettingPinjaman;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class LaporanSimpanPinjamController extends Controller
{
    /**
     * Tampilan halaman laporan
     */
    public function index(Request $request)
    {
        // Ambil semua data nasabah untuk dropdown filter
        $nasabah = Nasabah::orderBy('nama', 'asc')->get();
        $nasabahId = $request->query('nasabah_id', '');

        // Variabel default untuk view
        $tanggalAwal = $request->query('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggalAkhir = $request->query('tanggal_akhir', Carbon::now()->format('Y-m-d'));

        // Ambil data awal jika ada filter tanggal atau nasabah
        $transaksiSimpanan = [];
        $transaksiPinjaman = [];
        $pengeluaran = [];

        if ($request->has('tanggal_awal') || $request->has('tanggal_akhir')) {
            $transaksiSimpanan = $this->getTransaksiSimpanan($tanggalAwal, $tanggalAkhir, $nasabahId);
            $transaksiPinjaman = $this->getTransaksiPinjaman($tanggalAwal, $tanggalAkhir, $nasabahId);
            $pengeluaran = $this->getPengeluaran($tanggalAwal, $tanggalAkhir);
        }

        return view('simpan-pinjam.laporan.index', compact(
            'nasabah',
            'nasabahId',
            'tanggalAwal',
            'tanggalAkhir',
            'transaksiSimpanan',
            'transaksiPinjaman',
            'pengeluaran'
        ));
    }

    /**
     * Laporan Simpanan
     */
    public function laporanSimpanan(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'kategori' => 'nullable|in:pokok,wajib,sukarela,semua',
            'tipe_laporan' => 'required|in:web,pdf',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $kategori = $request->kategori;

        $query = Simpanan::with('nasabah')
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);

        if ($kategori && $kategori !== 'semua') {
            $query->where('kategori', $kategori);
        }

        $simpanan = $query->get();
        $total = $simpanan->sum('nominal');

        if ($request->tipe_laporan === 'web') {
            return view('simpan-pinjam.laporan.simpanan', compact(
                'simpanan',
                'total',
                'tanggalAwal',
                'tanggalAkhir',
                'kategori'
            ));
        } else {
            $pdf = PDF::loadView('simpan-pinjam.laporan.simpanan-pdf', compact(
                'simpanan',
                'total',
                'tanggalAwal',
                'tanggalAkhir',
                'kategori'
            ));
            return $pdf->download('laporan-simpanan-' . date('Y-m-d') . '.pdf');
        }
    }

    /**
     * Laporan Pengambilan Simpanan
     */
    public function laporanPengambilanSimpanan(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'kategori' => 'nullable|in:pokok,wajib,sukarela,semua',
            'tipe_laporan' => 'required|in:web,pdf',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $kategori = $request->kategori;

        $query = PengambilanSimpanan::with('nasabah')
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);

        if ($kategori && $kategori !== 'semua') {
            $query->where('kategori', $kategori);
        }

        $pengambilan = $query->get();
        $total = $pengambilan->sum('nominal');

        if ($request->tipe_laporan === 'web') {
            return view('simpan-pinjam.laporan.pengambilan-simpanan', compact(
                'pengambilan',
                'total',
                'tanggalAwal',
                'tanggalAkhir',
                'kategori'
            ));
        } else {
            $pdf = PDF::loadView('simpan-pinjam.laporan.pengambilan-simpanan-pdf', compact(
                'pengambilan',
                'total',
                'tanggalAwal',
                'tanggalAkhir',
                'kategori'
            ));
            return $pdf->download('laporan-pengambilan-simpanan-' . date('Y-m-d') . '.pdf');
        }
    }

    /**
     * Laporan Pinjaman
     */
    public function laporanPinjaman(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'kategori' => 'nullable',
            'status' => 'nullable|in:belum-lunas,lunas,semua',
            'tipe_laporan' => 'required|in:web,pdf',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $kategori = $request->kategori;
        $status = $request->status;

        $query = Pinjamans::with(['nasabah', 'settingPinjaman'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);

        if ($kategori && $kategori !== 'semua') {
            $query->where('setting_pinjamans_id', $kategori);
        }

        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }

        $pinjaman = $query->get();
        $total = $pinjaman->sum('nominal');
        $totalBunga = $pinjaman->sum('bunga_nominal');
        $totalPinjaman = $pinjaman->sum('total');

        $kategoriList = SettingPinjaman::pluck('nama_kategori', 'id')->toArray();

        if ($request->tipe_laporan === 'web') {
            return view('simpan-pinjam.laporan.pinjaman', compact(
                'pinjaman',
                'total',
                'totalBunga',
                'totalPinjaman',
                'tanggalAwal',
                'tanggalAkhir',
                'kategori',
                'status',
                'kategoriList'
            ));
        } else {
            $pdf = PDF::loadView('simpan-pinjam.laporan.pinjaman-pdf', compact(
                'pinjaman',
                'total',
                'totalBunga',
                'totalPinjaman',
                'tanggalAwal',
                'tanggalAkhir',
                'kategori',
                'status',
                'kategoriList'
            ));
            return $pdf->download('laporan-pinjaman-' . date('Y-m-d') . '.pdf');
        }
    }

    /**
     * Laporan Pengembalian Pinjaman
     */
    public function laporanPengembalianPinjaman(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'kategori' => 'nullable',
            'tipe_laporan' => 'required|in:web,pdf',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $kategori = $request->kategori;

        $query = PengembalianPinjamans::with(['nasabah', 'pinjaman.settingPinjaman'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);

        if ($kategori && $kategori !== 'semua') {
            $query->whereHas('pinjaman', function ($q) use ($kategori) {
                $q->where('setting_pinjamans_id', $kategori);
            });
        }

        $pengembalian = $query->get();
        $total = $pengembalian->sum('nominal');

        $kategoriList = SettingPinjaman::pluck('nama_kategori', 'id')->toArray();

        if ($request->tipe_laporan === 'web') {
            return view('simpan-pinjam.laporan.pengembalian-pinjaman', compact(
                'pengembalian',
                'total',
                'tanggalAwal',
                'tanggalAkhir',
                'kategori',
                'kategoriList'
            ));
        } else {
            $pdf = PDF::loadView('simpan-pinjam.laporan.pengembalian-pinjaman-pdf', compact(
                'pengembalian',
                'total',
                'tanggalAwal',
                'tanggalAkhir',
                'kategori',
                'kategoriList'
            ));
            return $pdf->download('laporan-pengembalian-pinjaman-' . date('Y-m-d') . '.pdf');
        }
    }

    /**
     * Laporan Pengeluaran
     */
    public function laporanPengeluaran(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'tipe_laporan' => 'required|in:web,pdf',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $pengeluaran = PengeluaranSimpanPinjam::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])->get();
        $total = $pengeluaran->sum('nominal');

        if ($request->tipe_laporan === 'web') {
            return view('simpan-pinjam.laporan.pengeluaran', compact(
                'pengeluaran',
                'total',
                'tanggalAwal',
                'tanggalAkhir'
            ));
        } else {
            $pdf = PDF::loadView('simpan-pinjam.laporan.pengeluaran-pdf', compact(
                'pengeluaran',
                'total',
                'tanggalAwal',
                'tanggalAkhir'
            ));
            return $pdf->download('laporan-pengeluaran-' . date('Y-m-d') . '.pdf');
        }
    }

    /**
     * Rekap Simpanan Nasabah
     */
    public function rekapSimpananNasabah(Request $request)
    {
        $request->validate([
            'tipe_laporan' => 'required|in:web,pdf',
        ]);

        $nasabahs = Nasabah::all();
        $rekapData = [];

        foreach ($nasabahs as $nasabah) {
            $simpananPokok = Simpanan::where('nasabah_id', $nasabah->id)
                ->where('kategori', 'pokok')
                ->sum('nominal');

            $pengambilanPokok = PengambilanSimpanan::where('nasabah_id', $nasabah->id)
                ->where('kategori', 'pokok')
                ->sum('nominal');

            $simpananWajib = Simpanan::where('nasabah_id', $nasabah->id)
                ->where('kategori', 'wajib')
                ->sum('nominal');

            $pengambilanWajib = PengambilanSimpanan::where('nasabah_id', $nasabah->id)
                ->where('kategori', 'wajib')
                ->sum('nominal');

            $simpananSukarela = Simpanan::where('nasabah_id', $nasabah->id)
                ->where('kategori', 'sukarela')
                ->sum('nominal');

            $pengambilanSukarela = PengambilanSimpanan::where('nasabah_id', $nasabah->id)
                ->where('kategori', 'sukarela')
                ->sum('nominal');

            $totalSimpanan = $simpananPokok + $simpananWajib + $simpananSukarela;
            $totalPengambilan = $pengambilanPokok + $pengambilanWajib + $pengambilanSukarela;
            $saldoSimpanan = $totalSimpanan - $totalPengambilan;

            $rekapData[] = [
                'nasabah' => $nasabah,
                'simpanan_pokok' => $simpananPokok,
                'pengambilan_pokok' => $pengambilanPokok,
                'saldo_pokok' => $simpananPokok - $pengambilanPokok,
                'simpanan_wajib' => $simpananWajib,
                'pengambilan_wajib' => $pengambilanWajib,
                'saldo_wajib' => $simpananWajib - $pengambilanWajib,
                'simpanan_sukarela' => $simpananSukarela,
                'pengambilan_sukarela' => $pengambilanSukarela,
                'saldo_sukarela' => $simpananSukarela - $pengambilanSukarela,
                'total_simpanan' => $totalSimpanan,
                'total_pengambilan' => $totalPengambilan,
                'saldo_simpanan' => $saldoSimpanan,
            ];
        }

        if ($request->tipe_laporan === 'web') {
            return view('simpan-pinjam.laporan.rekap-simpanan', compact('rekapData'));
        } else {
            $pdf = PDF::loadView('simpan-pinjam.laporan.rekap-simpanan-pdf', compact('rekapData'));
            return $pdf->download('rekap-simpanan-nasabah-' . date('Y-m-d') . '.pdf');
        }
    }

    /**
     * Rekap Pinjaman Nasabah
     */
    public function rekapPinjamanNasabah(Request $request)
    {
        $request->validate([
            'tipe_laporan' => 'required|in:web,pdf',
        ]);

        $nasabahs = Nasabah::all();
        $rekapData = [];

        foreach ($nasabahs as $nasabah) {
            $pinjaman = Pinjamans::where('nasabah_id', $nasabah->id)->get();
            $totalPinjaman = $pinjaman->sum('total');

            $pinjamanBelumLunas = Pinjamans::where('nasabah_id', $nasabah->id)
                ->where('status', 'belum-lunas')
                ->get();

            $totalPinjamanBelumLunas = $pinjamanBelumLunas->sum('total');

            $totalPengembalian = PengembalianPinjamans::where('nasabah_id', $nasabah->id)
                ->sum('nominal');

            $sisaPinjaman = $totalPinjaman - $totalPengembalian;

            $rekapData[] = [
                'nasabah' => $nasabah,
                'jumlah_pinjaman' => $pinjaman->count(),
                'total_pinjaman' => $totalPinjaman,
                'jumlah_pinjaman_belum_lunas' => $pinjamanBelumLunas->count(),
                'total_pinjaman_belum_lunas' => $totalPinjamanBelumLunas,
                'total_pengembalian' => $totalPengembalian,
                'sisa_pinjaman' => $sisaPinjaman,
            ];
        }

        if ($request->tipe_laporan === 'web') {
            return view('simpan-pinjam.laporan.rekap-pinjaman', compact('rekapData'));
        } else {
            $pdf = PDF::loadView('simpan-pinjam.laporan.rekap-pinjaman-pdf', compact('rekapData'));
            return $pdf->download('rekap-pinjaman-nasabah-' . date('Y-m-d') . '.pdf');
        }
    }

    /**
     * Update laporan berdasarkan filter
     */
    public function updateLaporan(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'nasabah_id' => 'nullable|exists:nasabahs,id',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $nasabahId = $request->nasabah_id;

        // Data transaksi simpanan
        $transaksiSimpanan = $this->getTransaksiSimpanan($tanggalAwal, $tanggalAkhir, $nasabahId);

        // Data transaksi pinjaman
        $transaksiPinjaman = $this->getTransaksiPinjaman($tanggalAwal, $tanggalAkhir, $nasabahId);

        // Data pengeluaran
        $pengeluaran = $this->getPengeluaran($tanggalAwal, $tanggalAkhir);

        return response()->json([
            'success' => true,
            'transaksiSimpanan' => $transaksiSimpanan,
            'transaksiPinjaman' => $transaksiPinjaman,
            'pengeluaran' => $pengeluaran
        ]);
    }

    /**
     * Ambil data transaksi simpanan
     */
    private function getTransaksiSimpanan($tanggalAwal, $tanggalAkhir, $nasabahId = null)
    {
        // Query untuk simpanan
        $simpananQuery = Simpanan::with('nasabah')
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('tanggal', 'asc');

        // Query untuk pengambilan simpanan
        $pengambilanQuery = PengambilanSimpanan::with('nasabah')
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('tanggal', 'asc');

        // Filter berdasarkan nasabah jika ada
        if ($nasabahId) {
            $simpananQuery->where('nasabah_id', $nasabahId);
            $pengambilanQuery->where('nasabah_id', $nasabahId);
        }

        $simpanan = $simpananQuery->get();
        $pengambilan = $pengambilanQuery->get();

        // Gabungkan data simpanan dan pengambilan
        $transaksi = [];
        $saldoBerjalan = 0;

        foreach ($simpanan as $s) {
            $transaksi[] = [
                'tanggal' => Carbon::parse($s->tanggal)->format('d/m/Y'),
                'nasabah_id' => $s->nasabah_id,
                'nasabah_nama' => $s->nasabah->nama ?? 'N/A',
                'jenis' => 'Debit', // Simpanan masuk
                'nominal' => $s->nominal,
                'saldo_berjalan' => $saldoBerjalan + $s->nominal
            ];
            $saldoBerjalan += $s->nominal;
        }

        foreach ($pengambilan as $p) {
            $transaksi[] = [
                'tanggal' => Carbon::parse($p->tanggal)->format('d/m/Y'),
                'nasabah_id' => $p->nasabah_id,
                'nasabah_nama' => $p->nasabah->nama ?? 'N/A',
                'jenis' => 'Kredit', // Pengambilan keluar
                'nominal' => $p->nominal,
                'saldo_berjalan' => $saldoBerjalan - $p->nominal
            ];
            $saldoBerjalan -= $p->nominal;
        }

        // Urutkan berdasarkan tanggal
        usort($transaksi, function ($a, $b) {
            return strtotime($a['tanggal']) - strtotime($b['tanggal']);
        });

        // Hitung ulang saldo berjalan setelah diurutkan
        $saldoBerjalan = 0;
        foreach ($transaksi as &$t) {
            if ($t['jenis'] == 'Debit') {
                $saldoBerjalan += $t['nominal'];
            } else {
                $saldoBerjalan -= $t['nominal'];
            }
            $t['saldo_berjalan'] = $saldoBerjalan;
        }

        return [
            'transaksi' => $transaksi,
            'saldo_akhir' => $saldoBerjalan
        ];
    }

    /**
     * Ambil data transaksi pinjaman
     */
    private function getTransaksiPinjaman($tanggalAwal, $tanggalAkhir, $nasabahId = null)
    {
        // Query untuk pinjaman yang belum lunas
        $pinjamanQuery = Pinjamans::with('nasabah')
            ->where('status', 'belum-lunas')
            ->orderBy('tanggal', 'asc');

        // Filter berdasarkan nasabah jika ada
        if ($nasabahId) {
            $pinjamanQuery->where('nasabah_id', $nasabahId);
        }

        $pinjaman = $pinjamanQuery->get();
        $transaksi = [];
        $totalPinjaman = 0;

        foreach ($pinjaman as $p) {
            // Hitung total pengembalian untuk pinjaman ini
            $totalPengembalian = PengembalianPinjamans::where('pinjaman_id', $p->id)->sum('nominal');
            $sisaPinjaman = $p->total - $totalPengembalian;

            $transaksi[] = [
                'id' => $p->id,
                'tanggal' => Carbon::parse($p->tanggal)->format('d/m/Y'),
                'nasabah_id' => $p->nasabah_id,
                'nasabah_nama' => $p->nasabah->nama ?? 'N/A',
                'total_pinjaman' => $p->total,
                'total_pengembalian' => $totalPengembalian,
                'nominal' => $sisaPinjaman
            ];

            $totalPinjaman += $sisaPinjaman;
        }

        return [
            'pinjaman' => $transaksi,
            'total_pinjaman' => $totalPinjaman
        ];
    }

    /**
     * Ambil data pengeluaran
     */
    private function getPengeluaran($tanggalAwal, $tanggalAkhir)
    {
        $pengeluaranQuery = PengeluaranSimpanPinjam::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('tanggal', 'asc');

        $pengeluaran = $pengeluaranQuery->get();
        $data = [];
        $totalPengeluaran = 0;

        foreach ($pengeluaran as $p) {
            $data[] = [
                'id' => $p->id,
                'tanggal' => Carbon::parse($p->tanggal)->format('d/m/Y'),
                'kode' => $p->kode,
                'tujuan' => $p->tujuan,
                'nominal' => $p->nominal
            ];

            $totalPengeluaran += $p->nominal;
        }

        return [
            'pengeluaran' => $data,
            'total_pengeluaran' => $totalPengeluaran
        ];
    }
}
