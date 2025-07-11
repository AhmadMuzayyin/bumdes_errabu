<?php

namespace App\Http\Controllers\FotoCopy;

use App\Http\Controllers\Controller;
use App\Models\Income;
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
            DB::beginTransaction();
            PembayaranFotoCopy::create($request->all());
            Income::create([
                'badan_usaha_id' => auth()->user()->badan_usaha->id,
                'nominal' => $request->total_pembayaran,
                'tanggal' => $request->tgl_pembayaran,
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
            $pembayaran->update($request->all());
            Income::where('badan_usaha_id', auth()->user()->badan_usaha->id)
                ->where('tanggal', $pembayaran->tgl_pembayaran)
                ->update([
                    'nominal' => $request->total_pembayaran,
                    'tanggal' => $request->tgl_pembayaran,
                ]);
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
