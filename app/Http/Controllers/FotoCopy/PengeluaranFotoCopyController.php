<?php

namespace App\Http\Controllers\FotoCopy;

use App\Http\Controllers\Controller;
use App\Models\PengeluaranFotoCopy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengeluaranFotoCopyController extends Controller
{
    /**
     * Menampilkan daftar pengeluaran fotocopy
     */
    public function index()
    {
        $pengeluaran = PengeluaranFotoCopy::orderBy('tgl_pengeluaran', 'desc')->get();
        return view('fotocopy.pengeluaran.index', compact('pengeluaran'));
    }

    /**
     * Menyimpan data pengeluaran fotocopy baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|unique:pengeluaran_foto_copies,kode',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'tgl_pengeluaran' => 'required|date',
            'tujuan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            PengeluaranFotoCopy::create($request->all());
            DB::commit();
            return redirect()->back()->with('success', 'Pengeluaran berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Pengeluaran gagal ditambahkan: ' . $th->getMessage());
        }
    }

    /**
     * Mengupdate data pengeluaran fotocopy
     */
    public function update(Request $request, PengeluaranFotoCopy $pengeluaran)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'tgl_pengeluaran' => 'required|date',
            'tujuan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $pengeluaran->update($request->all());
            DB::commit();
            return redirect()->back()->with('success', 'Pengeluaran berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Pengeluaran gagal diubah: ' . $th->getMessage());
        }
    }

    /**
     * Menghapus data pengeluaran fotocopy
     */
    public function destroy(PengeluaranFotoCopy $pengeluaran)
    {
        try {
            $pengeluaran->delete();

            return redirect()->back()->with('success', 'Pengeluaran berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Pengeluaran gagal dihapus: ' . $th->getMessage());
        }
    }
}
