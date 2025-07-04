@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pengembalian Pinjaman</h3>
                    <div class="card-tools">
                        <a href="{{ route('pengembalian-pinjaman.index') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Informasi Nasabah</span>
                                    <span class="info-box-number">Nama: {{ $pengembalian->pinjaman->nasabah->nama }}</span>
                                    <span class="info-box-number">Telepon: {{ $pengembalian->pinjaman->nasabah->telepon }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-money-bill"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Informasi Cicilan</span>
                                    <span class="info-box-number">Nominal Cicilan: {{ $pengembalian->nominal_cicilan }}</span>
                                    <span class="info-box-number">Tanggal Pembayaran: {{ $pengembalian->tgl_pengembalian_sementara }}</span>
                                    <span class="info-box-number">Status: 
                                        @if($pengembalian->status == 'Lunas')
                                            <span class="badge badge-success">Lunas</span>
                                        @else
                                            <span class="badge badge-warning">Belum Lunas</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-file-invoice-dollar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Informasi Pinjaman</span>
                                    <span class="info-box-number">Nominal Pokok Pinjaman: {{ $pengembalian->pinjaman->nominal }}</span>
                                    <span class="info-box-number">Total Harus Dikembalikan: {{ $pengembalian->pinjaman->nominal_pengembalian ?? $pengembalian->pinjaman->nominal }}</span>
                                    <span class="info-box-number">Tanggal Pinjam: {{ $pengembalian->pinjaman->tgl_pinjam }}</span>
                                    <span class="info-box-number">Status Pinjaman: 
                                        @if($pengembalian->pinjaman->status == 'Lunas')
                                            <span class="badge badge-success">Lunas</span>
                                        @else
                                            <span class="badge badge-warning">Belum Lunas</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Riwayat Cicilan</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Nominal Cicilan</th>
                                            <th>Tanggal Pembayaran</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach($pengembalian->pinjaman->pengembalianPinjaman as $cicilan)
                                            <tr @if($cicilan->id == $pengembalian->id) class="table-primary" @endif>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $cicilan->nominal_cicilan }}</td>
                                                <td>{{ $cicilan->tgl_pengembalian_sementara }}</td>
                                                <td>
                                                    @if($cicilan->status == 'Lunas')
                                                        <span class="badge badge-success">Lunas</span>
                                                    @else
                                                        <span class="badge badge-warning">Belum Lunas</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-info">
                                            <th colspan="1">Total</th>
                                            <th colspan="3">
                                                @php
                                                    $totalCicilan = $pengembalian->pinjaman->pengembalianPinjaman->sum(function($item) {
                                                        return (float) str_replace(['Rp. ', '.'], '', $item->nominal_cicilan);
                                                    });
                                                    echo 'Rp. ' . number_format($totalCicilan, 0, ',', '.');
                                                @endphp
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="btn-group">
                                <a href="{{ route('pengembalian-pinjaman.edit', $pengembalian->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('pengembalian-pinjaman.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Cicilan Baru
                                </a>
                                <form action="{{ route('pengembalian-pinjaman.destroy', $pengembalian->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
