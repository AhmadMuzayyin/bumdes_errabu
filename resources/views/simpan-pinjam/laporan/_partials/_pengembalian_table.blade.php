@if(count($pengembalian) > 0)
    @foreach($pengembalian as $key => $item)
        <tr>
            <td class="text-center">{{ $key + 1 }}</td>
            <td>{{ $item->tgl_pengembalian->format('d/m/Y') }}</td>
            <td>{{ $item->pinjaman->nasabah->nama }}</td>
            <td class="text-right">{{ 'Rp ' . number_format($item->nominal_cicilan, 0, ',', '.') }}</td>
            <td class="text-center">
                <span class="badge {{ $item->pinjaman->status == 'Lunas' ? 'badge-success' : 'badge-warning' }}">
                    {{ $item->pinjaman->status }}
                </span>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center">Tidak ada data pengembalian pinjaman pada periode ini</td>
    </tr>
@endif
