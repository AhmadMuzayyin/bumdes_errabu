<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengeluaran BRI Link</title>
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
        <h2>LAPORAN PENGELUARAN BRI LINK</h2>
        <p>Periode: {{ $periode }}</p>
        <p>Tanggal Cetak: {{ $tanggal_cetak }}</p>
    </div>

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
                    <td class="text-right">{{ number_format($item->nominal, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tgl_setor_tunai)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="5" class="text-right"><strong>Total Nominal</strong></td>
                <td colspan="2" class="text-right"><strong>{{ number_format($total_setor, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ $tanggal_cetak }}</p>
    </div>
</body>

</html>
