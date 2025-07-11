@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Simpanan</h3>
                    <div class="card-tools">
                        <a href="{{ route('simpanan.index') }}" class="btn btn-warning btn-sm">
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
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    <form action="{{ route('simpanan.update', $simpanan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nasabah_id">Nasabah</label>
                            <select name="nasabah_id" id="nasabah_id" class="form-control select2" required>
                                <option value="">-- Pilih Nasabah --</option>
                                @foreach($nasabah as $item)
                                    <option value="{{ $item->id }}" {{ old('nasabah_id', $simpanan->nasabah_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nominal">Nominal (Rp)</label>
                            <input type="number" name="nominal" id="nominal" class="form-control" value="{{ old('nominal', $simpanan->original_nominal) }}" required min="0">
                            <input type="hidden" name="original_nominal" value="{{ $simpanan->original_nominal }}">
                        </div>
                        <div class="form-group">
                            <label for="tgl_simpan">Tanggal</label>
                            <input type="date" name="tgl_simpan" id="tgl_simpan" class="form-control" value="{{ old('tgl_simpan', $simpanan->tgl_simpan) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Perbarui</button>
                            <a href="{{ route('simpanan.index') }}" class="btn btn-default">Batal</a>
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
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    });
</script>
@endsection
