<?php

namespace App\Http\Controllers\BriLink;

use App\Http\Controllers\Controller;
use App\Models\BriLinkSetorTunai;
use App\Models\Income;
use App\Models\IncomeBadanUsaha;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetorTunaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setor_tunai = BriLinkSetorTunai::latest()->get();
        return view('brilink.setor_tunai.index', compact('setor_tunai'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'kode_transaksi' => 'required|unique:bri_link_setor_tunais',
            'jumlah' => 'required|numeric',
            'nama' => 'required|string',
            'norek' => 'required|string',
            'nominal' => 'required|numeric',
            'tgl_setor_tunai' => 'required|date'
        ]);

        try {
            DB::beginTransaction();
            BriLinkSetorTunai::create($request->all());
            IncomeBadanUsaha::create([
                'badan_usaha_id' => auth()->user()->badan_usaha->id,
                'jenis_pemasukan' => 'Pendapatan Usaha',
                'nominal' => $request->nominal,
            ]);
            DB::commit();
            return redirect()->route('brilink.setor-tunai.index')
                ->with('success', 'Data setor tunai berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Gagal menambahkan data setor tunai: ' . $e->getMessage()])
                ->withInput();
        }
    }
    public function update(Request $request, BriLinkSetorTunai $setor_tunai)
    {
        $request->validate([
            'jumlah' => 'required|numeric',
            'nama' => 'required|string',
            'norek' => 'required|string',
            'nominal' => 'required|numeric',
            'tgl_setor_tunai' => 'required|date'
        ]);

        try {
            DB::beginTransaction();
            IncomeBadanUsaha::where('badan_usaha_id', auth()->user()->badan_usaha->id)
                ->where('jenis_pemasukan', 'Pendapatan Usaha')
                ->where('nominal', $setor_tunai->nominal)
                ->where('created_at', $setor_tunai->created_at)
                ->update([
                    'nominal' => $request->nominal,
                ]);
            $setor_tunai->update($request->all());
            DB::commit();
            return redirect()->route('brilink.setor-tunai.index')
                ->with('success', 'Data setor tunai berhasil diperbarui');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Gagal memperbarui data setor tunai: ' . $th->getMessage()])
                ->withInput();
        }
    }
    public function destroy(BriLinkSetorTunai $setor_tunai)
    {
        try {
            DB::beginTransaction();
            IncomeBadanUsaha::where('badan_usaha_id', auth()->user()->badan_usaha->id)
                ->where('jenis_pemasukan', 'Pendapatan Usaha')
                ->where('nominal', $setor_tunai->nominal)
                ->where('created_at', $setor_tunai->created_at)
                ->delete();
            $setor_tunai->delete();
            DB::commit();
            return redirect()->route('brilink.setor-tunai.index')
                ->with('success', 'Data setor tunai berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Gagal menghapus data setor tunai: ' . $th->getMessage()])
                ->withInput();
        }
    }
}
