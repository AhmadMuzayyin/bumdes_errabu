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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('brilink.tarik_tunai.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(BriLinkTarikTunai $tarik_tunai)
    {
        return view('brilink.tarik_tunai.show', compact('tarik_tunai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BriLinkTarikTunai $tarik_tunai)
    {
        return view('brilink.tarik_tunai.edit', compact('tarik_tunai'));
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BriLinkTarikTunai $tarik_tunai)
    {
        $tarik_tunai->delete();

        return redirect()->route('brilink.tarik-tunai.index')
            ->with('success', 'Data tarik tunai berhasil dihapus');
    }
}
