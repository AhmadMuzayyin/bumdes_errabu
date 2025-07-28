<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use App\Models\PengeluaranSimpanPinjam;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PengeluaranSimpanPinjamController extends Controller
{
    public function index()
    {
        $pengeluaran = PengeluaranSimpanPinjam::orderBy('tgl_pengeluaran', 'desc')->get();
        return view('simpan-pinjam.pengeluaran.index', compact('pengeluaran'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'jenis_pengeluaran' => 'required|in:Belanja Rutin,Gaji Karyawan,Setor Penghasilan,Lainnya',
            'kode' => 'required|string|unique:pengeluaran_foto_copies,kode',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'tgl_pengeluaran' => 'required|date',
            'tujuan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            PengeluaranSimpanPinjam::create($request->all());
            if ($request->jenis_pengeluaran === 'Setor Penghasilan') {
                Income::create([
                    'sumber_dana' => 'Penghasilan Simpan Pinjam',
                    'nominal' => $request->jumlah * $request->harga,
                    'tanggal' => $request->tgl_pengeluaran,
                ]);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Pengeluaran berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Pengeluaran gagal ditambahkan: ' . $th->getMessage());
        }
    }
    public function update(Request $request, PengeluaranSimpanPinjam $pengeluaran)
    {
        $request->validate([
            'jenis_pengeluaran' => 'required|in:Belanja Rutin,Gaji Karyawan,Setor Pengahsilan,Lainnya',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'tgl_pengeluaran' => 'required|date',
            'tujuan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $pengeluaran->update($request->all());
            if ($request->jenis_pengeluaran === 'Setor Pengahsilan') {
                Income::where('sumber_dana', 'Penghasilan Simpan Pinjam')->where('nominal', $request->old_jumlah * $request->old_harga)->update(
                    [
                        'sumber_dana' => 'Penghasilan Simpan Pinjam',
                        'nominal' => $request->jumlah * $request->harga,
                        'tanggal' => $request->tgl_pengeluaran,
                    ]
                );
            }
            DB::commit();
            return redirect()->back()->with('success', 'Pengeluaran berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Pengeluaran gagal diubah: ' . $th->getMessage());
        }
    }
    public function destroy(PengeluaranSimpanPinjam $pengeluaran)
    {
        try {
            $pengeluaran->delete();
            if ($pengeluaran->jenis_pengeluaran === 'Setor Pengahsilan') {
                Income::where('sumber_dana', 'Penghasilan Simpan Pinjam')->where('tanggal', $pengeluaran->tgl_pengeluaran)->delete();
            }
            return redirect()->back()->with('success', 'Pengeluaran berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Pengeluaran gagal dihapus: ' . $th->getMessage());
        }
    }
}
