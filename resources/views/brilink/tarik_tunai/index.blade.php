@extends('layouts.app', ['title' => 'Tarik Tunai BRI Link'])
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Daftar Tarik Tunai</h3>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahTarikTunai">
                            <i class="fas fa-plus"></i> Tambah Tarik Tunai
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabelTarikTunai">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Nama</th>
                                    <th>No Rekening</th>
                                    <th>No Rekening Tujuan</th>
                                    <th>Nominal</th>
                                    <th>Tanggal</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tarik_tunai as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_transaksi }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->norek }}</td>
                                        <td>{{ $item->norek_tujuan }}</td>
                                        <td>Rp. {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_tarik_tunai)->format('d/m/Y') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                                data-target="#modalDetailTarikTunai{{ $item->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                                data-target="#modalEditTarikTunai{{ $item->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('brilink.tarik-tunai.destroy', $item->id) }}"
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
    @foreach ($tarik_tunai as $item)
        <!-- Modal Detail Tarik Tunai -->
        <div class="modal fade" id="modalDetailTarikTunai{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modalDetailTarikTunaiLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetailTarikTunaiLabel{{ $item->id }}">
                            Detail Tarik Tunai</h5>
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
                                    <th>No Rekening Tujuan</th>
                                    <td>{{ $item->norek_tujuan }}</td>
                                </tr>
                                <tr>
                                    <th>Nominal</th>
                                    <td>Rp. {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ \Carbon\Carbon::parse($item->tgl_tarik_tunai)->format('d/m/Y') }}</td>
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

        <!-- Modal Edit Tarik Tunai -->
        <div class="modal fade" id="modalEditTarikTunai{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditTarikTunaiLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditTarikTunaiLabel{{ $item->id }}">
                            Edit Tarik Tunai</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('brilink.tarik-tunai.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="kode_transaksi">Kode Transaksi</label>
                                <input type="text" class="form-control" id="kode_transaksi" name="kode_transaksi"
                                    value="{{ $item->kode_transaksi }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="{{ $item->nama }}" required>
                            </div>
                            <div class="form-group">
                                <label for="norek">No Rekening</label>
                                <input type="text" class="form-control" id="norek" name="norek"
                                    value="{{ $item->norek }}">
                            </div>
                            <div class="form-group">
                                <label for="norek_tujuan">No Rekening Tujuan</label>
                                <input type="text" class="form-control" id="norek_tujuan" name="norek_tujuan"
                                    value="{{ $item->norek_tujuan }}">
                            </div>
                            <div class="form-group">
                                <label for="nominal">Nominal</label>
                                <input type="number" class="form-control" id="nominal" name="nominal"
                                    value="{{ $item->nominal }}" required>
                            </div>
                            <div class="form-group">
                                <label for="tgl_tarik_tunai">Tanggal</label>
                                <input type="date" class="form-control" id="tgl_tarik_tunai" name="tgl_tarik_tunai"
                                    value="{{ $item->tgl_tarik_tunai }}" required>
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

    <!-- Modal Tambah Tarik Tunai -->
    <div class="modal fade" id="modalTambahTarikTunai" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahTarikTunaiLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahTarikTunaiLabel">Tambah Tarik Tunai</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('brilink.tarik-tunai.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kode_transaksi">Kode Transaksi</label>
                            <input type="text" class="form-control" id="kode_transaksi" name="kode_transaksi"
                                value="{{ 'TRK-' . date('YmdHis') }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukkan nama" required>
                        </div>
                        <div class="form-group">
                            <label for="norek">No Rekening</label>
                            <input type="text" class="form-control" id="norek" name="norek"
                                placeholder="Masukkan nomor rekening (opsional)">
                        </div>
                        <div class="form-group">
                            <label for="norek_tujuan">No Rekening Tujuan</label>
                            <input type="text" class="form-control" id="norek_tujuan" name="norek_tujuan"
                                placeholder="Masukkan nomor rekening tujuan (opsional)">
                        </div>
                        <div class="form-group">
                            <label for="nominal">Nominal</label>
                            <input type="number" class="form-control" id="nominal" name="nominal"
                                placeholder="Masukkan nominal" required>
                        </div>
                        <div class="form-group">
                            <label for="tgl_tarik_tunai">Tanggal</label>
                            <input type="date" class="form-control" id="tgl_tarik_tunai" name="tgl_tarik_tunai"
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
            $('#tabelTarikTunai').DataTable();
        });
    </script>
@endpush
