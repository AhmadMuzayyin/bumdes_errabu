<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengeluaranSimpanPinjam;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PengeluaranSimpanPinjamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengeluaran = PengeluaranSimpanPinjam::latest()->get();
        return view('simpan-pinjam.pengeluaran.index', compact('pengeluaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate kode pengeluaran
        $lastPengeluaran = PengeluaranSimpanPinjam::latest('id')->first();
        $nextId = $lastPengeluaran ? $lastPengeluaran->id + 1 : 1;
        $kode = 'PNG-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        return view('simpan-pinjam.pengeluaran.create', compact('kode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|unique:pengeluaran_simpan_pinjams,kode',
            'jumlah' => 'required|numeric',
            'tgl_pengeluaran' => 'required|date',
            'tujuan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            PengeluaranSimpanPinjam::create($request->all());

            DB::commit();
            return redirect()->route('pengeluaran.index')
                ->with('success', 'Data pengeluaran berhasil ditambahkan');
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
        $pengeluaran = PengeluaranSimpanPinjam::findOrFail($id);
        return view('simpan-pinjam.pengeluaran.show', compact('pengeluaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pengeluaran = PengeluaranSimpanPinjam::findOrFail($id);
        return view('simpan-pinjam.pengeluaran.edit', compact('pengeluaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pengeluaran = PengeluaranSimpanPinjam::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|unique:pengeluaran_simpan_pinjams,kode,' . $id,
            'jumlah' => 'required|numeric',
            'tgl_pengeluaran' => 'required|date',
            'tujuan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Update data pengeluaran
            $pengeluaran->update([
                'kode' => $request->kode,
                'tgl_pengeluaran' => $request->tgl_pengeluaran,
                'tujuan' => $request->tujuan,
                'jumlah' => $request->jumlah,
            ]);

            DB::commit();
            return redirect()->route('pengeluaran.index')
                ->with('success', 'Data pengeluaran berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pengeluaran = PengeluaranSimpanPinjam::findOrFail($id);

        DB::beginTransaction();
        try {
            $pengeluaran->delete();

            DB::commit();
            return redirect()->route('pengeluaran.index')
                ->with('success', 'Data pengeluaran berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
