<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Dana Keluar</title>
    @include('helpers.format_helpers')
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
        }
        .meta-info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .summary {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .text-right {
            text-align: right;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>BUMDES ERRABU</h2>
        <p>Desa Errabu, Kecamatan Bluto, Kabupaten Sumenep</p>
        <h3>LAPORAN DANA KELUAR</h3>
        <p>Periode: {{ $periodeDisplay }}</p>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td width="150">Tanggal Cetak</td>
                <td>: {{ $tanggalCetak }}</td>
            </tr>
            <tr>
                <td>Periode Laporan</td>
                <td>: {{ $periodeDisplay }}</td>
            </tr>
            @if($badanUsahaId)
                <tr>
                    <td>Badan Usaha</td>
                    <td>: {{ $badanUsahaList->where('id', $badanUsahaId)->first()->nama }}</td>
                </tr>
            @endif
        </table>
    </div>

    <!-- Ringkasan Dana Keluar -->
    <div class="section">
        <h4>Ringkasan Dana Keluar</h4>
        <table>
            <tr>
                <th>Sumber</th>
                <th class="text-right" width="150">Jumlah (Rp)</th>
            </tr>
            <tr>
                <td>Umum</td>
                <td class="text-right">{{ number_format($data['summaryDanaKeluar']['umum'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Simpan Pinjam</td>
                <td class="text-right">{{ number_format($data['summaryDanaKeluar']['simpan_pinjam'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Foto Copy</td>
                <td class="text-right">{{ number_format($data['summaryDanaKeluar']['fotocopy'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr style="font-weight: bold; background-color: #f2f2f2;">
                <td>TOTAL DANA KELUAR</td>
                <td class="text-right">{{ number_format($data['totalDanaKeluar'], 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Dana Keluar Detail -->
    <div class="section">
        <!-- Dana Keluar Umum -->
        <h4 class="section-title">Dana Keluar - Umum</h4>
        <table>
            <tr>
                <th width="30">No</th>
                <th width="90">Tanggal</th>
                <th>Tujuan</th>
                <th>Keterangan</th>
                <th class="text-right" width="120">Jumlah (Rp)</th>
            </tr>
            @php $no = 1; @endphp
            @forelse($data['danaKeluar']['umum'] as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $item->tujuan }}</td>
                    <td>{{ $item->deskripsi ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center">Tidak ada data</td>
                </tr>
            @endforelse
            @if(count($data['danaKeluar']['umum']) > 0)
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td colspan="4" style="text-align: right">Total Dana Keluar Umum</td>
                    <td class="text-right">Rp {{ number_format($data['summaryDanaKeluar']['umum'] ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endif
        </table>

        <div class="page-break"></div>
        <!-- Dana Keluar Foto Copy -->
        <h4 class="section-title">Dana Keluar - Foto Copy</h4>
        <table>
            <tr>
                <th width="30">No</th>
                <th width="90">Tanggal</th>
                <th>Keterangan</th>
                <th class="text-right" width="120">Jumlah (Rp)</th>
            </tr>
            @php $no = 1; @endphp
            @forelse($data['danaKeluar']['fotocopy'] as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tgl_pengeluaran)->format('d/m/Y') }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td class="text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center">Tidak ada data</td>
                </tr>
            @endforelse
            @if(count($data['danaKeluar']['fotocopy']) > 0)
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td colspan="3" style="text-align: right">Total Dana Keluar Foto Copy</td>
                    <td class="text-right">Rp {{ number_format($data['summaryDanaKeluar']['fotocopy'] ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endif
        </table>

        <!-- Dana Keluar Simpan Pinjam -->
        <h4 class="section-title">Dana Keluar - Simpan Pinjam</h4>
        <table>
            <tr>
                <th width="30">No</th>
                <th width="90">Tanggal</th>
                <th>Jenis</th>
                <th>Keterangan</th>
                <th class="text-right" width="120">Jumlah (Rp)</th>
            </tr>
            @php 
                $no = 1; 
                $totalSimpanPinjamOut = 0;
            @endphp
            
            <!-- Pengambilan Simpanan -->
            @foreach($data['simpanPinjamData']['pengambilan_simpanan'] as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tgl_pengambilan)->format('d/m/Y') }}</td>
                    <td>Pengambilan Simpanan ({{ ucfirst($item->kategori) }})</td>
                    <td>{{ $item->nasabah->nama ?? 'N/A' }}</td>
                    <td class="text-right">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                </tr>
                @php $totalSimpanPinjamOut += $item->nominal; @endphp
            @endforeach
            
            <!-- Pinjaman -->
            @foreach($data['simpanPinjamData']['pinjaman'] as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</td>
                    <td>Pinjaman</td>
                    <td>{{ $item->nasabah->nama ?? 'N/A' }}</td>
                    <td class="text-right">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                </tr>
                @php $totalSimpanPinjamOut += $item->nominal; @endphp
            @endforeach
            
            <!-- Pengeluaran Operasional -->
            @foreach($data['simpanPinjamData']['pengeluaran'] as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tgl_pengeluaran)->format('d/m/Y') }}</td>
                    <td>Operasional</td>
                    <td>{{ $item->tujuan }}</td>
                    <td class="text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                </tr>
                @php $totalSimpanPinjamOut += $item->nominal; @endphp
            @endforeach
            
            @if($no == 1)
                <tr>
                    <td colspan="5" style="text-align: center">Tidak ada data</td>
                </tr>
            @else
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td colspan="4" style="text-align: right">Total Dana Keluar Simpan Pinjam</td>
                    <td class="text-right">Rp {{ number_format($totalSimpanPinjamOut, 0, ',', '.') }}</td>
                </tr>
            @endif
        </table>
    </div>

    <div class="footer">
        <p>Laporan dibuat oleh sistem BUMDES ERRABU - {{ $tanggalCetak }}</p>
    </div>
</body>
</html>
