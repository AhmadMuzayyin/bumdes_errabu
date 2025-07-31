<?php

namespace App\Http\Controllers\BriLink;

use App\Http\Controllers\Controller;
use App\Models\BriLinkBayarTagihanPln;
use App\Models\Income;
use App\Models\IncomeBadanUsaha;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BayarTagihanPlnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bayar_tagihan = BriLinkBayarTagihanPln::latest()->get();
        return view('brilink.bayar_tagihan_pln.index', compact('bayar_tagihan'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:bri_link_bayar_tagihan_plns',
            'nama' => 'required|string',
            'id_pelanggan' => 'required|string',
            'nominal' => 'required|numeric',
            'tgl_transaksi' => 'required|date'
        ]);

        try {
            DB::beginTransaction();
            BriLinkBayarTagihanPln::create($request->all());
            IncomeBadanUsaha::create([
                'badan_usaha_id' => auth()->user()->badan_usaha->id,
                'jenis_pemasukan' => 'Pendapatan Usaha',
                'nominal' => $request->nominal,
            ]);
            DB::commit();
            return redirect()->route('brilink.bayar-tagihan-pln.index')
                ->with('success', 'Data pembayaran tagihan PLN berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('brilink.bayar-tagihan-pln.index')
                ->with('error', 'Gagal menambahkan data pembayaran tagihan PLN: ' . $th->getMessage());
        }
    }
    public function update(Request $request, BriLinkBayarTagihanPln $bayar_tagihan_pln)
    {
        $request->validate([
            'nama' => 'required|string',
            'id_pelanggan' => 'required|string',
            'nominal' => 'required|numeric',
            'tgl_transaksi' => 'required|date'
        ]);

        try {
            DB::beginTransaction();
            IncomeBadanUsaha::where('badan_usaha_id', auth()->user()->badan_usaha->id)
                ->where('jenis_pemasukan', 'Pendapatan Usaha')
                ->where('created_at', $bayar_tagihan_pln->created_at)
                ->update(['nominal' => $request->nominal]);
            $bayar_tagihan_pln->update($request->all());
            DB::commit();
            return redirect()->route('brilink.bayar-tagihan-pln.index')
                ->with('success', 'Data pembayaran tagihan PLN berhasil diperbarui');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('brilink.bayar-tagihan-pln.index')
                ->with('error', 'Gagal memperbarui data pembayaran tagihan PLN: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BriLinkBayarTagihanPln $bayar_tagihan_pln)
    {
        try {
            DB::beginTransaction();
            IncomeBadanUsaha::where('badan_usaha_id', auth()->user()->badan_usaha->id)
                ->where('jenis_pemasukan', 'Pendapatan Usaha')
                ->where('created_at', $bayar_tagihan_pln->created_at)
                ->delete();
            $bayar_tagihan_pln->delete();
            DB::commit();
            return redirect()->route('brilink.bayar-tagihan-pln.index')
                ->with('success', 'Data pembayaran tagihan PLN berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('brilink.bayar-tagihan-pln.index')
                ->with('error', 'Gagal menghapus data pembayaran tagihan PLN: ' . $th->getMessage());
        }
    }
}
