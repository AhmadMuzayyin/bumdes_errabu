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
@endsection
@push('js')
    <script>
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
