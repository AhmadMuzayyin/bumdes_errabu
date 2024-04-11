<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        //
    }
    public function store(TransactionRequest $request)
    {
        //
    }

    public function update(TransactionRequest $request, Transaction $transaction)
    {
        //
    }
    public function destroy(Transaction $transaction)
    {
        try {
            $transaction->delete();
            return back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Data gagal dihapus');
        }
    }
}
