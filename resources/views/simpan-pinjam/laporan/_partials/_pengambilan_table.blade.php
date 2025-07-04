@if(count($pengambilan) > 0)
    @foreach($pengambilan as $key => $item)
        <tr>
            <td class="text-center">{{ $key + 1 }}</td>
            <td>{{ $item->tgl_pengambilan->format('d/m/Y') }}</td>
            <td>{{ $item->nasabah->nama }}</td>
            <td class="text-right">{{ 'Rp ' . number_format($item->nominal, 0, ',', '.') }}</td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="4" class="text-center">Tidak ada data pengambilan simpanan pada periode ini</td>
    </tr>
@endif
