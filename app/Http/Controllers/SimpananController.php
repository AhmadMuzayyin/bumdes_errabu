<?php

namespace App\Http\Controllers;

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
        $simpanan = Simpanan::with('nasabah')->latest()->get();
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
            Simpanan::create($request->all());
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $simpanan = Simpanan::findOrFail($id);

        // Mulai transaksi database untuk memastikan konsistensi data
        DB::beginTransaction();
        try {
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

    /**
     * Display simpanan by kategori
     */
    public function kategori($kategori)
    {
        $simpanan = Simpanan::with('nasabah')
            ->where('kategori', $kategori)
            ->latest()
            ->get();
        return view('simpan-pinjam.simpanan.kategori', compact('simpanan', 'kategori'));
    }
}
