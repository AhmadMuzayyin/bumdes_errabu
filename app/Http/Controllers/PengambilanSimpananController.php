<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengambilanSimpanan;
use App\Models\Nasabah;
use App\Models\Simpanan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PengambilanSimpananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengambilan = PengambilanSimpanan::with('nasabah')->latest()->get();
        return view('simpan-pinjam.pengambilan-simpanan.index', compact('pengambilan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nasabah = Nasabah::all();
        return view('simpan-pinjam.pengambilan-simpanan.create', compact('nasabah'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nasabah_id' => 'required|exists:nasabahs,id',
            'nominal' => 'required|numeric',
            'tgl_pengambilan' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Mulai transaksi database untuk memastikan konsistensi data
        DB::beginTransaction();
        try {
            // Periksa saldo simpanan nasabah
            $totalSimpanan = Simpanan::where('nasabah_id', $request->nasabah_id)
                ->sum('nominal');

            $totalPengambilan = PengambilanSimpanan::where('nasabah_id', $request->nasabah_id)
                ->sum('nominal');

            $saldo = $totalSimpanan - $totalPengambilan;

            if ($saldo < $request->nominal) {
                return redirect()->back()
                    ->with('error', 'Saldo simpanan tidak mencukupi untuk melakukan pengambilan')
                    ->withInput();
            }
            Simpanan::where('nasabah_id', $request->nasabah_id)
                ->decrement('nominal', $request->nominal);
            PengambilanSimpanan::create($request->all());
            DB::commit();
            return redirect()->route('pengambilan-simpanan.index')
                ->with('success', 'Data pengambilan simpanan berhasil ditambahkan');
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
        $pengambilan = PengambilanSimpanan::with('nasabah')->findOrFail($id);
        return view('simpan-pinjam.pengambilan-simpanan.show', compact('pengambilan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pengambilan = PengambilanSimpanan::findOrFail($id);
        $nasabah = Nasabah::all();
        return view('simpan-pinjam.pengambilan-simpanan.edit', compact('pengambilan', 'nasabah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nasabah_id' => 'required|exists:nasabahs,id',
            'nominal' => 'required|numeric',
            'tgl_pengambilan' => 'required|date',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Mulai transaksi database untuk memastikan konsistensi data
        DB::beginTransaction();
        try {
            // Jika nominal berubah, periksa saldo simpanan nasabah
            $pengambilan = PengambilanSimpanan::findOrFail($id);
            $simpanan = Simpanan::where('nasabah_id', $request->nasabah_id);
            if ($pengambilan->nominal != $request->nominal) {
                $totalSimpanan = $simpanan->sum('nominal');

                $totalPengambilan = PengambilanSimpanan::where('nasabah_id', $request->nasabah_id)
                    ->where('id', '!=', $id) // Kecualikan pengambilan yang sedang diedit
                    ->sum('nominal');

                $saldo = $totalSimpanan - $totalPengambilan;

                if ($saldo < $request->nominal) {
                    return redirect()->back()
                        ->with('error', 'Saldo simpanan tidak mencukupi untuk melakukan pengambilan')
                        ->withInput();
                }
            }
            if ($simpanan->sum('nominal') > $request->old_nominal) {
                $simpanan->update(['nominal' => $simpanan->sum('nominal') + (int)$request->old_nominal]);
                $simpanan->decrement('nominal', $request->nominal);
            }
            $pengambilan->update($request->all());
            DB::commit();
            return redirect()->route('pengambilan-simpanan.index')
                ->with('success', 'Data pengambilan simpanan berhasil diperbarui');
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
        $pengambilan = PengambilanSimpanan::findOrFail($id);

        // Mulai transaksi database untuk memastikan konsistensi data
        DB::beginTransaction();
        try {
            $pengambilan->delete();
            DB::commit();
            return redirect()->route('pengambilan-simpanan.index')
                ->with('success', 'Data pengambilan simpanan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
