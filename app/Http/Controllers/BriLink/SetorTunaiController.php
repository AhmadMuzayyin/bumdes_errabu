<?php

namespace App\Http\Controllers\BriLink;

use App\Http\Controllers\Controller;
use App\Models\BriLinkSetorTunai;
use App\Models\Income;
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

        try {
            DB::beginTransaction();
            BriLinkSetorTunai::create($request->all());
            Income::create([
                'badan_usaha_id' => auth()->user()->badan_usaha->id,
                'tanggal' => $request->tgl_setor_tunai,
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

        try {
            DB::beginTransaction();
            $setor_tunai->update($request->all());
            Income::where('badan_usaha_id', auth()->user()->badan_usaha->id)
                ->where('tanggal', $setor_tunai->tgl_setor_tunai)
                ->update([
                    'nominal' => $request->nominal,
                    'tanggal' => Carbon::parse($request->tgl_setor_tunai),
                ]);
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BriLinkSetorTunai $setor_tunai)
    {
        try {
            DB::beginTransaction();
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
