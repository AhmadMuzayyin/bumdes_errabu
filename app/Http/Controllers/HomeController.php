<?php

namespace App\Http\Controllers;

use App\Models\BadanUsaha;
use App\Models\Income;
use App\Models\Spending;

class HomeController extends Controller
{
    public function __invoke()
    {
        $badan_usaha = BadanUsaha::with('user')->get();
        $keuangan = Income::all();
        $pengeluaran = Spending::all();
        return view('landing.index', compact('badan_usaha', 'keuangan', 'pengeluaran'));
    }
}
