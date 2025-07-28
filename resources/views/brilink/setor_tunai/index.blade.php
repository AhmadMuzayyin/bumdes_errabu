@extends('layouts.app', ['title' => 'Setor Tunai BRI Link'])
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Daftar Setor Tunai</h3>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahSetorTunai">
                            <i class="fas fa-plus"></i> Tambah Setor Tunai
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabelSetorTunai">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Nama</th>
                                    <th>No Rekening</th>
                                    <th>Jumlah</th>
                                    <th>Nominal</th>
                                    <th>Tanggal</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($setor_tunai as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_transaksi }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->norek }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>Rp. {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_setor_tunai)->format('d/m/Y') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                                data-target="#modalDetailSetorTunai{{ $item->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                                data-target="#modalEditSetorTunai{{ $item->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('brilink.setor-tunai.destroy', $item->id) }}"
                                                method="POST" class="d-inline">
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
                                        <td colspan="8" class="text-center">Tidak ada data</td>
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
    @foreach ($setor_tunai as $item)
        <!-- Modal Detail Setor Tunai -->
        <div class="modal fade" id="modalDetailSetorTunai{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modalDetailSetorTunaiLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetailSetorTunaiLabel{{ $item->id }}">
                            Detail Setor Tunai</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Kode Transaksi</th>
                                    <td>{{ $item->kode_transaksi }}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $item->nama }}</td>
                                </tr>
                                <tr>
                                    <th>No Rekening</th>
                                    <td>{{ $item->norek }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah</th>
                                    <td>{{ $item->jumlah }}</td>
                                </tr>
                                <tr>
                                    <th>Nominal</th>
                                    <td>Rp. {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ \Carbon\Carbon::parse($item->tgl_setor_tunai)->format('d/m/Y') }}</td>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Setor Tunai -->
        <div class="modal fade" id="modalEditSetorTunai{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditSetorTunaiLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditSetorTunaiLabel{{ $item->id }}">
                            Edit Setor Tunai</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('brilink.setor-tunai.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="kode_transaksi">Kode Transaksi</label>
                                <input type="text" class="form-control" id="kode_transaksi" name="kode_transaksi"
                                    value="{{ $item->kode_transaksi }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="jumlah">Jumlah</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah"
                                    value="{{ $item->jumlah }}" required>
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="{{ $item->nama }}" required>
                            </div>
                            <div class="form-group">
                                <label for="norek">No Rekening</label>
                                <input type="text" class="form-control" id="norek" name="norek"
                                    value="{{ $item->norek }}" required>
                            </div>
                            <div class="form-group">
                                <label for="nominal">Nominal</label>
                                <input type="number" class="form-control" id="nominal" name="nominal"
                                    value="{{ $item->nominal }}" required>
                            </div>
                            <div class="form-group">
                                <label for="tgl_setor_tunai">Tanggal</label>
                                <input type="date" class="form-control" id="tgl_setor_tunai" name="tgl_setor_tunai"
                                    value="{{ $item->tgl_setor_tunai }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modal Tambah Setor Tunai -->
    <div class="modal fade" id="modalTambahSetorTunai" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahSetorTunaiLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahSetorTunaiLabel">Tambah Setor Tunai</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('brilink.setor-tunai.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kode_transaksi">Kode Transaksi</label>
                            <input type="text" class="form-control" id="kode_transaksi" name="kode_transaksi"
                                value="{{ 'STR-' . date('YmdHis') }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah"
                                placeholder="Masukkan jumlah" required>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukkan nama" required>
                        </div>
                        <div class="form-group">
                            <label for="norek">No Rekening</label>
                            <input type="text" class="form-control" id="norek" name="norek"
                                placeholder="Masukkan nomor rekening" required>
                        </div>
                        <div class="form-group">
                            <label for="nominal">Nominal</label>
                            <input type="number" class="form-control" id="nominal" name="nominal"
                                placeholder="Masukkan nominal" required>
                        </div>
                        <div class="form-group">
                            <label for="tgl_setor_tunai">Tanggal</label>
                            <input type="date" class="form-control" id="tgl_setor_tunai" name="tgl_setor_tunai"
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
            $('#tabelSetorTunai').DataTable();
        });
    </script>
@endpush
