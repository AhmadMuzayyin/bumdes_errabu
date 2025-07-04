@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Pengeluaran</h3>
                <div class="card-tools">
                    <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('pengeluaran.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label for="kode" class="col-sm-3 col-form-label">Kode<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('kode') is-invalid @enderror" id="kode" 
                                name="kode" value="{{ old('kode', $kode ?? 'PNG-' . str_pad(time(), 5, '0', STR_PAD_LEFT)) }}" required readonly>
                            @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="tgl_pengeluaran" class="col-sm-3 col-form-label">Tanggal<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control @error('tgl_pengeluaran') is-invalid @enderror" id="tgl_pengeluaran" 
                                name="tgl_pengeluaran" value="{{ old('tgl_pengeluaran', date('Y-m-d')) }}" required>
                            @error('tgl_pengeluaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="tujuan" class="col-sm-3 col-form-label">Tujuan<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control @error('tujuan') is-invalid @enderror" id="tujuan" 
                                name="tujuan" value="{{ old('tujuan') }}" required>
                            @error('tujuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="jumlah" class="col-sm-3 col-form-label">Jumlah<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" 
                                    name="jumlah" value="{{ old('jumlah') }}" required>
                                @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="offset-sm-3 col-sm-9">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Format input jumlah dengan separator ribuan
        $('#jumlah').on('keyup', function() {
            let value = $(this).val();
            value = value.replace(/\D/g, "");
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            $(this).val(value);
        });
        
        // Sebelum submit, konversi jumlah ke format angka tanpa separator
        $('form').on('submit', function() {
            let jumlah = $('#jumlah').val();
            jumlah = jumlah.replace(/\./g, "");
            $('#jumlah').val(jumlah);
        });
    });
</script>
@endsection
