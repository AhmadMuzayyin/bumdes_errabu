<?php

namespace App\Http\Controllers\BriLink;

use App\Http\Controllers\Controller;
use App\Models\IncomeBadanUsaha;
use App\Models\Spending;
use Illuminate\Http\Request;

class BriLinkDanaMasukController extends Controller
{
    public function index()
    {
        $incomes = IncomeBadanUsaha::where('badan_usaha_id', auth()->user()->badan_usaha->id)->get();
        return view('brilink.dana-masuk.index', compact('incomes'));
    }
}
