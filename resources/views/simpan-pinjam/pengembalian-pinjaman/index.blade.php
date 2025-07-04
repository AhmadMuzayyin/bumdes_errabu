@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Pengembalian Pinjaman</h3>
                    <div class="card-tools">
                        <a href="{{ route('pengembalian-pinjaman.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Pengembalian
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="pengembalianTable">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nasabah</th>
                                    <th>Nominal Cicilan</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Status Cicilan</th>
                                    <th>Status Pinjaman</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengembalian as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->pinjaman->nasabah->nama }}</td>
                                        <td>{{ $item->nominal_cicilan }}</td>
                                        <td>{{ $item->tgl_pengembalian_sementara }}</td>
                                        <td>
                                            @if($item->status == 'Lunas')
                                                <span class="badge badge-success">Lunas</span>
                                            @else
                                                <span class="badge badge-warning">Belum Lunas</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->pinjaman->status == 'Lunas')
                                                <span class="badge badge-success">Lunas</span>
                                            @else
                                                <span class="badge badge-warning">Belum Lunas</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('pengembalian-pinjaman.show', $item->id) }}" class="btn btn-info btn-sm" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('pengembalian-pinjaman.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('pengembalian-pinjaman.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                @php
                                                    // Hitung total pembayaran selain cicilan ini
                                                    $totalLainnya = App\Models\PengembalianPinjaman::where('pinjamans_id', $item->pinjamans_id)
                                                        ->where('id', '!=', $item->id)
                                                        ->sum('nominal_cicilan');
                                                    
                                                    // Cek apakah penghapusan akan mengubah status pinjaman
                                                    $nominalPokok = $item->pinjaman->getOriginalNominalAttribute();
                                                    $totalSetelahHapus = $totalLainnya;
                                                    $akanUbahStatus = ($item->pinjaman->status == 'Lunas' && $totalSetelahHapus < $nominalPokok);
                                                    
                                                    // Buat pesan konfirmasi
                                                    $confirmMessage = 'Apakah Anda yakin ingin menghapus data pengembalian pinjaman ini?';
                                                    if ($akanUbahStatus) {
                                                        $confirmMessage .= '\n\nPERINGATAN: Menghapus cicilan ini akan mengubah status pinjaman dari LUNAS menjadi BELUM LUNAS!';
                                                    }
                                                @endphp
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('{{ $confirmMessage }}')" 
                                                    title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#pengembalianTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            }
        });
    });
</script>
@endsection
