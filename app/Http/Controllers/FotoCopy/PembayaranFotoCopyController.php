<?php

namespace App\Http\Controllers\FotoCopy;

use App\Http\Controllers\Controller;
use App\Models\PembayaranFotoCopy;
use Illuminate\Http\Request;

class PembayaranFotoCopyController extends Controller
{
    /**
     * Menampilkan daftar pembayaran fotocopy
     */
    public function index()
    {
        $pembayaran = PembayaranFotoCopy::orderBy('tgl_pembayaran', 'desc')->get();
        return view('fotocopy.pembayaran.index', compact('pembayaran'));
    }

    /**
     * Menyimpan data pembayaran fotocopy baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'total_pembayaran' => 'required|numeric|min:0',
            'tgl_pembayaran' => 'required|date',
        ]);

        try {
            PembayaranFotoCopy::create($request->all());

            return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Pembayaran gagal ditambahkan: ' . $th->getMessage());
        }
    }

    /**
     * Mengupdate data pembayaran fotocopy
     */
    public function update(Request $request, PembayaranFotoCopy $pembayaran)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'total_pembayaran' => 'required|numeric|min:0',
            'tgl_pembayaran' => 'required|date',
        ]);

        try {
            $pembayaran->update($request->all());

            return redirect()->back()->with('success', 'Pembayaran berhasil diubah');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Pembayaran gagal diubah: ' . $th->getMessage());
        }
    }

    /**
     * Menghapus data pembayaran fotocopy
     */
    public function destroy(PembayaranFotoCopy $pembayaran)
    {
        try {
            $pembayaran->delete();

            return redirect()->back()->with('success', 'Pembayaran berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Pembayaran gagal dihapus: ' . $th->getMessage());
        }
    }
}
