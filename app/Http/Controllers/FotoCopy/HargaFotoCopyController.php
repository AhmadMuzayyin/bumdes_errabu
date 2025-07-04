<?php

namespace App\Http\Controllers\FotoCopy;

use App\Http\Controllers\Controller;
use App\Models\HargaFotoCopy;
use Illuminate\Http\Request;

class HargaFotoCopyController extends Controller
{
    /**
     * Menampilkan daftar harga fotocopy
     */
    public function index()
    {
        $harga_list = HargaFotoCopy::orderBy('nama', 'asc')->get();
        return view('fotocopy.harga.index', compact('harga_list'));
    }

    /**
     * Menyimpan data harga fotocopy baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'harga' => 'required|numeric|min:0',
        ]);

        HargaFotoCopy::create($request->all());

        return redirect()->back()->with('success', 'Harga berhasil ditambahkan');
    }

    /**
     * Mengupdate data harga fotocopy
     */
    public function update(Request $request, HargaFotoCopy $harga)
    {
        $request->validate([
            'nama' => 'required|string',
            'harga' => 'required|numeric|min:0',
        ]);

        $harga->update($request->all());

        return redirect()->back()->with('success', 'Harga berhasil diperbarui');
    }

    /**
     * Menghapus data harga fotocopy
     */
    public function destroy(HargaFotoCopy $harga)
    {
        $harga->delete();

        return redirect()->back()->with('success', 'Harga berhasil dihapus');
    }
}
