@if(count($rekapData) > 0)
    @foreach($rekapData as $key => $item)
        <tr>
            <td class="text-center">{{ $key + 1 }}</td>
            <td>{{ $item['nasabah']->nama }}</td>
            <td class="text-right">{{ 'Rp ' . number_format($item['total_pinjaman'], 0, ',', '.') }}</td>
            <td class="text-right">{{ 'Rp ' . number_format($item['total_pengembalian'], 0, ',', '.') }}</td>
            <td class="text-right">{{ 'Rp ' . number_format($item['sisa_pinjaman'], 0, ',', '.') }}</td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center">Tidak ada data nasabah.</td>
    </tr>
@endif
