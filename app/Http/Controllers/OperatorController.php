<?php

namespace App\Http\Controllers;

use App\Http\Requests\OperatorRequest;
use App\Models\User;

class OperatorController extends Controller
{
    public function index()
    {
        $operator = User::where('role', 'operator')->get();

        return view('operator.index', compact('operator'));
    }

    public function store(OperatorRequest $request)
    {
        try {
            User::create($request->validated());

            return redirect()->back()->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data gagal ditambahkan');
        }
    }

    public function update(OperatorRequest $request, User $operator)
    {
        try {
            $operator->update($request->validated());

            return redirect()->back()->with('success', 'Data berhasil diubah');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data gagal diubah');
        }
    }

    public function destroy(User $operator)
    {
        try {
            $operator->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data gagal dihapus');
        }
    }
}
