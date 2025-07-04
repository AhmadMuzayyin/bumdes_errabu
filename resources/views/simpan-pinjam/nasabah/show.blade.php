@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Nasabah</h3>
                    <div class="card-tools">
                        <a href="{{ route('nasabah.index') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Nama Nasabah</th>
                                <td>{{ $nasabah->nama }}</td>
                            </tr>
                            <tr>
                                <th>NIK</th>
                                <td>{{ $nasabah->nik }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $nasabah->alamat }}</td>
                            </tr>
                            <tr>
                                <th>No. HP</th>
                                <td>{{ $nasabah->no_hp }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>{{ ucfirst($nasabah->jenis_kelamin) }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('nasabah.edit', $nasabah->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('nasabah.destroy', $nasabah->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus nasabah ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
