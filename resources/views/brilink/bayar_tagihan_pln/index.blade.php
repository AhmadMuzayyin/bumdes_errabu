@extends('layouts.app', ['title' => 'Bayar Tagihan PLN BRI Link'])
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Daftar Bayar Tagihan PLN</h3>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahBayarTagihan">
                            <i class="fas fa-plus"></i> Tambah Bayar Tagihan
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabelBayarTagihan">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Nama</th>
                                    <th>ID Pelanggan</th>
                                    <th>Nominal</th>
                                    <th>Tanggal</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bayar_tagihan as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->id_pelanggan }}</td>
                                        <td>{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d/m/Y') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                                data-target="#modalDetailBayarTagihan{{ $item->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                                data-target="#modalEditBayarTagihan{{ $item->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('brilink.bayar-tagihan-pln.destroy', $item->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Containers for all items -->
    @foreach($bayar_tagihan as $item)
        <!-- Modal Detail Bayar Tagihan -->
        <div class="modal fade" id="modalDetailBayarTagihan{{ $item->id }}" tabindex="-1"
            role="dialog" aria-labelledby="modalDetailBayarTagihanLabel{{ $item->id }}"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetailBayarTagihanLabel{{ $item->id }}">
                            Detail Bayar Tagihan PLN</h5>
                        <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Kode Transaksi</th>
                                    <td>{{ $item->kode }}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $item->nama }}</td>
                                </tr>
                                <tr>
                                    <th>ID Pelanggan</th>
                                    <td>{{ $item->id_pelanggan }}</td>
                                </tr>
                                <tr>
                                    <th>Nominal</th>
                                    <td>{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat pada</th>
                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Diupdate pada</th>
                                    <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Bayar Tagihan -->
        <div class="modal fade" id="modalEditBayarTagihan{{ $item->id }}" tabindex="-1"
            role="dialog" aria-labelledby="modalEditBayarTagihanLabel{{ $item->id }}"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditBayarTagihanLabel{{ $item->id }}">
                            Edit Bayar Tagihan PLN</h5>
                        <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('brilink.bayar-tagihan-pln.update', $item->id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="kode">Kode Transaksi</label>
                                <input type="text" class="form-control" id="kode"
                                    name="kode" value="{{ $item->kode }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" id="nama"
                                    name="nama" value="{{ $item->nama }}" required>
                            </div>
                            <div class="form-group">
                                <label for="id_pelanggan">ID Pelanggan</label>
                                <input type="text" class="form-control" id="id_pelanggan"
                                    name="id_pelanggan" value="{{ $item->id_pelanggan }}" required>
                            </div>
                            <div class="form-group">
                                <label for="nominal">Nominal</label>
                                <input type="number" class="form-control" id="nominal"
                                    name="nominal" value="{{ $item->nominal }}" required>
                            </div>
                            <div class="form-group">
                                <label for="tgl_transaksi">Tanggal</label>
                                <input type="date" class="form-control" id="tgl_transaksi"
                                    name="tgl_transaksi" value="{{ $item->tgl_transaksi }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modal Tambah Bayar Tagihan -->
    <div class="modal fade" id="modalTambahBayarTagihan" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahBayarTagihanLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahBayarTagihanLabel">Tambah Bayar Tagihan PLN</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('brilink.bayar-tagihan-pln.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kode">Kode Transaksi</label>
                            <input type="text" class="form-control" id="kode" name="kode"
                                value="{{ 'PLN-' . date('YmdHis') }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukkan nama" required>
                        </div>
                        <div class="form-group">
                            <label for="id_pelanggan">ID Pelanggan</label>
                            <input type="text" class="form-control" id="id_pelanggan" name="id_pelanggan"
                                placeholder="Masukkan ID pelanggan" required>
                        </div>
                        <div class="form-group">
                            <label for="nominal">Nominal</label>
                            <input type="number" class="form-control" id="nominal" name="nominal"
                                placeholder="Masukkan nominal" required>
                        </div>
                        <div class="form-group">
                            <label for="tgl_transaksi">Tanggal</label>
                            <input type="date" class="form-control" id="tgl_transaksi" name="tgl_transaksi"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#tabelBayarTagihan').DataTable();
        });
    </script>
@endpush
