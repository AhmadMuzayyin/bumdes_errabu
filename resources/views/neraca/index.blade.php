@extends('layouts.app', ['title' => 'Neraca Keuangan', 'activePage' => 'Neraca Keuangan'])
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Neraca Keuangan</h3>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Uraian</th>
                                <th>Pemasukan (Rp)</th>
                                <th>Pengeluaran (Rp)</th>
                                <th>Saldo (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($neraca as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->uraian }}</td>
                                    <td>Rp.{{ number_format($item->pemasukan, 0, ',', '.') }}</td>
                                    <td>Rp.{{ number_format($item->pengeluaran, 0, ',', '.') }}</td>
                                    <td>Rp.{{ number_format($item->saldo, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(function() {
            $("#example1").DataTable();
        });
    </script>
@endpush
