<?php

namespace App\Http\Controllers\BriLink;

use App\Http\Controllers\Controller;
use App\Models\BriLinkSetorTunai;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('brilink.setor_tunai.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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

        BriLinkSetorTunai::create($request->all());

        return redirect()->route('brilink.setor-tunai.index')
            ->with('success', 'Data setor tunai berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(BriLinkSetorTunai $setor_tunai)
    {
        return view('brilink.setor_tunai.show', compact('setor_tunai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BriLinkSetorTunai $setor_tunai)
    {
        return view('brilink.setor_tunai.edit', compact('setor_tunai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BriLinkSetorTunai $setor_tunai)
    {
        $request->validate([
            'jumlah' => 'required|numeric',
            'nama' => 'required|string',
            'norek' => 'required|string',
            'nominal' => 'required|numeric',
            'tgl_setor_tunai' => 'required|date'
        ]);

        $setor_tunai->update($request->all());

        return redirect()->route('brilink.setor-tunai.index')
            ->with('success', 'Data setor tunai berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BriLinkSetorTunai $setor_tunai)
    {
        $setor_tunai->delete();

        return redirect()->route('brilink.setor-tunai.index')
            ->with('success', 'Data setor tunai berhasil dihapus');
    }
}
