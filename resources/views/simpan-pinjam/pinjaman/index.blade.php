@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Pinjaman</h3>
                    <div class="card-tools">
                        <a href="{{ route('pinjaman.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Pinjaman
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="pinjamanTable">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nasabah</th>
                                    <th>Nominal</th>
                                    <th>Bunga</th>
                                    <th>Total</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pinjaman as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->nasabah->nama }}</td>
                                        <td>{{ $item->nominal }}</td>
                                        <td>{{ $bunga }} (Rp
                                            {{ number_format(($item->original_nominal * $original_bunga) / 100, 0, ',', '.') }})
                                        </td>
                                        <td>Rp. {{ number_format($item->nominal_pengembalian, 0, ',', '.') }}</td>
                                        <td>{{ $item->tgl_pinjam }}</td>
                                        <td>
                                            @if ($item->status == 'Lunas')
                                                <span class="badge badge-success">Lunas</span>
                                            @else
                                                <span class="badge badge-warning">Belum Lunas</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('pinjaman.show', $item->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('pinjaman.edit', $item->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('pinjaman.destroy', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data pinjaman ini?')">
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
            $('#pinjamanTable').DataTable({
                "responsive": true,
                "autoWidth": false,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
                }
            });
        });
    </script>
@endsection
