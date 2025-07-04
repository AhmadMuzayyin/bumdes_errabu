@extends('layouts.app', ['title' => 'Operator', 'activePage' => 'Operator'])
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Data Operator</h3>
                        </div>
                        <div class="col-right">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addOperator">
                                <ion-icon name="add"></ion-icon> Operator
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
                                <th>Email</th>
                                <th>Level</th>
                                <th>created_at</th>
                                <th>updated_at</th>
                                <th>
                                    <ion-icon name="settings"></ion-icon>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($operator as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ ucfirst($item->role) }}</td>
                                    <td>{{ date('d F-Y', strtotime($item->created_at)) }}</td>
                                    <td>{{ date('d F-Y', strtotime($item->updated_at)) }}</td>
                                    <td>
                                        <x-action-button edit="edit-{{ $item->id }}"
                                            delete="{{ route('operator.destroy', $item->id) }}" />
                                        <x-modal-form id="edit-{{ $item->id }}" title="Edit Operator"
                                            action="{{ route('operator.update', $item->id) }}">
                                            <div class="form-group">
                                                <label for="name">Nama</label>
                                                <input name="name" id="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    placeholder="Nama Operator" value="{{ old('name') ?? $item->nama }}">
                                                @error('name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" name="email" id="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    placeholder="Email Operator"
                                                    value="{{ old('email') ?? $item->email }}">
                                                @error('email')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" name="password" id="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    placeholder="Password Operator"
                                                    value="{{ old('password') }}">
                                                @error('password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="password_confirmation">Password Confirmation</label>
                                                <input type="password" name="password_confirmation"
                                                    id="password_confirmation" class="form-control"
                                                    placeholder="Password Operator"
                                                    value="{{ old('password_confirmation') }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="role">Role</label>
                                                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                                                    <option value="operator simpan pinjam" {{ $item->role == 'operator simpan pinjam' ? 'selected' : '' }}>Operator Simpan Pinjam</option>
                                                    <option value="operator foto copy" {{ $item->role == 'operator foto copy' ? 'selected' : '' }}>Operator Foto Copy</option>
                                                    <option value="operator brilink" {{ $item->role == 'operator brilink' ? 'selected' : '' }}>Operator BRILink</option>
                                                </select>
                                                @error('role')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <p class="text-danger text-sm">
                                                *Tulis password lama jika tidak ingin mengubah password, tulis password baru jika ingin mengubah password
                                            </p>
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
    <x-modal-form id="addOperator" title="Tambah Operator" action="{{ route('operator.store') }}">
        <div class="form-group">
            <label for="name">Nama</label>
            <input name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                placeholder="Nama Operator" value="{{ old('name') }}">
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                placeholder="Email Operator" value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password"
                class="form-control @error('password') is-invalid @enderror" placeholder="Password Operator"
                value="{{ old('password') }}">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation">Password Confirmation</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="form-control" placeholder="Password Operator">
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                <option value="operator simpan pinjam">Operator Simpan Pinjam</option>
                <option value="operator foto copy">Operator Foto Copy</option>
                <option value="operator brilink">Operator BRILink</option>
            </select>
            @error('role')
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
