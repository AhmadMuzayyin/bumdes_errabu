@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Nasabah</h3>
                    <div class="card-tools">
                        <a href="{{ route('nasabah.index') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('nasabah.update', $nasabah->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama">Nama Nasabah <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $nasabah->nama) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat <span class="text-danger">*</span></label>
                            <textarea name="alamat" id="alamat" class="form-control" rows="3" required>{{ old('alamat', $nasabah->alamat) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="telepon">Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="telepon" id="telepon" class="form-control" value="{{ old('telepon', $nasabah->telepon) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="tgl_bergabung">Tanggal Bergabung <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_bergabung" id="tgl_bergabung" class="form-control" value="{{ old('tgl_bergabung', $nasabah->tgl_bergabung) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Perbarui</button>
                            <a href="{{ route('nasabah.index') }}" class="btn btn-default">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
