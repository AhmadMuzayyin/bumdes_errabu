@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pinjaman</h3>
                    <div class="card-tools">
                        <a href="{{ route('pinjaman.index') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Data Pinjaman</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Nama Nasabah</th>
                                        <td>{{ $pinjaman->nasabah->nama }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nominal Pinjaman</th>
                                        <td>{{ $pinjaman->nominal }}</td>
                                    </tr>
                                    <tr>
                                        <th>Bunga</th>
                                        <td>{{ $bunga }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Pinjaman</th>
                                        <td>Rp. {{ number_format($pinjaman->nominal_pengembalian, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Pinjam</th>
                                        <td>{{ $pinjaman->tgl_pinjam }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if ($pinjaman->status == 'Lunas')
                                                <span class="badge badge-success">Lunas</span>
                                            @else
                                                <span class="badge badge-warning">Belum Lunas</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5>Riwayat Pengembalian</h5>
                            @php
                                $totalPengembalian = $pinjaman->pengembalianPinjaman->sum('original_nominal_cicilan');
                                $sisaPinjaman = $pinjaman->nominal_pengembalian - $totalPengembalian;
                            @endphp
                            <div class="mb-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-money-bill"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Pengembalian</span>
                                        <span class="info-box-number">Rp
                                            {{ number_format($totalPengembalian, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-coins"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Sisa Pinjaman</span>
                                        <span class="info-box-number">Rp
                                            {{ number_format($sisaPinjaman, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            @if (count($pinjaman->pengembalianPinjaman) > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Nominal</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pinjaman->pengembalianPinjaman as $key => $pengembalian)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $pengembalian->tgl_pengembalian_sementara }}</td>
                                                    <td>{{ $pengembalian->nominal_cicilan }}
                                                    </td>
                                                    <td>{{ $pengembalian->keterangan ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    Belum ada catatan pengembalian untuk pinjaman ini.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('pinjaman.edit', $pinjaman->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('pinjaman.destroy', $pinjaman->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus data pinjaman ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                        <a href="{{ route('pengembalian-pinjaman.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Tambah Pengembalian
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
