@extends('layouts.app', ['title' => 'Dana Keluar', 'activePage' => 'Dana Keluar'])
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Data Dana Keluar</h3>
                        </div>
                        <div class="col-right">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addSpending">
                                <ion-icon name="add"></ion-icon> Dana Keluar
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
                                <th>Tujuan</th>
                                <th>Nominal</th>
                                <th>Tanggal</th>
                                <th>created_at</th>
                                <th>updated_at</th>
                                <th>
                                    <ion-icon name="settings"></ion-icon>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($spending as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->badan_usaha->nama }}</td>
                                    <td>{{ $item->nominal }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>
                                        <x-action-button edit="edit-{{ $item->id }}"
                                            delete="{{ route('spending.destroy', $item->id) }}" />
                                        <x-modal-form id="edit-{{ $item->id }}" title="Edit Dana Keluar"
                                            action="{{ route('spending.update', $item->id) }}">

                                            <div class="form-group">
                                                <label for="badan_usaha_id">Tujuan</label>
                                                <select name="badan_usaha_id" id="badan_usaha_id"
                                                    class="form-control @error('badan_usaha_id') is-invalid @enderror">
                                                    <option value="">-- Pilih Badan Usaha --</option>
                                                    @foreach ($bumdes as $val)
                                                        <option value="{{ $val->id }}"
                                                            {{ $item->badan_usaha_id == $val->id ? 'selected' : '' }}>
                                                            {{ $val->nama }}</option>
                                                    @endforeach
                                                </select>
                                                @error('badan_usaha_id')
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
                                                <label for="keterangan">Keterangan</label>
                                                <textarea name="keterangan" id="keterangan" rows="5"
                                                    class="form-control @error('keterangan') is-invalid @enderror" placeholder="Keterangan">{{ old('keterangan') ?? $item->keterangan }}</textarea>
                                                @error('keterangan')
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
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <x-modal-form id="addSpending" title="Tambah Dana Keluar" action="{{ route('spending.store') }}">
        <div class="form-group">
            <label for="badan_usaha_id">Badan Usaha Tujuan</label>
            <select name="badan_usaha_id" id="badan_usaha_id"
                class="form-control @error('badan_usaha_id') is-invalid @enderror">
                <option value="">-- Pilih Badan Usaha --</option>
                @foreach ($bumdes as $val)
                    <option value="{{ $val->id }}">{{ $val->nama }}</option>
                @endforeach
            </select>
            @error('badan_usaha_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
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
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="5"
                class="form-control @error('keterangan') is-invalid @enderror" placeholder="Keterangan">{{ old('keterangan') }}</textarea>
            @error('keterangan')
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
