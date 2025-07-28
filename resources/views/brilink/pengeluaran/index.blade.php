@extends('layouts.app', ['title' => 'Pengeluaran BRI Link'])
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Daftar Pengeluaran BRI Link</h3>
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahPengeluaran">
                            <i class="fas fa-plus"></i> Tambah Pengeluaran
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabelPengeluaran">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Tanggal</th>
                                    <th>Tujuan</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($spendings as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_pengeluaran)->format('d F Y') }}</td>
                                        <td>{{ $item->tujuan }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                                data-target="#modalDetailPengeluaran{{ $item->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                                data-target="#modalEditPengeluaran{{ $item->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('brilink.dana-keluar.destroy', $item->id) }}"
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
    @foreach ($spendings as $item)
        <!-- Modal Detail Pengeluaran -->
        <div class="modal fade" id="modalDetailPengeluaran{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modalDetailPengeluaranLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetailPengeluaranLabel{{ $item->id }}">
                            Detail Pengeluaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Kode</th>
                                    <td>{{ $item->kode }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Pengeluaran</th>
                                    <td>{{ $item->jenis_pengeluaran }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah</th>
                                    <td>{{ $item->jumlah }}</td>
                                </tr>
                                <tr>
                                    <th>Harga</th>
                                    <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>Rp. {{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ \Carbon\Carbon::parse($item->tgl_pengeluaran)->format('d F Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tujuan</th>
                                    <td>{{ $item->tujuan }}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat pada</th>
                                    <td>{{ $item->created_at->format('d F Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Diupdate pada</th>
                                    <td>{{ $item->updated_at->format('d F Y H:i') }}</td>
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

        <!-- Modal Edit Pengeluaran -->
        <div class="modal fade" id="modalEditPengeluaran{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditPengeluaranLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditPengeluaranLabel{{ $item->id }}">
                            Edit Pengeluaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('brilink.dana-keluar.update', $item->id) }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="kode">Kode</label>
                                <input type="text" class="form-control" id="kode" name="kode"
                                    value="{{ $item->kode }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="jenis_pengeluaran">Jenis Pengeluaran</label>
                                <select class="form-control @error('jenis_pengeluaran') is-invalid @enderror"
                                    aria-describedby="jenisPengeluaranHelp" id="jenis_pengeluaran" name="jenis_pengeluaran"
                                    required>
                                    <option value="" disabled>Pilih Jenis Pengeluaran</option>
                                    <option value="Belanja Rutin"
                                        {{ $item->jenis_pengeluaran == 'Belanja Rutin' ? 'selected' : '' }}>Belanja Rutin
                                    </option>
                                    <option value="Gaji Karyawan"
                                        {{ $item->jenis_pengeluaran == 'Gaji Karyawan' ? 'selected' : '' }}>Gaji Karyawan
                                    </option>
                                    <option value="Setor Pengahsilan"
                                        {{ $item->jenis_pengeluaran == 'Setor Pengahsilan' ? 'selected' : '' }}>Setor
                                        Pengahsilan</option>
                                    <option value="Lainnya" {{ $item->jenis_pengeluaran == 'Lainnya' ? 'selected' : '' }}>
                                        Lainnya</option>
                                </select>
                                @error('jenis_pengeluaran')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="jumlah">Jumlah</label>
                                <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                                    id="jumlah" name="jumlah" value="{{ $item->jumlah }}" required>
                                @error('jumlah')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                    id="harga" name="harga" value="{{ $item->harga }}" required>
                                @error('harga')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <input type="hidden" name="old_jumlah" value="{{ $item->jumlah }}">
                            <input type="hidden" name="old_harga" value="{{ $item->harga }}">
                            <div class="form-group">
                                <label for="tgl_pengeluaran">Tanggal</label>
                                <input type="date" class="form-control @error('tgl_pengeluaran') is-invalid @enderror"
                                    id="tgl_pengeluaran" name="tgl_pengeluaran" value="{{ $item->tgl_pengeluaran }}"
                                    required>
                                @error('tgl_pengeluaran')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="tujuan">Tujuan</label>
                                <textarea class="form-control @error('tujuan') is-invalid @enderror" id="tujuan" name="tujuan" rows="3">{{ $item->tujuan }}</textarea>
                                @error('tujuan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
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

    <!-- Modal Tambah Pengeluaran -->
    <div class="modal fade" id="modalTambahPengeluaran" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahPengeluaranLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahPengeluaranLabel">Tambah Pengeluaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('brilink.dana-keluar.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kode">Kode</label>
                            <input type="text" class="form-control" id="kode" name="kode"
                                value="{{ 'BRI-' . date('YmdHis') }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="jenis_pengeluaran">Jenis Pengeluaran</label>
                            <select class="form-control" id="jenis_pengeluaran" name="jenis_pengeluaran" required>
                                <option value="" selected disabled>Pilih Jenis Pengeluaran</option>
                                <option value="Belanja Rutin">Belanja Rutin</option>
                                <option value="Gaji Karyawan">Gaji Karyawan</option>
                                <option value="Setor Pengahsilan">Setor Pengahsilan</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah"
                                placeholder="Masukkan jumlah" required>
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga"
                                placeholder="Masukkan harga" required>
                        </div>
                        <div class="form-group">
                            <label for="tgl_pengeluaran">Tanggal</label>
                            <input type="date" class="form-control" id="tgl_pengeluaran" name="tgl_pengeluaran"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="tujuan">Tujuan</label>
                            <textarea class="form-control" id="tujuan" name="tujuan" rows="3" placeholder="Masukkan tujuan"></textarea>
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
            $('#tabelPengeluaran').DataTable();
        });
    </script>
@endpush
