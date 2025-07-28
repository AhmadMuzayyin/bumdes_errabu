<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan BRI Link</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 5px 0;
        }

        .header p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .section-title {
            background-color: #007bff;
            color: white;
            padding: 5px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .summary-table {
            width: 50%;
            margin-top: 20px;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>LAPORAN TRANSAKSI BRI LINK</h2>
        <p>Periode: {{ $periode }}</p>
        <p>Tanggal Cetak: {{ $tanggal_cetak }}</p>
    </div>

    <h3 class="section-title">1. Laporan Setor Tunai</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Kode Transaksi</th>
                <th>Nama</th>
                <th>No Rekening</th>
                <th>Jumlah</th>
                <th>Nominal</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($setor_tunai as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->kode_transaksi }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->norek }}</td>
                    <td class="text-right">{{ $item->jumlah }}</td>
                    <td class="text-right">Rp. {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tgl_setor_tunai)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="5" class="text-right"><strong>Total Nominal</strong></td>
                <td colspan="2" class="text-right"><strong>Rp.
                        {{ number_format($total_setor, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <h3 class="section-title">2. Laporan Tarik Tunai</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Kode Transaksi</th>
                <th>Nama</th>
                <th>No Rekening</th>
                <th>No Rekening Tujuan</th>
                <th>Nominal</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tarik_tunai as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->kode_transaksi }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->norek }}</td>
                    <td>{{ $item->norek_tujuan }}</td>
                    <td class="text-right">Rp. {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tgl_tarik_tunai)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="5" class="text-right"><strong>Total Nominal</strong></td>
                <td colspan="2" class="text-right"><strong>Rp.
                        {{ number_format($total_tarik, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <h3 class="section-title">3. Laporan Bayar Tagihan PLN</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>ID Pelanggan</th>
                <th>Nominal</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bayar_tagihan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->id_pelanggan }}</td>
                    <td class="text-right">Rp. {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="4" class="text-right"><strong>Total Nominal</strong></td>
                <td colspan="2" class="text-right">
                    <strong>Rp. {{ number_format($total_bayar_pln, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <h3 class="section-title">4. Laporan Pemasukan</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nominal</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pemasukan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-right">Rp. {{ number_format($item->originalNominal, 0, ',', '.') }}</td>
                    <td>
                        {{ $item->keterangan }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="2" class="text-right"><strong>Total Nominal</strong></td>
                <td colspan="2" class="text-right">
                    <strong>Rp. {{ number_format($pemasukan->sum('originalNominal'), 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <h3 class="section-title">5. Laporan Pengeluaran</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Kode</th>
                <th>Jenis Pengeluaran</th>
                <th>Jumlah</th>
                <th>Nominal</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_pegeluaran = 0;
            @endphp
            @forelse ($pengeluaran as $index => $item)
                @php
                    $total_pegeluaran += $item->harga * $item->jumlah;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->jenis_pengeluaran }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
            <tr class="bg-light">
                <td colspan="4" class="text-right"><strong>Total Nominal</strong></td>
                <td colspan="2"><strong>Rp. {{ number_format($total_pegeluaran, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <h3 class="section-title">6. Rekap Transaksi</h3>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Jenis Transaksi</th>
                <th>Jumlah Transaksi</th>
                <th>Total Nominal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Pemasukan</td>
                <td class="text-center">{{ count($pemasukan) }}</td>
                <td class="text-right">Rp. {{ number_format($pemasukan->sum('originalNominal'), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pengeluaran</td>
                <td class="text-center">{{ count($pengeluaran) }}</td>
                @php
                    $total_pengeluaran = 0;
                    foreach ($pengeluaran as $key => $value) {
                        $total_pengeluaran += $value->harga * $value->jumlah;
                    }
                @endphp
                <td class="text-right">Rp. {{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Setor Tunai</td>
                <td class="text-center">{{ count($setor_tunai) }}</td>
                <td class="text-right">Rp. {{ number_format($total_setor, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tarik Tunai</td>
                <td class="text-center">{{ count($tarik_tunai) }}</td>
                <td class="text-right">Rp. {{ number_format($total_tarik, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Bayar Tagihan PLN</td>
                <td class="text-center">{{ count($bayar_tagihan) }}</td>
                <td class="text-right">Rp. {{ number_format($total_bayar_pln, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total</strong></td>
                <td class="text-center">
                    <strong>{{ count($setor_tunai) + count($tarik_tunai) + count($bayar_tagihan) }}</strong>
                </td>
                <td class="text-right">
                    <strong>Rp.
                        {{ number_format($total_setor + $total_tarik + $total_bayar_pln + $total_pengeluaran + $pemasukan->sum('originalNominal'), 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ $tanggal_cetak }}</p>
    </div>
</body>

</html>
