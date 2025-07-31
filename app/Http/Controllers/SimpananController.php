<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\IncomeBadanUsaha;
use Illuminate\Http\Request;
use App\Models\Simpanan;
use App\Models\Nasabah;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SimpananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $simpanan = Simpanan::with('nasabah')->get();
        return view('simpan-pinjam.simpanan.index', compact('simpanan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nasabah = Nasabah::all();
        $kategori = ['pokok', 'wajib', 'sukarela'];
        return view('simpan-pinjam.simpanan.create', compact('nasabah', 'kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nasabah_id' => 'required|exists:nasabahs,id',
            'nominal' => 'required|numeric',
            'tgl_simpan' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Mulai transaksi database untuk memastikan konsistensi data
        DB::beginTransaction();
        try {
            $simpanan = Simpanan::create($request->all());
            IncomeBadanUsaha::create([
                'badan_usaha_id' => auth()->user()->badan_usaha->id,
                'nominal' => $request->nominal,
                'jenis_pemasukan' => 'Pendapatan Usaha',
            ]);
            DB::commit();
            return redirect()->route('simpanan.index')
                ->with('success', 'Data simpanan berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $simpanan = Simpanan::with('nasabah')->findOrFail($id);
        return view('simpan-pinjam.simpanan.show', compact('simpanan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $simpanan = Simpanan::findOrFail($id);
        $nasabah = Nasabah::all();
        return view('simpan-pinjam.simpanan.edit', compact('simpanan', 'nasabah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $simpanan = Simpanan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nasabah_id' => 'required|exists:nasabahs,id',
            'nominal' => 'required|numeric',
            'tgl_simpan' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Mulai transaksi database untuk memastikan konsistensi data
        DB::beginTransaction();
        try {
            IncomeBadanUsaha::where('badan_usaha_id', auth()->user()->badan_usaha->id)
                ->where('jenis_pemasukan', 'Pendapatan Usaha')
                ->where('created_at', $simpanan->created_at)
                ->where('nominal', $simpanan->OriginalNominal)
                ->update(['nominal' => $request->nominal]);
            $simpanan->update($request->all());
            DB::commit();
            return redirect()->route('simpanan.index')
                ->with('success', 'Data simpanan berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        $simpanan = Simpanan::findOrFail($id);
        DB::beginTransaction();
        try {
            IncomeBadanUsaha::where('badan_usaha_id', auth()->user()->badan_usaha->id)
                ->where('jenis_pemasukan', 'Pendapatan Usaha')
                ->where('created_at', $simpanan->created_at)
                ->where('nominal', $simpanan->OriginalNominal)
                ->delete();
            $simpanan->delete();
            DB::commit();
            return redirect()->route('simpanan.index')
                ->with('success', 'Data simpanan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
