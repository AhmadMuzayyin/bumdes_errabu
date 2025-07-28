<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpendingRequest;
use App\Models\BadanUsaha;
use App\Models\Income;
use App\Models\Spending;

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
            $saldo = Income::sum('nominal');
            $nominal = Spending::sum('nominal');
            $nominal = (int)$nominal + (int)$request['nominal'];
            if ($saldo < $nominal) {
                return redirect()->back()->with('error', 'Saldo tidak mencukupi untuk dana keluar ini');
            }
            Spending::create($request->validated());
            return redirect()->back()->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data gagal ditambahkan');
        }
    }

    public function update(SpendingRequest $request, Spending $spending)
    {
        try {
            $spending->update($request->validated());

            return redirect()->back()->with('success', 'Data berhasil diubah');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data gagal diubah');
        }
    }

    public function destroy(Spending $spending)
    {
        try {
            $spending->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data gagal dihapus');
        }
    }
}
