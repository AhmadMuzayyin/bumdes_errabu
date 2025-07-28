<?php

namespace App\Http\Controllers\BriLink;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\PengeluaranBriLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BriLinkDanaKeluarController extends Controller
{
    public function index()
    {
        $spendings = PengeluaranBriLink::all();
        return view('brilink.pengeluaran.index', compact('spendings'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'jenis_pengeluaran' => 'required|in:Belanja Rutin,Gaji Karyawan,Setor Pengahsilan,Lainnya',
            'kode' => 'required|string|unique:pengeluaran_bri_links,kode',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'tgl_pengeluaran' => 'required|date',
            'tujuan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            PengeluaranBriLink::create($request->all());
            if ($request->jenis_pengeluaran === 'Setor Pengahsilan') {
                Income::create([
                    'sumber_dana' => 'Penghasilan BRI Link',
                    'nominal' => $request->jumlah * $request->harga,
                    'tanggal' => $request->tgl_pengeluaran,
                ]);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Pengeluaran berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Pengeluaran gagal ditambahkan: ' . $th->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_pengeluaran' => 'required|in:Belanja Rutin,Gaji Karyawan,Setor Pengahsilan,Lainnya',
            'kode' => 'required|string|unique:pengeluaran_bri_links,kode,' . $id,
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'tgl_pengeluaran' => 'required|date',
            'tujuan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            $spending = PengeluaranBriLink::findOrFail($id);
            $spending->update($request->all());
            if ($request->jenis_pengeluaran === 'Setor Pengahsilan') {
                Income::updateOrCreate(
                    ['sumber_dana' => 'Penghasilan BRI Link', 'tanggal' => $request->tgl_pengeluaran],
                    ['nominal' => $request->jumlah * $request->harga]
                );
            }
            DB::commit();
            return redirect()->back()->with('success', 'Pengeluaran berhasil diperbarui');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Pengeluaran gagal diperbarui: ' . $th->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $spending = PengeluaranBriLink::findOrFail($id);
            $spending->delete();
            if ($spending->jenis_pengeluaran === 'Setor Pengahsilan') {
                Income::where('sumber_dana', 'Penghasilan BRI Link')
                    ->where('tanggal', $spending->tgl_pengeluaran)
                    ->delete();
            }
            DB::commit();
            return redirect()->back()->with('success', 'Pengeluaran berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Pengeluaran gagal dihapus: ' . $th->getMessage());
        }
    }
}
