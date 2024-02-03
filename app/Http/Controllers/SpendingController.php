<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpendingRequest;
use App\Models\Spending;

class SpendingController extends Controller
{
    public function index()
    {
        $spending = Spending::all();

        return view('spending.index', compact('spending'));
    }

    public function store(SpendingRequest $request)
    {
        try {
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
