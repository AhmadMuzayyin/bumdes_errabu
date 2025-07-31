@extends('layouts.app', ['title' => 'Pembayaran Foto Copy'])
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Daftar Pembayaran Foto Copy</h3>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahPembayaran">
                            <i class="fas fa-plus"></i> Tambah Pembayaran
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kertas</th>
                                    <th>Jumlah</th>
                                    <th>Total Pembayaran</th>
                                    <th>Tanggal</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pembayaran as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->harga_foto_copy->nama }} - {{ $item->harga_foto_copy->harga }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>Rp. {{ number_format($item->total_pembayaran, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_pembayaran)->format('d/m/Y') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                                data-target="#modalDetailPembayaran{{ $item->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                                data-target="#modalEditPembayaran{{ $item->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('fotocopy.pembayaran.destroy', $item->id) }}"
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
    @foreach ($pembayaran as $item)
        <!-- Modal Detail Pembayaran -->
        <div class="modal fade" id="modalDetailPembayaran{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modalDetailPembayaranLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetailPembayaranLabel{{ $item->id }}">
                            Detail Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Jumlah</th>
                                    <td>{{ $item->jumlah }}</td>
                                </tr>
                                <tr>
                                    <th>Total Pembayaran</th>
                                    <td>{{ number_format($item->total_pembayaran, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>{{ \Carbon\Carbon::parse($item->tgl_pembayaran)->format('d/m/Y') }}
                                    </td>
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

        <!-- Modal Edit Pembayaran -->
        <div class="modal fade" id="modalEditPembayaran{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditPembayaranLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditPembayaranLabel{{ $item->id }}">
                            Edit Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('fotocopy.pembayaran.update', $item->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="jenis_kertas" id="jenis_kertas{{ $item->id }}"
                            value="{{ $item->harga_foto_copy->OriginalHarga }}">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="jumlah">Jumlah</label>
                                <input type="number" class="form-control" id="jumlah{{ $item->id }}" name="jumlah"
                                    value="{{ $item->jumlah }}" required>
                            </div>
                            <div class="form-group">
                                <label for="total_pembayaran">Total Pembayaran</label>
                                <input type="number" class="form-control" id="total_pembayaran{{ $item->id }}"
                                    name="total_pembayaran" value="{{ $item->total_pembayaran }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="tgl_pembayaran">Tanggal</label>
                                <input type="date" class="form-control" id="tgl_pembayaran" name="tgl_pembayaran"
                                    value="{{ $item->tgl_pembayaran }}" required>
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
        @push('js')
            <script>
                $(document).ready(function() {
                    $("#modalEditPembayaran{{ $item->id }}").on('shown.bs.modal', function() {
                        $('#jumlah{{ $item->id }}').focus();
                        $('#jumlah{{ $item->id }}').on('change', function() {
                            var harga = $('#jenis_kertas{{ $item->id }}').val();
                            var jumlah = $('#jumlah{{ $item->id }}').val();
                            if (harga && jumlah) {
                                var total = parseInt(harga) * parseInt(jumlah);
                                $('#total_pembayaran{{ $item->id }}').val(total);
                            } else {
                                $('#total_pembayaran{{ $item->id }}').val('');
                            }
                        });
                    });
                });
            </script>
        @endpush
    @endforeach

    <!-- Modal Tambah Pembayaran -->
    <div class="modal fade" id="modalTambahPembayaran" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahPembayaranLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahPembayaranLabel">Tambah Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('fotocopy.pembayaran.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="jenis_kertas_add">Jenis Kertas</label>
                            <select name="jenis_kertas" id="jenis_kertas_add" class="form-control" required>
                                <option value="" disabled selected>Pilih Jenis Kertas</option>
                                @foreach ($kertas as $item)
                                    <option value="{{ $item->id }}" data-harga="{{ $item->originalHarga }}">
                                        {{ $item->nama }}-{{ $item->harga }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_add">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah_add" name="jumlah"
                                placeholder="Masukkan jumlah" required>
                        </div>
                        <div class="form-group">
                            <label for="total_pembayaran_add">Total Pembayaran</label>
                            <input type="number" class="form-control" id="total_pembayaran_add" name="total_pembayaran"
                                placeholder="Masukkan total pembayaran" readonly>
                        </div>
                        <div class="form-group">
                            <label for="tgl_pembayaran">Tanggal</label>
                            <input type="date" class="form-control" id="tgl_pembayaran" name="tgl_pembayaran"
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
            $('#modalTambahPembayaran').on('shown.bs.modal', function() {
                $('#jenis_kertas_add, #jumlah_add').on('change', function() {
                    var harga = $('#jenis_kertas_add option:selected').data('harga');
                    var jumlah = $('#jumlah_add').val();
                    if (harga && jumlah) {
                        var total = parseInt(harga) * parseInt(jumlah);
                        $('#total_pembayaran_add').val(total);
                    } else {
                        $('#total_pembayaran_add').val('');
                    }
                });
            });
        });
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@endpush
