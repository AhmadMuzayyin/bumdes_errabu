<?php

namespace App\Http\Controllers\BriLink;

use App\Http\Controllers\Controller;
use App\Models\BriLinkTarikTunai;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        BriLinkTarikTunai::create($request->all());

        return redirect()->route('brilink.tarik-tunai.index')
            ->with('success', 'Data tarik tunai berhasil ditambahkan');
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

        $tarik_tunai->update($request->all());

        return redirect()->route('brilink.tarik-tunai.index')
            ->with('success', 'Data tarik tunai berhasil diperbarui');
    }
    public function destroy(BriLinkTarikTunai $tarik_tunai)
    {
        $tarik_tunai->delete();

        return redirect()->route('brilink.tarik-tunai.index')
            ->with('success', 'Data tarik tunai berhasil dihapus');
    }
}
