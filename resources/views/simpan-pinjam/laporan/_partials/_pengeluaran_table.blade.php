@if(count($pengeluaran) > 0)
    @foreach($pengeluaran as $key => $item)
        <tr>
            <td class="text-center">{{ $key + 1 }}</td>
            <td>{{ $item->tgl_pengeluaran->format('d/m/Y') }}</td>
            <td>{{ $item->kode }}</td>
            <td>{{ $item->tujuan }}</td>
            <td class="text-right">{{ 'Rp ' . number_format($item->nominal, 0, ',', '.') }}</td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center">Tidak ada data pengeluaran pada periode ini</td>
    </tr>
@endif
