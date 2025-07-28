<?php

namespace App\Http\Controllers\FotoCopy;

use App\Http\Controllers\Controller;
use App\Models\Spending;
use Illuminate\Http\Request;

class DanaMasukController extends Controller
{
    public function index()
    {
        $pemasukan = Spending::where('badan_usaha_id', auth()->user()->badan_usaha->id)
            ->get();
        return view('fotocopy.pemasukan.index', compact('pemasukan'));
    }
}
