<?php

namespace App\Http\Controllers\FotoCopy;

use App\Http\Controllers\Controller;
use App\Models\HargaFotoCopy;
use App\Models\Income;
use App\Models\IncomeBadanUsaha;
use App\Models\PembayaranFotoCopy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranFotoCopyController extends Controller
{
    /**
     * Menampilkan daftar pembayaran fotocopy
     */
    public function index()
    {
        $pembayaran = PembayaranFotoCopy::orderBy('tgl_pembayaran', 'desc')->get();
        $kertas = HargaFotoCopy::all();
        return view('fotocopy.pembayaran.index', compact('pembayaran', 'kertas'));
    }

    /**
     * Menyimpan data pembayaran fotocopy baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_kertas' => 'required|exists:harga_foto_copies,id',
            'jumlah' => 'required|integer|min:1',
            'total_pembayaran' => 'required|numeric|min:0',
            'tgl_pembayaran' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            PembayaranFotoCopy::create([
                'harga_foto_copy_id' => $request->jenis_kertas,
                'jumlah' => $request->jumlah,
                'total_pembayaran' => $request->total_pembayaran,
                'tgl_pembayaran' => $request->tgl_pembayaran,
            ]);
            IncomeBadanUsaha::create([
                'badan_usaha_id' => auth()->user()->badan_usaha->id,
                'jenis_pemasukan' => 'Pendapatan Usaha',
                'nominal' => $request->total_pembayaran,
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
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
            DB::beginTransaction();
            IncomeBadanUsaha::where('badan_usaha_id', auth()->user()->badan_usaha->id)
                ->where('jenis_pemasukan', 'Pendapatan Usaha')
                ->where('nominal', $pembayaran->total_pembayaran)
                ->where('created_at', $pembayaran->created_at)
                ->update(['nominal' => $request->total_pembayaran]);
            $pembayaran->update($request->all());
            DB::commit();
            return redirect()->back()->with('success', 'Pembayaran berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollBack();
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
