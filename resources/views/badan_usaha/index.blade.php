@extends('layouts.app', ['title' => 'Badan Usaha', 'activePage' => 'Badan Usaha'])
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Data Badan Usaha</h3>
                        </div>
                        <div class="col-right">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addUsaha">
                                <ion-icon name="add"></ion-icon> Badan Usaha
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
                                <th>Nama</th>
                                <th>Operator</th>
                                <th>created_at</th>
                                <th>updated_at</th>
                                <th>
                                    <ion-icon name="settings"></ion-icon>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($badan_usaha as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                    <td>
                                        <x-action-button edit="edit-{{ $item->id }}" delete="{{ route('badan_usaha.destroy', $item->id) }}" />
                                            <x-modal-form id="edit-{{ $item->id }}" title="Edit Badan Usaha" action="{{ route('badan_usaha.update', $item->id) }}">
                                                <div class="form-group">
                                                    <label for="user_id">Operator</label>
                                                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id') }}">
                                                        <option value="">-- Pilih Operator --</option>
                                                        @foreach ($operator as $op)
                                                            <option value="{{ $op->id }}" {{ $item->user_id == $op->id ? 'selected' : '' }}>{{ $op->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('nama')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="nama">Nama</label>
                                                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') ?? $item->nama }}">
                                                    @error('nama')
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
    <x-modal-form id="addUsaha" title="Tambah Badan Usaha" action="{{ route('badan_usaha.store') }}">
        <div class="form-group">
            <label for="user_id">Operator</label>
            <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id') }}">
                <option value="">-- Pilih Operator --</option>
                @foreach ($operator as $op)
                    <option value="{{ $op->id }}">{{ $op->name }}</option>
                @endforeach
            </select>
            @error('nama')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Nama Badan Usaha" value="{{ old('nama') }}">
            @error('nama')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </x-modal-form>
@endsection
@push('js')
<script>
    $(function () {
    $("#example1").DataTable();
  });
  </script>
@endpush
