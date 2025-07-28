<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengembalianPinjaman;
use App\Models\Pinjamans;
use App\Models\Nasabah;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengembalianPinjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengembalian = PengembalianPinjaman::with(['pinjaman', 'pinjaman.nasabah'])->latest()->get();
        return view('simpan-pinjam.pengembalian-pinjaman.index', compact('pengembalian'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nasabah = Nasabah::all();
        return view('simpan-pinjam.pengembalian-pinjaman.create', compact('nasabah'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pinjamans_id' => 'required|exists:pinjamans,id',
            'nominal_cicilan' => 'required|numeric',
            'tgl_pengembalian_sementara' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $pinjaman = Pinjamans::findOrFail($request->pinjamans_id);

            // Buat record pengembalian pinjaman
            $pengembalian = PengembalianPinjaman::create([
                'pinjamans_id' => $request->pinjamans_id,
                'nominal_cicilan' => $request->nominal_cicilan,
                'tgl_pengembalian_sementara' => $request->tgl_pengembalian_sementara,
                'status' => 'Belum Lunas',
            ]);

            // Cek total pengembalian
            $totalPengembalian = PengembalianPinjaman::where('pinjamans_id', $request->pinjamans_id)->sum('nominal_cicilan');

            // Update status pinjaman jika sudah lunas
            if ($totalPengembalian >= $pinjaman->original_nominal) {
                $pinjaman->update([
                    'status' => 'Lunas',
                    'nominal_pengembalian' => $totalPengembalian
                ]);

                // Update status semua cicilan menjadi lunas
                PengembalianPinjaman::where('pinjamans_id', $request->pinjamans_id)->update(['status' => 'Lunas']);
                $pinjaman->update([
                    'status' => 'Lunas',
                ]);
            }

            DB::commit();
            return redirect()->route('pengembalian-pinjaman.index')
                ->with('success', 'Data pengembalian pinjaman berhasil ditambahkan');
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
        $pengembalian = PengembalianPinjaman::with(['pinjaman', 'pinjaman.nasabah'])->findOrFail($id);
        return view('simpan-pinjam.pengembalian-pinjaman.show', compact('pengembalian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pengembalian = PengembalianPinjaman::findOrFail($id);
        $pinjaman = Pinjamans::all();
        return view('simpan-pinjam.pengembalian-pinjaman.edit', compact('pengembalian', 'pinjaman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pengembalian = PengembalianPinjaman::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'pinjamans_id' => 'required|exists:pinjamans,id',
            'nominal_cicilan' => 'required|numeric|min:0',
            'tgl_pengembalian_sementara' => 'required|date',
            'status' => 'required|in:Lunas,Belum Lunas',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $oldNominalCicilan = $pengembalian->getOriginalNominalCicilanAttribute();
            $newNominalCicilan = $request->nominal_cicilan;
            $pengembalian->update([
                'pinjamans_id' => $request->pinjamans_id,
                'nominal_cicilan' => $newNominalCicilan,
                'tgl_pengembalian_sementara' => $request->tgl_pengembalian_sementara,
                'status' => $request->status
            ]);

            $pinjaman = Pinjamans::findOrFail($request->pinjamans_id);
            $totalPengembalian = DB::table('pengembalian_pinjamans')
                ->where('pinjamans_id', $request->pinjamans_id)
                ->sum('nominal_cicilan');
            $nominalPokok = $pinjaman->getOriginalNominalAttribute();
            if ($totalPengembalian >= $nominalPokok) {
                $pinjaman->update([
                    'status' => 'Lunas',
                    'nominal_pengembalian' => $totalPengembalian
                ]);
                if ($request->status === 'Lunas') {
                    PengembalianPinjaman::where('pinjamans_id', $request->pinjamans_id)
                        ->update(['status' => 'Lunas']);
                }
            }
            DB::commit();
            return redirect()->route('pengembalian-pinjaman.index')
                ->with('success', 'Data pengembalian pinjaman berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    public function destroy(string $id)
    {
        $pengembalian = PengembalianPinjaman::findOrFail($id);
        $pinjamans_id = $pengembalian->pinjamans_id;

        DB::beginTransaction();
        try {
            $pengembalian->delete();
            $pinjaman = Pinjamans::findOrFail($pinjamans_id);
            if ($pinjaman->status === 'Lunas') {
                $pinjaman->update([
                    'status' => 'Belum Lunas'
                ]);
                PengembalianPinjaman::where('pinjamans_id', $pinjamans_id)->update([
                    'status' => 'Belum Lunas'
                ]);
                Log::info("Pinjaman ID: $pinjamans_id - Status diubah dari Lunas ke Belum Lunas setelah penghapusan cicilan ID: $id");
            }

            DB::commit();
            return redirect()->route('pengembalian-pinjaman.index')
                ->with('success', 'Data pengembalian pinjaman berhasil dihapus dan status pinjaman diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get pinjaman by nasabah id.
     */
    public function getPinjamanByNasabah($id)
    {
        $pinjaman = Pinjamans::where('nasabah_id', $id)
            ->where('status', 'Belum Lunas')
            ->with('pengembalianPinjaman')
            ->get()
            ->map(function ($item) {
                $totalPembayaran = DB::table('pengembalian_pinjamans')
                    ->where('pinjamans_id', $item->id)
                    ->sum('nominal_cicilan');

                $item->attributes = [
                    'nominal' => $item->getOriginalNominalAttribute(),
                    'nominal_pengembalian' => $item->attributes['nominal_pengembalian'] ?? $item->getOriginalNominalAttribute(),
                    'tgl_pinjam' => $item->getOriginalTglPinjamAttribute()
                ];
                $item->total_pembayaran = $totalPembayaran;

                return $item;
            });
        return response()->json($pinjaman);
    }
}
