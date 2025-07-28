<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemasukan BRI Link</title>
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

        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>LAPORAN PEMASUKAN BRI LINK</h2>
        <p>Periode: {{ $periode }}</p>
        <p>Tanggal Cetak: {{ $tanggal_cetak }}</p>
    </div>

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
            @php
                $total_pemasukan = 0;
            @endphp
            @forelse ($pemasukan as $index => $item)
                @php
                    $total_pemasukan += $item->originalNominal;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>Rp. {{ number_format($item->originalNominal, 0, ',', '.') }}</td>
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
                    <strong>Rp. {{ number_format($total_pemasukan, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ $tanggal_cetak }}</p>
    </div>
</body>

</html>
