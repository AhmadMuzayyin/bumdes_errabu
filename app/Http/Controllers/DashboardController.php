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
        // Redirect operator simpanpinjam ke dashboard simpan pinjam
        if (Auth::user()->role == 'operator simpan pinjam') {
            return redirect()->route('simpan-pinjam.dashboard');
        }

        // Redirect operator foto copy ke dashboard foto copy
        if (Auth::user()->role == 'operator foto copy') {
            return redirect()->route('fotocopy.dashboard');
        }

        $income = [];
        $pemasukan = [];
        $manual_income = [];
        $manual_spending = [];
        if (Auth::user()->role == 'admin') {
            $pemasukan = Income::selectRaw('SUM(nominal) as total, DATE_FORMAT(tanggal, "%Y-%m") as month')
                ->groupBy('month')
                ->get();
            $manual_income = Income::all();
            $manual_spending = Spending::all();
        } else {
            $pemasukan = Income::selectRaw('SUM(nominal) as total, DATE_FORMAT(tanggal, "%Y-%m") as month')
                ->join('badan_usahas', 'badan_usahas.id', '=', 'incomes.badan_usaha_id')
                ->where('badan_usahas.user_id', Auth::user()->id)
                ->groupBy('month')
                ->get();
            $manual_income = Income::with('badan_usaha')->whereHas('badan_usaha', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })->get();
        }
        $startMonth = Carbon::parse($pemasukan->min('month') ?? now())->startOfMonth();
        $endMonth = Carbon::parse($pemasukan->max('month') ?? now())->endOfMonth();
        $currentMonth = $startMonth;
        $income = [];
        while ($currentMonth->lte($endMonth)) {
            $income[] = [
                'bulan' => $currentMonth->format('F'),
                'nominal' => $pemasukan->firstWhere('month', $currentMonth->format('Y-m'))['total'] ?? 0,
                'badan_usaha' => $pemasukan->firstWhere('month', $currentMonth->format('Y-m'))['name'] ?? '',
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
        return view('dashboard', compact('income', 'spending', 'manual_income', 'manual_spending'));
    }
}
