<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Spending;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dana_masuk = [];
        $dana_keluar = [];
        if ($request->get('start') && $request->get('end')) {
            $dana_masuk = Income::whereBetween('tanggal', [$request->start, $request->end])
                ->orderBy('tanggal', 'asc')
                ->get();
            $dana_keluar = Spending::whereBetween('tanggal', [$request->start, $request->end])
                ->orderBy('tanggal', 'asc')
                ->get();
        } else {
            $dana_masuk = Income::orderBy('tanggal', 'asc')->get();
            $dana_keluar = Spending::orderBy('tanggal', 'asc')->get();
        }
        return view('laporan.index', compact('dana_masuk', 'dana_keluar'));
    }
}
