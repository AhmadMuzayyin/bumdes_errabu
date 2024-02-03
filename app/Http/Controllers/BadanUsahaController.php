<?php

namespace App\Http\Controllers;

use App\Http\Requests\BadanUsahaRequest;
use App\Models\BadanUsaha;
use App\Models\User;

class BadanUsahaController extends Controller
{
    public function index()
    {
        $badan_usaha = BadanUsaha::all();
        $operator = User::where('role', 'operator')->get();

        return view('badan_usaha.index', compact('badan_usaha', 'operator'));
    }

    public function store(BadanUsahaRequest $request)
    {
        BadanUsaha::create($request->validated());

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function update(BadanUsahaRequest $request, BadanUsaha $badan_usaha)
    {
        $badan_usaha->update($request->validated());

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function destroy(BadanUsaha $badan_usaha)
    {
        $badan_usaha->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
