<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengambilan Simpanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        .title {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
        }
        .subtitle {
            font-size: 12pt;
            margin: 5px 0;
        }
        .info {
            margin-bottom: 10px;
            font-size: 9pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9pt;
        }
        th, td {
            border: 0.5px solid #000;
            padding: 4px 6px;
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
            margin-top: 20px;
            font-size: 8pt;
            text-align: right;
        }
        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN PENGAMBILAN SIMPANAN</div>
        <div class="subtitle">BUMDes Errabu</div>
    </div>
    
    <div class="info">
        <table border="0" cellspacing="0" cellpadding="0" style="border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 100px;"><strong>Periode</strong></td>
                <td style="border: none;">: {{ date('d/m/Y', strtotime($tanggalAwal)) }} - {{ date('d/m/Y', strtotime($tanggalAkhir)) }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;"><strong>Tanggal Cetak</strong></td>
                <td style="border: none;">: {{ date('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>
    
    <table cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Nasabah</th>
                <th width="20%">Tanggal</th>
                <th width="40%">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @if(count($pengambilan) > 0)
                @foreach($pengambilan as $index => $p)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $p->nasabah->nama }}</td>
                    <td>{{ date('d/m/Y', strtotime($p->attributes['tgl_pengambilan'])) }}</td>
                    <td class="text-right">{{ $p->nominal }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="3" class="text-right"><strong>TOTAL</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        * Laporan ini dicetak melalui sistem dan tidak memerlukan tanda tangan
    </div>
</body>
</html>
