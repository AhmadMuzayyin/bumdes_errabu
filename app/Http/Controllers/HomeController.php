<?php

namespace App\Http\Controllers;

use App\Models\BadanUsaha;
use App\Models\Income;

class HomeController extends Controller
{
    public function __invoke()
    {
        $badan_usaha = BadanUsaha::with('user')->get();
        $keuangan = Income::with('badan_usaha')->get();
        return view('landing.index', compact('badan_usaha', 'keuangan'));
    }
}
