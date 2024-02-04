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
                                <th>Nama Badan Usaha</th>
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
                                    <td>{{ $item->badan_usaha->nama }}</td>
                                    <td>{{ $item->nominal }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <x-action-button edit="edit-{{ $item->id }}" view="detail-{{ $item->id }}"
                                            delete="{{ route('income.destroy', $item->id) }}" />
                                        <x-modal-form id="edit-{{ $item->id }}" title="Edit Dana Masuk"
                                            action="{{ route('income.update', $item->id) }}">
                                            @if (Auth::user()->role == 'admin')
                                                <div class="form-group">
                                                    <label for="badan_usaha_id">Badan Usaha</label>
                                                    <select name="badan_usaha_id" id="badan_usaha_id"
                                                        class="form-control @error('badan_usaha_id') is-invalid @enderror"
                                                        value="{{ old('badan_usaha_id') }}">
                                                        <option value="">-- Pilih Badan Usaha --</option>
                                                        @foreach ($usaha as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ $val->id == $item->badan_usaha_id ? 'selected' : '' }}>
                                                                {{ $val->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('badan_usaha_id')
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
                                                        <th>Nama Badan Usaha</th>
                                                        <td>{{ $item->badan_usaha->nama }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Operator</th>
                                                        <td>{{ $item->badan_usaha->user->nama }}</td>
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
                <label for="badan_usaha_id">Badan Usaha</label>
                <select name="badan_usaha_id" id="badan_usaha_id"
                    class="form-control @error('badan_usaha_id') is-invalid @enderror" value="{{ old('badan_usaha_id') }}">
                    <option value="">-- Pilih Badan Usaha --</option>
                    @foreach ($usaha as $val)
                        <option value="{{ $val->id }}">{{ $val->nama }}</option>
                    @endforeach
                </select>
                @error('badan_usaha_id')
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
