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

class LaporanSimpanPinjamController extends Controller
{
    /**
     * Tampilan halaman laporan
     */
    public function index()
    {
        return view('simpan-pinjam.laporan.index');
    }

    /**
     * Form laporan simpanan
     */
    public function formSimpanan()
    {
        return view('simpan-pinjam.laporan.form-simpanan');
    }

    /**
     * Form laporan pengambilan simpanan
     */
    public function formPengambilanSimpanan()
    {
        return view('simpan-pinjam.laporan.form-pengambilan-simpanan');
    }

    /**
     * Form laporan pinjaman
     */
    public function formPinjaman()
    {
        $kategoriList = SettingPinjaman::pluck('nama_kategori', 'id')->toArray();
        return view('simpan-pinjam.laporan.form-pinjaman', compact('kategoriList'));
    }

    /**
     * Form laporan pengembalian pinjaman
     */
    public function formPengembalianPinjaman()
    {
        $kategoriList = SettingPinjaman::pluck('nama_kategori', 'id')->toArray();
        return view('simpan-pinjam.laporan.form-pengembalian-pinjaman', compact('kategoriList'));
    }

    /**
     * Form laporan pengeluaran
     */
    public function formPengeluaran()
    {
        return view('simpan-pinjam.laporan.form-pengeluaran');
    }

    /**
     * Form rekap simpanan
     */
    public function formRekapSimpanan()
    {
        return view('simpan-pinjam.laporan.form-rekap-simpanan');
    }

    /**
     * Form rekap pinjaman
     */
    public function formRekapPinjaman()
    {
        return view('simpan-pinjam.laporan.form-rekap-pinjaman');
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
            ->whereBetween('tgl_simpan', [$tanggalAwal, $tanggalAkhir]);

        if ($kategori && $kategori !== 'semua') {
            $query->where('kategori', $kategori);
        }

        $simpanan = $query->get();
        $total = $simpanan->sum(function ($item) {
            return $item->getOriginalNominalAttribute();
        });

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
            ->whereBetween('tgl_pengambilan', [$tanggalAwal, $tanggalAkhir]);

        if ($kategori && $kategori !== 'semua') {
            $query->where('kategori', $kategori);
        }

        $pengambilan = $query->get();
        $total = $pengambilan->sum(function ($item) {
            return $item->getOriginalNominalAttribute();
        });

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
            'status' => 'nullable|in:Belum Lunas,Lunas,semua',
            'tipe_laporan' => 'required|in:web,pdf',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $kategori = $request->kategori;
        $status = $request->status;

        $query = Pinjamans::with(['nasabah', 'settingPinjaman'])
            ->whereBetween('tgl_pinjam', [$tanggalAwal, $tanggalAkhir]);

        if ($kategori && $kategori !== 'semua') {
            $query->where('setting_pinjaman_id', $kategori);
        }

        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }

        $pinjaman = $query->get();
        $total = $pinjaman->sum(function ($item) {
            return $item->getOriginalNominalAttribute();
        });
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

        $query = PengembalianPinjamans::with(['pinjaman.nasabah', 'pinjaman.settingPinjaman'])
            ->whereBetween('tgl_pengembalian_sementara', [$tanggalAwal, $tanggalAkhir]);

        if ($kategori && $kategori !== 'semua') {
            $query->whereHas('pinjaman', function ($q) use ($kategori) {
                $q->where('setting_pinjaman_id', $kategori);
            });
        }

        $pengembalian = $query->get();
        $total = $pengembalian->sum(function ($item) {
            return $item->getOriginalNominalCicilanAttribute();
        });

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

        $pengeluaran = PengeluaranSimpanPinjam::whereBetween('tgl_pengeluaran', [$tanggalAwal, $tanggalAkhir])->get();
        $total = $pengeluaran->sum(function ($item) {
            return $item->getOriginalJumlahAttribute();
        });

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
            // Karena tidak ada kolom kategori dalam tabel simpanan, kita hitung total saja
            $simpananPokok = 0; // Tidak ada kategori di migration
            $pengambilanPokok = 0; // Tidak ada kategori di migration
            $simpananWajib = 0; // Tidak ada kategori di migration
            $pengambilanWajib = 0; // Tidak ada kategori di migration

            // Hitung total simpanan
            $simpananSukarela = Simpanan::where('nasabah_id', $nasabah->id)
                ->get()
                ->sum(function ($item) {
                    return $item->getOriginalNominalAttribute();
                });

            // Hitung total pengambilan
            $pengambilanSukarela = PengambilanSimpanan::where('nasabah_id', $nasabah->id)
                ->get()
                ->sum(function ($item) {
                    return $item->getOriginalNominalAttribute();
                });

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
            $totalPinjaman = $pinjaman->sum(function ($item) {
                return $item->getOriginalNominalAttribute();
            });

            $pinjamanBelumLunas = Pinjamans::where('nasabah_id', $nasabah->id)
                ->where('status', 'Belum Lunas')
                ->get();

            $totalPinjamanBelumLunas = $pinjamanBelumLunas->sum(function ($item) {
                return $item->getOriginalNominalAttribute();
            });

            // Pengembalian pinjaman dikumpulkan berdasarkan pinjaman nasabah
            $pinjamanIds = $pinjaman->pluck('id')->toArray();
            $totalPengembalian = PengembalianPinjamans::whereIn('pinjamans_id', $pinjamanIds)
                ->get()
                ->sum(function ($item) {
                    return $item->getOriginalNominalCicilanAttribute();
                });

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
     * API untuk data simpanan
     */
    public function apiSimpanan(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $simpanan = Simpanan::with('nasabah')
            ->whereBetween('tgl_simpan', [$tanggalAwal, $tanggalAkhir])
            ->get();

        $total = $simpanan->sum(function ($item) {
            return $item->getOriginalNominalAttribute();
        });

        return response()->json([
            'status' => 'success',
            'data' => $simpanan,
            'total' => $total
        ]);
    }

    /**
     * API untuk data pengambilan simpanan
     */
    public function apiPengambilan(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $pengambilan = PengambilanSimpanan::with('nasabah')
            ->whereBetween('tgl_pengambilan', [$tanggalAwal, $tanggalAkhir])
            ->get();

        $total = $pengambilan->sum(function ($item) {
            return $item->getOriginalNominalAttribute();
        });

        return response()->json([
            'status' => 'success',
            'data' => $pengambilan,
            'total' => $total
        ]);
    }

    /**
     * API untuk data pinjaman
     */
    public function apiPinjaman(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $pinjaman = Pinjamans::with('nasabah')
            ->whereBetween('tgl_pinjam', [$tanggalAwal, $tanggalAkhir])
            ->get();

        $total = $pinjaman->sum(function ($item) {
            return $item->getOriginalNominalAttribute();
        });

        return response()->json([
            'status' => 'success',
            'data' => $pinjaman,
            'total' => $total
        ]);
    }

    /**
     * API untuk data pengembalian pinjaman
     */
    public function apiPengembalian(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $pengembalian = PengembalianPinjamans::with(['pinjaman.nasabah'])
            ->whereBetween('tgl_pengembalian_sementara', [$tanggalAwal, $tanggalAkhir])
            ->get();

        $total = $pengembalian->sum(function ($item) {
            return $item->getOriginalNominalCicilanAttribute();
        });

        return response()->json([
            'status' => 'success',
            'data' => $pengembalian,
            'total' => $total
        ]);
    }

    /**
     * API untuk data pengeluaran
     */
    public function apiPengeluaran(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $pengeluaran = PengeluaranSimpanPinjam::whereBetween('tgl_pengeluaran', [$tanggalAwal, $tanggalAkhir])
            ->get();

        $total = $pengeluaran->sum(function ($item) {
            return $item->getOriginalJumlahAttribute();
        });

        return response()->json([
            'status' => 'success',
            'data' => $pengeluaran,
            'total' => $total
        ]);
    }
}
