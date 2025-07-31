<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpendingRequest;
use App\Models\BadanUsaha;
use App\Models\Income;
use App\Models\IncomeBadanUsaha;
use App\Models\Spending;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SpendingController extends Controller
{
    public function index()
    {
        $spending = Spending::all();
        $bumdes = BadanUsaha::all();
        return view('spending.index', compact('spending', 'bumdes'));
    }

    public function store(SpendingRequest $request)
    {
        try {
            DB::beginTransaction();
            $saldo = Income::sum('nominal');
            $nominal = Spending::sum('nominal');
            $nominal = (int)$nominal + (int)$request['nominal'];
            if ($saldo < $nominal) {
                return redirect()->back()->with('error', 'Saldo tidak mencukupi untuk dana keluar ini');
            }
            Spending::create($request->validated());
            BadanUsaha::where('id', $request['badan_usaha_id'])
                ->update(['saldo' => $request['nominal']]);
            IncomeBadanUsaha::create([
                'badan_usaha_id' => $request['badan_usaha_id'],
                'jenis_pemasukan' => 'Modal Usaha',
                'nominal' => $request['nominal'],
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Data gagal ditambahkan');
        }
    }

    public function update(SpendingRequest $request, Spending $spending)
    {
        try {
            DB::beginTransaction();
            BadanUsaha::where('id', $request['badan_usaha_id'])
                ->update(['saldo' => $request['nominal']]);
            IncomeBadanUsaha::where('badan_usaha_id', $request['badan_usaha_id'])
                ->where('jenis_pemasukan', 'Modal Usaha')
                ->where('nominal', $spending->OriginalNominal)
                ->update(['nominal' => $request['nominal']]);
            $spending->update($request->validated());
            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Data gagal diubah');
        }
    }

    public function destroy(Spending $spending)
    {
        try {
            DB::beginTransaction();
            BadanUsaha::where('id', $spending['badan_usaha_id'])
                ->update(['saldo' => 0]);
            IncomeBadanUsaha::where('badan_usaha_id', $spending['badan_usaha_id'])
                ->where('jenis_pemasukan', 'Modal Usaha')
                ->where('nominal', $spending->OriginalNominal)
                ->delete();
            $spending->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Data gagal dihapus');
        }
    }
}
