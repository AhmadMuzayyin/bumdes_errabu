@if(count($simpanan) > 0)
    @foreach($simpanan as $key => $item)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ \Carbon\Carbon::parse($item->tgl_simpan)->format('d/m/Y') }}</td>
        <td>{{ $item->nasabah->nama }}</td>
        <td class="text-right">{{ 'Rp ' . number_format($item->attributes['nominal'], 0, ',', '.') }}</td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="4" class="text-center">Tidak ada data simpanan pada periode ini.</td>
    </tr>
@endif
