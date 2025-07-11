@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Rekap Simpanan Nasabah</h3>
                <div class="card-tools">
                    <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-default">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.rekap-simpanan') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="tipe_laporan">Format Laporan</label>
                        <select class="form-control" id="tipe_laporan" name="tipe_laporan">
                            <option value="web">Tampilkan di Web</option>
                            <option value="pdf">Download PDF</option>
                        </select>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Lihat Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
