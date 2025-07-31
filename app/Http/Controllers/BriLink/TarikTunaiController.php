<?php

namespace App\Http\Controllers\BriLink;

use App\Http\Controllers\Controller;
use App\Models\BriLinkTarikTunai;
use App\Models\IncomeBadanUsaha;
use App\Models\PengeluaranBriLink;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarikTunaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tarik_tunai = BriLinkTarikTunai::latest()->get();
        return view('brilink.tarik_tunai.index', compact('tarik_tunai'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'kode_transaksi' => 'required|unique:bri_link_tarik_tunais',
            'nama' => 'required|string',
            'norek' => 'nullable|string',
            'norek_tujuan' => 'nullable|string',
            'nominal' => 'required|numeric',
            'tgl_tarik_tunai' => 'required|date'
        ]);

        try {
            DB::beginTransaction();
            BriLinkTarikTunai::create($request->all());
            $saldo = IncomeBadanUsaha::where('jenis_pemasukan', 'Pendapatan Usaha')
                ->where('badan_usaha_id', auth()->user()->badan_usaha->id)
                ->sum('nominal');
            if ($saldo < $request->nominal) {
                return redirect()->route('brilink.tarik-tunai.index')
                    ->with('error', 'Saldo tidak mencukupi untuk tarik tunai');
            }
            PengeluaranBriLink::create([
                'kode' => $request->kode_transaksi,
                'jenis_pengeluaran' => 'Lainnya',
                'jumlah' => 1,
                'harga' => $request->nominal,
                'tgl_pengeluaran' => $request->tgl_tarik_tunai,
                'tujuan' => 'Tarik Tunai',
            ]);
            DB::commit();
            return redirect()->route('brilink.tarik-tunai.index')
                ->with('success', 'Data tarik tunai berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('brilink.tarik-tunai.index')
                ->with('error', 'Gagal menambahkan data tarik tunai: ' . $th->getMessage());
        }
    }
    public function update(Request $request, BriLinkTarikTunai $tarik_tunai)
    {
        $request->validate([
            'nama' => 'required|string',
            'norek' => 'nullable|string',
            'norek_tujuan' => 'nullable|string',
            'nominal' => 'required|numeric',
            'tgl_tarik_tunai' => 'required|date'
        ]);

        try {
            DB::beginTransaction();
            PengeluaranBriLink::where('kode', $tarik_tunai->kode_transaksi)
                ->update([
                    'harga' => $request->nominal,
                    'tgl_pengeluaran' => $request->tgl_tarik_tunai,
                    'tujuan' => 'Tarik Tunai',
                ]);
            $tarik_tunai->update($request->all());
            DB::commit();
            return redirect()->route('brilink.tarik-tunai.index')
                ->with('success', 'Data tarik tunai berhasil diperbarui');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('brilink.tarik-tunai.index')
                ->with('error', 'Gagal memperbarui data tarik tunai: ' . $th->getMessage());
        }
    }
    public function destroy(BriLinkTarikTunai $tarik_tunai)
    {
        try {
            DB::beginTransaction();
            PengeluaranBriLink::where('kode', $tarik_tunai->kode_transaksi)->delete();
            $tarik_tunai->delete();
            DB::commit();
            return redirect()->route('brilink.tarik-tunai.index')
                ->with('success', 'Data tarik tunai berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('brilink.tarik-tunai.index')
                ->with('error', 'Gagal menghapus data tarik tunai: ' . $th->getMessage());
        }
    }
}
