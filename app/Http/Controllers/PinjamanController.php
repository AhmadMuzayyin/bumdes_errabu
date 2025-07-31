<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pinjamans;
use App\Models\Nasabah;
use App\Models\PengeluaranSimpanPinjam;
use App\Models\SettingPinjaman;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PinjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pinjaman = Pinjamans::with('nasabah')->latest()->get();
        $bunga = SettingPinjaman::first()->bunga ?? 0;
        $original_bunga = SettingPinjaman::first()->original_bunga ?? 0;
        return view('simpan-pinjam.pinjaman.index', compact('pinjaman', 'bunga', 'original_bunga'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nasabah = Nasabah::all();
        $bunga = SettingPinjaman::first()->bunga ?? 0;
        $original_bunga = SettingPinjaman::first()->original_bunga ?? 0;
        return view('simpan-pinjam.pinjaman.create', compact('nasabah', 'bunga', 'original_bunga'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nasabah_id' => 'required|exists:nasabahs,id',
            'nominal' => 'required|numeric',
            'tgl_pinjam' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Mulai transaksi database untuk memastikan konsistensi data
        DB::beginTransaction();
        try {
            // Hitung bunga pinjaman (persentase dari nominal pokok)
            $settingPinjaman = SettingPinjaman::first();
            $persentaseBunga = $settingPinjaman->original_bunga;
            $nominalPokok = $request->nominal;
            $nominalBunga = ($nominalPokok * $persentaseBunga / 100);
            $totalPinjaman = $nominalPokok + $nominalBunga;

            // Buat data pinjaman
            $nasabah = Nasabah::findOrFail($request->nasabah_id);
            $pinjaman = new Pinjamans();
            $pinjaman->nasabah_id = $nasabah->id;
            $pinjaman->nominal = $request->nominal;
            $pinjaman->tgl_pinjam = $request->tgl_pinjam;
            $pinjaman->nominal_pengembalian = $totalPinjaman;
            $pinjaman->status = 'Belum Lunas';
            $pinjaman->save();
            PengeluaranSimpanPinjam::create([
                'kode' => 'PINJ-' . $pinjaman->created_at->format('YmdHis'),
                'jenis_pengeluaran' => 'Lainnya',
                'jumlah' => 1,
                'harga' => $request->nominal,
                'tgl_pengeluaran' => now(),
                'tujuan' => 'Pinjaman untuk Nasabah: ' . $nasabah->nama,
            ]);

            DB::commit();
            return redirect()->route('pinjaman.index')
                ->with('success', 'Data pinjaman berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pinjaman = Pinjamans::with(['nasabah', 'pengembalianPinjaman'])->findOrFail($id);
        $bunga = SettingPinjaman::first()->bunga ?? 0;
        $original_bunga = SettingPinjaman::first()->original_bunga ?? 0;
        return view('simpan-pinjam.pinjaman.show', compact('pinjaman', 'bunga', 'original_bunga'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pinjaman = Pinjamans::findOrFail($id);
        $nasabah = Nasabah::all();
        return view('simpan-pinjam.pinjaman.edit', compact('pinjaman', 'nasabah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nasabah_id' => 'required|exists:nasabahs,id',
            'nominal' => 'required|numeric',
            'tgl_pinjam' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        DB::beginTransaction();
        try {
            // Hitung bunga pinjaman (persentase dari nominal pokok)
            $settingPinjaman = SettingPinjaman::first();
            $persentaseBunga = $settingPinjaman->original_bunga;
            $nominalPokok = $request->nominal;
            $nominalBunga = ($nominalPokok * $persentaseBunga / 100);
            $totalPinjaman = $nominalPokok + $nominalBunga;

            // Update data pinjaman
            $pinjaman = Pinjamans::findOrFail($id);
            if ($pinjaman->pengembalianPinjaman()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Pinjaman sudah memiliki catatan pengembalian, tidak dapat diubah.');
            }
            $nasabah = Nasabah::findOrFail($request->nasabah_id);
            $pinjaman->nasabah_id = $nasabah->id;
            $pinjaman->nominal = $request->nominal;
            $pinjaman->tgl_pinjam = $request->tgl_pinjam;
            $pinjaman->nominal_pengembalian = $totalPinjaman;
            $pinjaman->save();
            PengeluaranSimpanPinjam::where('kode', 'PINJ-' . $pinjaman->created_at->format('YmdHis'))
                ->update([
                    'kode' => 'PINJ-' . $pinjaman->created_at->format('YmdHis'),
                    'jenis_pengeluaran' => 'Lainnya',
                    'jumlah' => 1,
                    'harga' => $request->nominal,
                    'tgl_pengeluaran' => now(),
                    'tujuan' => 'Pinjaman untuk Nasabah: ' . $nasabah->nama,
                ]);

            DB::commit();
            return redirect()->route('pinjaman.index')
                ->with('success', 'Data pinjaman berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pinjaman = Pinjamans::findOrFail($id);
        if ($pinjaman->pengembalianPinjaman()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Pinjaman sudah memiliki catatan pengembalian, tidak dapat dihapus.');
        }
        try {
            DB::beginTransaction();
            PengeluaranSimpanPinjam::where('kode', 'PINJ-' . $pinjaman->created_at->format('YmdHis'))
                ->delete();
            $pinjaman->delete();
            DB::commit();
            return redirect()->route('pinjaman.index')
                ->with('success', 'Data pinjaman berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
