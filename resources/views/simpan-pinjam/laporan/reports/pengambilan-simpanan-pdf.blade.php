<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengambilan Simpanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
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
        <h2>LAPORAN PENGAMBILAN SIMPANAN</h2>
        <h3>BUMDES ERRABU</h3>
    </div>
    
    <div class="info">
        <table border="0" style="margin-bottom: 10px">
            <tr>
                <td><strong>Periode</strong></td>
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
                <th width="5%" class="text-center">No</th>
                <th width="15%" class="text-center">Tanggal</th>
                <th width="50%" class="text-center">Nama Nasabah</th>
                <th width="30%" class="text-center">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @if(count($pengambilan) > 0)
                @foreach($pengambilan as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ $item->tgl_pengambilan->format('d/m/Y') }}</td>
                        <td>{{ $item->nasabah->nama }}</td>
                        <td class="text-right">{{ number_format($item->nominal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data pengambilan simpanan pada periode ini</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total</th>
                <th class="text-right">{{ number_format($total, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>Tanggal: {{ date('d/m/Y') }}</p>
        <br><br><br>
        <p>(___________________)</p>
        <p>Petugas</p>
    </div>
</body>
</html>
