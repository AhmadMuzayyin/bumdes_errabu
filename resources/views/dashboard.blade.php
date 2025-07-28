@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h1>Selamat datang {{ Auth::user()->name }}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if (auth()->user()->role == 'admin')
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <canvas id="incomes" width="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <canvas id="spending"></canvas>
                    </div>
                </div>
            </div>
        @else
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <canvas id="incomes" width="100"></canvas>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h3>Dana Masuk</h3>
                    <div class="table-responsive">
                        <table class="table table-striped" id="incomesTable">
                            <thead>
                                <th>#</th>
                                @if (auth()->user()->role == 'admin')
                                    <th>Sumber Dana</th>
                                @endif
                                <th>Nominal</th>
                                <th>Tanggal</th>
                                @if (auth()->user()->role != 'admin')
                                    <th>Status</th>
                                @endif
                            </thead>
                            <tbody>
                                @foreach ($manual_income as $pemasukan)
                                    <tr>
                                        @if (auth()->user()->role == 'admin')
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pemasukan->sumber_dana }}</td>
                                            <td>{{ $pemasukan->nominal }}</td>
                                            <td>{{ date('d F Y', strtotime($pemasukan->tanggal)) }}</td>
                                        @else
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pemasukan->nominal }}</td>
                                            <td>{{ date('d F Y', strtotime($pemasukan->tanggal)) }}</td>
                                            <td>
                                                <ion-icon name="checkmark-circle-outline"
                                                    class="alert alert-success"></ion-icon>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if (auth()->user()->role == 'admin')
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h3>Dana Keluar</h3>
                        <div class="table-responsive">
                            <table class="table table-striped" id="spendingsTable">
                                <thead>
                                    <th>#</th>
                                    <th>Tujuan</th>
                                    <th>Nominal</th>
                                    <th>Tanggal</th>
                                </thead>
                                <tbody>
                                    @foreach ($manual_spending as $pengeluaran)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pengeluaran->tujuan }}</td>
                                            <td>{{ $pengeluaran->nominal }}</td>
                                            <td>{{ date('d F Y', strtotime($pengeluaran->tanggal)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@push('js')
    <script>
        $(function() {
            $("#incomesTable").DataTable();
            $("#spendingsTable").DataTable();
        });
        var data = {!! json_encode($income) !!}
        var labels = data.map(item => item.bulan)
        var values = data.map(item => item.nominal)
        var incomectx = document.getElementById('incomes').getContext('2d');
        var income = new Chart(incomectx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Dana Masuk',
                    data: values,
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });
    </script>
    @if (auth()->user()->role == 'admin')
        <script>
            var data = {!! json_encode($spending) !!}
            var labels = data.map(item => item.bulan)
            var values = data.map(item => item.nominal)
            var spendingctx = document.getElementById('spending').getContext('2d');
            var spending = new Chart(spendingctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Dana Keluar',
                        data: values,
                        backgroundColor: 'rgb(255,66,100,0.5)',
                        borderColor: 'rgb(255,66,106,1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endif
@endpush
