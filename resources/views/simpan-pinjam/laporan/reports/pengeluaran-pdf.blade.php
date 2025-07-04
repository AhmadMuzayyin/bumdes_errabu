<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengeluaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2, .header h3 {
            margin: 5px 0;
        }
        .info {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 5px;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PENGELUARAN SIMPAN PINJAM</h2>
        <h3>BUMDES ERRABU</h3>
    </div>
    
    <div class="info">
        <table border="0" style="margin-bottom: 10px">
            <tr>
                <td width="15%"><strong>Periode</strong></td>
                <td>: {{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Cetak</strong></td>
                <td>: {{ date('d/m/Y') }}</td>
            </tr>
        </table>
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="30%">Nama Pengeluaran</th>
                <th width="30%">Keterangan</th>
                <th width="20%">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @if(count($pengeluaran) > 0)
                @foreach($pengeluaran as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_pengeluaran)->format('d/m/Y') }}</td>
                        <td>{{ $item->kode }}</td>
                        <td>{{ $item->tujuan ?? '-' }}</td>
                        <td class="text-right">{{ 'Rp ' . number_format($item->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data pengeluaran pada periode ini.</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total</th>
                <th class="text-right">{{ 'Rp ' . number_format($total, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>
            <strong>{{ date('d F Y') }}</strong><br>
            Petugas Simpan Pinjam
            <br><br><br><br>
            __________________________
        </p>
    </div>
</body>
</html>
