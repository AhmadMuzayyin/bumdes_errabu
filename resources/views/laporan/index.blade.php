@extends('layouts.app', ['title' => 'Laporan', 'activePage' => 'Laporan Keuangan'])
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Laporan Keuangan</h3>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <label for="start">Periode Mulai</label>
                            <input type="date" class="form-control" id="start" name="start"
                                value="{{ request()->get('start') ?? '' }}">
                        </div>
                        <div class="col-3">
                            <label for="end">Periode Akhir</label>
                            <input type="date" class="form-control" id="end"
                                name="end"value="{{ request()->get('end') ?? '' }}">
                        </div>
                        <div class="col-3">
                            <a role="button" href="{{ route('laporan.index') }}" class="btn btn-success btn-sm"
                                style="margin-top: 9%">
                                Refresh
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <h1 class="fw-bold mt-5 bg-primary">Dana Masuk</h1>
                            <div class="table-responsive mt-3">
                                <table class="table table-striped" id="danaMasuk">
                                    <thead>
                                        <th>#</th>
                                        <th>Badan Usaha</th>
                                        <th>Jumlah Dana Masuk</th>
                                        <th>Tanggal Masuk</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($dana_masuk as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->badan_usaha->nama }}</td>
                                                <td>{{ $item->nominal }}</td>
                                                <td>{{ date('d F Y', strtotime($item->tanggal)) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col">
                            <h1 class="fw-bold mt-5 bg-danger">Dana Keluar</h1>
                            <div class="table-responsive mt-3">
                                <table class="table table-striped" id="danaKeluar">
                                    <thead>
                                        <th>#</th>
                                        <th>Tujuan</th>
                                        <th>Jumlah Dana Keluar</th>
                                        <th>Tanggal Keluar</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($dana_keluar as $spending)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $spending->tujuan }}</td>
                                                <td>{{ $spending->nominal }}</td>
                                                <td>{{ date('d F Y', strtotime($spending->tanggal)) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(function() {
            $("#danaMasuk").DataTable();
            $("#danaKeluar").DataTable();
        });
        $(function() {
            $('#start').change(function() {
                var start = $(this).val();
                var end = $('#end').val();
                window.location.href = "{{ request()->segment(1) }}?start=" + start + "&end=" + end;
            })
            $('#end').change(function() {
                var start = $('#start').val();
                var end = $(this).val();
                window.location.href = "{{ request()->segment(1) }}?start=" + start + "&end=" + end;
            })
        })
    </script>
@endpush
