@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Pengeluaran</h3>
                <div class="card-tools">
                    <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('pengeluaran.edit', $pengeluaran->id) }}" class="btn btn-warning btn-sm ml-1">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Tanggal</th>
                        <td>{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pengeluaran</th>
                        <td>{{ $pengeluaran->tujuan }}</td>
                    </tr>
                    <tr>
                        <th>Nominal</th>
                        <td>{{ $pengeluaran->jumlah }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Pengeluaran</th>
                        <td>{{ \Carbon\Carbon::parse($pengeluaran->tgl_pengeluaran)->format('d F Y') }}</td>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $pengeluaran->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Diperbarui Pada</th>
                        <td>{{ $pengeluaran->updated_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <form action="{{ route('pengeluaran.destroy', $pengeluaran->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data pengeluaran ini?')">
                        <i class="fas fa-trash"></i> Hapus Pengeluaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
