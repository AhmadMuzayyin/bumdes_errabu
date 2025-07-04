<?php

namespace App\Http\Controllers\BriLink;

use App\Http\Controllers\Controller;
use App\Models\BriLinkSetorTunai;
use App\Models\BriLinkTarikTunai;
use App\Models\BriLinkBayarTagihanPln;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BriLinkDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalSetorTunai' => BriLinkSetorTunai::count(),
            'totalTarikTunai' => BriLinkTarikTunai::count(),
            'totalBayarPln' => BriLinkBayarTagihanPln::count(),
            'nominalSetorTunai' => BriLinkSetorTunai::sum('nominal'),
            'nominalTarikTunai' => BriLinkTarikTunai::sum('nominal'),
            'nominalBayarPln' => BriLinkBayarTagihanPln::sum('nominal'),
            'transaksiTerbaru' => $this->getLatestTransactions(),
        ];

        return view('brilink.dashboard', $data);
    }

    private function getLatestTransactions()
    {
        $setorTunai = BriLinkSetorTunai::latest()->take(5)->get()->map(function ($item) {
            return [
                'kode' => $item->kode_transaksi,
                'nama' => $item->nama,
                'nominal' => $item->nominal,
                'jenis' => 'Setor Tunai',
                'tanggal' => Carbon::parse($item->tgl_setor_tunai)->format('d/m/Y'),
            ];
        });

        $tarikTunai = BriLinkTarikTunai::latest()->take(5)->get()->map(function ($item) {
            return [
                'kode' => $item->kode_transaksi,
                'nama' => $item->nama,
                'nominal' => $item->nominal,
                'jenis' => 'Tarik Tunai',
                'tanggal' => Carbon::parse($item->tgl_tarik_tunai)->format('d/m/Y'),
            ];
        });

        $bayarPln = BriLinkBayarTagihanPln::latest()->take(5)->get()->map(function ($item) {
            return [
                'kode' => $item->kode,
                'nama' => $item->nama,
                'nominal' => $item->nominal,
                'jenis' => 'Bayar Tagihan PLN',
                'tanggal' => Carbon::parse($item->tgl_transaksi)->format('d/m/Y'),
            ];
        });

        return $setorTunai->concat($tarikTunai)->concat($bayarPln)
            ->sortByDesc('tanggal')
            ->take(10)
            ->values()
            ->all();
    }
}
