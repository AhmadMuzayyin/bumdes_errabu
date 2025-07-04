@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pengambilan Simpanan</h3>
                    <div class="card-tools">
                        <a href="{{ route('pengambilan-simpanan.index') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Nama Nasabah</th>
                                <td>{{ $pengambilan->nasabah->nama }}</td>
                            </tr>
                            <tr>
                                <th>Nominal</th>
                                <td>{{ $pengambilan->nominal }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td>{{ $pengambilan->tgl_pengambilan }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('pengambilan-simpanan.edit', $pengambilan->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('pengambilan-simpanan.destroy', $pengambilan->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data pengambilan simpanan ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
