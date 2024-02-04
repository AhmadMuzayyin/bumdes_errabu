<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Spending;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $income = [];
        $pemasukan = [];
        if (Auth::user()->role == 'admin') {
            $pemasukan = Income::selectRaw('SUM(nominal) as total, DATE_FORMAT(tanggal, "%Y-%m") as month')
                ->groupBy('month')
                ->get();
        } else {
            $pemasukan = Income::selectRaw('SUM(nominal) as total, DATE_FORMAT(tanggal, "%Y-%m") as month')
                ->join('badan_usahas', 'badan_usahas.id', '=', 'incomes.badan_usaha_id')
                ->where('badan_usahas.user_id', Auth::user()->id)
                ->groupBy('month')
                ->get();
        }
        $startMonth = Carbon::parse($pemasukan->min('month') ?? now())->startOfMonth();
        $endMonth = Carbon::parse($pemasukan->max('month') ?? now())->endOfMonth();
        $currentMonth = $startMonth;
        $income = [];
        while ($currentMonth->lte($endMonth)) {
            $income[] = [
                'bulan' => $currentMonth->format('F'),
                'nominal' => $pemasukan->firstWhere('month', $currentMonth->format('Y-m'))['total'] ?? 0,
            ];
            $currentMonth->addMonth();
        }

        $spending = [];
        $pengeluaran = Spending::selectRaw('SUM(nominal) as total, DATE_FORMAT(tanggal, "%Y-%m") as month')
            ->groupBy('month')
            ->get();
        $start = Carbon::parse($pengeluaran->min('month') ?? now())->startOfMonth();
        $end = Carbon::parse($pengeluaran->max('month') ?? now())->endOfMonth();
        $current = $start;
        while ($current->lte($end)) {
            $spending[] = [
                'bulan' => $current->format('F'),
                'nominal' => $pengeluaran->firstWhere('month', $current->format('Y-m'))['total'] ?? 0,
            ];
            $current->addMonth();
        }
        return view('dashboard', compact('income', 'spending'));
    }
}
