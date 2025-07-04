<?php

namespace App\Http\Controllers\BriLink;

use App\Http\Controllers\Controller;
use App\Models\BriLinkBayarTagihanPln;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('brilink.bayar_tagihan_pln.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:bri_link_bayar_tagihan_plns',
            'nama' => 'required|string',
            'id_pelanggan' => 'required|string',
            'nominal' => 'required|numeric',
            'tgl_transaksi' => 'required|date'
        ]);

        BriLinkBayarTagihanPln::create($request->all());

        return redirect()->route('brilink.bayar-tagihan-pln.index')
            ->with('success', 'Data pembayaran tagihan PLN berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(BriLinkBayarTagihanPln $bayar_tagihan_pln)
    {
        return view('brilink.bayar_tagihan_pln.show', compact('bayar_tagihan_pln'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BriLinkBayarTagihanPln $bayar_tagihan_pln)
    {
        return view('brilink.bayar_tagihan_pln.edit', compact('bayar_tagihan_pln'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BriLinkBayarTagihanPln $bayar_tagihan_pln)
    {
        $request->validate([
            'nama' => 'required|string',
            'id_pelanggan' => 'required|string',
            'nominal' => 'required|numeric',
            'tgl_transaksi' => 'required|date'
        ]);

        $bayar_tagihan_pln->update($request->all());

        return redirect()->route('brilink.bayar-tagihan-pln.index')
            ->with('success', 'Data pembayaran tagihan PLN berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BriLinkBayarTagihanPln $bayar_tagihan_pln)
    {
        $bayar_tagihan_pln->delete();

        return redirect()->route('brilink.bayar-tagihan-pln.index')
            ->with('success', 'Data pembayaran tagihan PLN berhasil dihapus');
    }
}
