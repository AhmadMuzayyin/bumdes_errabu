@extends('layouts.app', ['title' => 'Setting Pinjaman'])

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pengaturan Bunga Pinjaman</h3>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('setting-pinjaman.update', $setting->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="bunga">Persentase Bunga Pinjaman (%)</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('bunga') is-invalid @enderror" id="bunga" name="bunga" 
                                value="{{ $setting->original_bunga }}" min="0" max="100" step="0.1">
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                            @error('bunga')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">
                            Masukkan persentase bunga untuk pinjaman. Nilai saat ini: <strong>{{ $setting->bunga }}</strong>
                        </small>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Bunga Pinjaman</h3>
            </div>
            <div class="card-body">
                <p>Pengaturan bunga pinjaman akan berlaku untuk semua transaksi pinjaman baru. Perubahan pada persentase 
                bunga tidak akan memengaruhi pinjaman yang sudah ada sebelumnya.</p>
                
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Cara penghitungan bunga:</h5>
                    <p>Bunga = Jumlah Pinjaman × {{ $setting->bunga }} ÷ 100</p>
                    <p>Contoh: Untuk pinjaman sebesar Rp 1.000.000 dengan bunga {{ $setting->bunga }}, maka:</p>
                    <p>Bunga = Rp 1.000.000 × {{ $setting->original_bunga }} ÷ 100 = Rp {{ number_format(1000000 * $setting->original_bunga / 100, 0, ',', '.') }}</p>
                    <p>Total yang harus dikembalikan = Rp 1.000.000 + Rp {{ number_format(1000000 * $setting->original_bunga / 100, 0, ',', '.') }} = 
                        <strong>Rp {{ number_format(1000000 + (1000000 * $setting->original_bunga / 100), 0, ',', '.') }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
