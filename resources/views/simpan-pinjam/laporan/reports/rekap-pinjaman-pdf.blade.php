<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Pinjaman Nasabah</title>
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
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>REKAP PINJAMAN NASABAH</h2>
        <h3>BUMDES ERRABU</h3>
    </div>
    
    <div class="info">
        <table border="0" style="margin-bottom: 10px">
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
                <th width="20%" class="text-center">Nasabah</th>
                <th width="10%" class="text-center">Jumlah Pinjaman</th>
                <th width="15%" class="text-center">Total Pinjaman</th>
                <th width="10%" class="text-center">Jumlah Belum Lunas</th>
                <th width="15%" class="text-center">Total Belum Lunas</th>
                <th width="15%" class="text-center">Total Pengembalian</th>
                <th width="15%" class="text-center">Sisa Pinjaman</th>
            </tr>
        </thead>
        <tbody>
            @if(count($rekapData) > 0)
                @foreach($rekapData as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $item['nasabah']->nama }}</td>
                        <td class="text-center">{{ $item['jumlah_pinjaman'] }}</td>
                        <td class="text-right">{{ 'Rp ' . number_format($item['total_pinjaman'], 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item['jumlah_pinjaman_belum_lunas'] }}</td>
                        <td class="text-right">{{ 'Rp ' . number_format($item['total_pinjaman_belum_lunas'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ 'Rp ' . number_format($item['total_pengembalian'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ 'Rp ' . number_format($item['sisa_pinjaman'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data nasabah.</td>
                </tr>
            @endif
        </tbody>
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
