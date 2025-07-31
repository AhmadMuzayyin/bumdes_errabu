@extends('layouts.app', ['title' => 'Laporan Simpan Pinjam'])

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Laporan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan.index') }}" method="GET" class="mb-0">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="start">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="start" name="start"
                                        value="{{ request('start', date('Y-m-01')) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="end">Tanggal Selesai</label>
                                    <input type="date" class="form-control" id="end" name="end"
                                        value="{{ request('end', date('Y-m-t')) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary mr-2">Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Simpanan</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nasabah</th>
                                    <th>Tanggal</th>
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($simpanan as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->nasabah->nama ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_simpan)->format('d/m/Y') }}</td>
                                        <td>{{ $item->nominal }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="3" class="text-right">Total Nominal</td>
                                    <td>Rp. {{ number_format($total_simpanan, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Pengambilan Simpanan</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nasabah</th>
                                    <th>Tanggal</th>
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengambilan as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->nasabah->nama ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_pengambilan)->format('d/m/Y') }}</td>
                                        <td>{{ $item->nominal }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="3" class="text-right">Total Nominal</td>
                                    <td>Rp. {{ number_format($total_pengambilan, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Pinjaman</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nasabah</th>
                                    <th>Tanggal</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pinjaman as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->nasabah->nama ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</td>
                                        <td>{{ $item->nominal }}</td>
                                        <td>{{ $item->status }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="4" class="text-right">Total Nominal</td>
                                    <td>Rp. {{ number_format($total_pinjaman, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Pengembalian Pinjaman</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pinjaman</th>
                                    <th>Tanggal</th>
                                    <th>Cicilan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengembalian as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->pinjaman->nasabah->nama ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_pengembalian_sementara)->format('d/m/Y') }}
                                        </td>
                                        <td>{{ $item->nominal_cicilan }}</td>
                                        <td>{{ $item->status }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="4" class="text-right">Total Nominal</td>
                                    <td>Rp. {{ number_format($total_pengembalian, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Pengeluaran</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Tujuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengeluaran as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_pengeluaran)->format('d/m/Y') }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>{{ $item->tujuan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="4" class="text-right">Total Nominal</td>
                                    <td>Rp. {{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
