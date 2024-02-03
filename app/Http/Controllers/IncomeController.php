<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeRequest;
use App\Models\BadanUsaha;
use App\Models\Income;

class IncomeController extends Controller
{
    public function index()
    {
        $income = [];
        $usaha = [];
        if (auth()->user()->role == 'operator') {
            $usaha = BadanUsaha::where('user_id', auth()->user()->id)->first();
            $income = Income::where('badan_usaha_id', $usaha->id)->get();
        } else {
            $usaha = BadanUsaha::all();
            $income = Income::all();
        }

        return view('income.index', compact('income', 'usaha'));
    }

    public function store(IncomeRequest $request)
    {
        Income::create($request->validated());

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function update(IncomeRequest $request, Income $income)
    {
        $income->update($request->validated());

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function destroy(Income $income)
    {
        $income->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
