@extends('layouts.app', ['title' => 'Dana Masuk', 'activePage' => 'Dana Masuk'])
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Data Dana Masuk</h3>
                        </div>
                        <div class="col-right">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addUsaha">
                                <ion-icon name="add"></ion-icon> Dana Masuk
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sumber Dana</th>
                                <th>Jumlah Dana</th>
                                <th>Tanggal</th>
                                <th>created_at</th>
                                <th>updated_at</th>
                                <th>
                                    <ion-icon name="settings"></ion-icon>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($income as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->sumber_dana }}</td>
                                    <td>{{ $item->nominal }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <x-action-button edit="edit-{{ $item->id }}" view="detail-{{ $item->id }}"
                                            delete="{{ route('income.destroy', $item->id) }}" />
                                        <x-modal-form id="edit-{{ $item->id }}" title="Edit Dana Masuk"
                                            action="{{ route('income.update', $item->id) }}">
                                            <div class="form-group">
                                                <label for="sumber_dana">Sumber Dana</label>
                                                <select name="sumber_dana" id="sumber_dana"
                                                    class="form-control @error('sumber_dana') is-invalid @enderror">
                                                    <option value="" disabled>Pilih Sumber Dana</option>
                                                    <option value="APB DESA"
                                                        {{ (old('sumber_dana') ?? $item->sumber_dana) == 'APB DESA' ? 'selected' : '' }}>
                                                        APB DESA</option>
                                                    <option value="BANK"
                                                        {{ (old('sumber_dana') ?? $item->sumber_dana) == 'BANK' ? 'selected' : '' }}>
                                                        BANK</option>
                                                    <option value="PEMERINTAH PROVINSI"
                                                        {{ (old('sumber_dana') ?? $item->sumber_dana) == 'PEMERINTAH PROVINSI' ? 'selected' : '' }}>
                                                        PEMERINTAH PROVINSI</option>
                                                    <option value="PEMERINTAH KOTA"
                                                        {{ (old('sumber_dana') ?? $item->sumber_dana) == 'PEMERINTAH KOTA' ? 'selected' : '' }}>
                                                        PEMERINTAH KOTA</option>
                                                    <option value="PIHAK KETIGA"
                                                        {{ (old('sumber_dana') ?? $item->sumber_dana) == 'PIHAK KETIGA' ? 'selected' : '' }}>
                                                        PIHAK KETIGA</option>
                                                    <option value="LAIN-LAIN"
                                                        {{ (old('sumber_dana') ?? $item->sumber_dana) == 'LAIN-LAIN' ? 'selected' : '' }}>
                                                        LAIN-LAIN</option>
                                                </select>
                                                @error('sumber_dana')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="nominal">Nominal</label>
                                                <input type="number" name="nominal" id="nominal"
                                                    class="form-control @error('nominal') is-invalid @enderror"
                                                    placeholder="Nominal"
                                                    value="{{ old('nominal') ?? $item->originalNominal }}">
                                                @error('nominal')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="tanggal">Tanggal</label>
                                                <input type="date" name="tanggal" id="tanggal"
                                                    class="form-control @error('tanggal') is-invalid @enderror"
                                                    placeholder="tanggal"
                                                    value="{{ old('tanggal') ?? $item->originalTanggal }}">
                                                @error('tanggal')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </x-modal-form>
                                        <x-modal-default id="detail-{{ $item->id }}" title="Detail Badan Usaha">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tr>
                                                        <th>Sumber Dana</th>
                                                        <td>{{ $item->sumber_dana }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Nominal</th>
                                                        <td>{{ $item->nominal }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <td>{{ $item->tanggal }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>created_at</th>
                                                        <td>{{ $item->created_at }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>updated_at</th>
                                                        <td>{{ $item->updated_at }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </x-modal-default>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <x-modal-form id="addUsaha" title="Tambah Dana Masuk" action="{{ route('income.store') }}">
        @if (Auth::user()->role == 'admin')
            <div class="form-group">
                <label for="sumber_dana">Sumber Dana</label>
                <select name="sumber_dana" id="sumber_dana" class="form-control @error('sumber_dana') is-invalid @enderror">
                    <option value="" disabled selected>Pilih Sumber Dana</option>
                    <option value="APB DESA" {{ old('sumber_dana') == 'APB DESA' ? 'selected' : '' }}>APB DESA</option>
                    <option value="BANK" {{ old('sumber_dana') == 'BANK' ? 'selected' : '' }}>BANK</option>
                    <option value="PEMERINTAH PROVINSI"
                        {{ old('sumber_dana') == 'PEMERINTAH PROVINSI' ? 'selected' : '' }}>PEMERINTAH PROVINSI</option>
                    <option value="PEMERINTAH KOTA" {{ old('sumber_dana') == 'PEMERINTAH KOTA' ? 'selected' : '' }}>
                        PEMERINTAH KOTA</option>
                    <option value="PIHAK KETIGA" {{ old('sumber_dana') == 'PIHAK KETIGA' ? 'selected' : '' }}>PIHAK KETIGA
                    </option>
                    <option value="LAIN-LAIN" {{ old('sumber_dana') == 'LAIN-LAIN' ? 'selected' : '' }}>LAIN-LAIN</option>
                </select>
                @error('sumber_dana')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        @else
            <input type="hidden" name="badan_usaha_id" value="{{ $usaha->id }}">
        @endif
        <div class="form-group">
            <label for="nominal">Nominal</label>
            <input type="number" name="nominal" id="nominal" class="form-control @error('nominal') is-invalid @enderror"
                placeholder="Nominal" value="{{ old('nominal') }}">
            @error('nominal')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                placeholder="tanggal" value="{{ old('tanggal') }}">
            @error('tanggal')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </x-modal-form>
@endsection
@push('js')
    <script>
        $(function() {
            $("#example1").DataTable();
        });
    </script>
@endpush
