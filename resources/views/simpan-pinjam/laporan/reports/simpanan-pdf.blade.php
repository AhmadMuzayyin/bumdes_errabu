<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Simpanan</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN TRANSAKSI SIMPANAN</h2>
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
                <th width="25%">Nasabah</th>
                <th width="10%">Jenis</th>
                <th width="20%">Nominal</th>
                <th width="25%">Saldo Berjalan</th>
            </tr>
        </thead>
        <tbody>
            @if(count($transaksi) > 0)
                @foreach($transaksi as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y') }}</td>
                        <td>{{ $item['nasabah_nama'] }}</td>
                        <td class="text-center">{{ $item['jenis'] }}</td>
                        <td class="text-right">{{ 'Rp ' . number_format($item['nominal'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ 'Rp ' . number_format($item['saldo_berjalan'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data transaksi simpanan pada periode ini.</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">Saldo Akhir</th>
                <th class="text-right">{{ 'Rp ' . number_format($saldoAkhir, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name }}</p>
        <p>Tanggal: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
