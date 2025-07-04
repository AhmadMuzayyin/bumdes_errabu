@if(count($pinjaman) > 0)
    @foreach($pinjaman as $key => $item)
        <tr>
            <td class="text-center">{{ $key + 1 }}</td>
            <td>{{ $item->tgl_pinjam->format('d/m/Y') }}</td>
            <td>{{ $item->nasabah->nama }}</td>
            <td class="text-right">{{ 'Rp ' . number_format($item->nominal, 0, ',', '.') }}</td>
            <td class="text-center">
                <span class="badge {{ $item->status == 'Lunas' ? 'badge-success' : 'badge-warning' }}">
                    {{ $item->status }}
                </span>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center">Tidak ada data pinjaman pada periode ini</td>
    </tr>
@endif
