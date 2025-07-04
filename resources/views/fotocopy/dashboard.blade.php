@extends('layouts.app', ['title' => 'Dashboard Foto Copy'])
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h1>Selamat datang di Dashboard Operator Foto Copy</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($totalPembayaran, 0, ',', '.') }}</h3>
                    <p>Total Pembayaran</p>
                </div>
                <div class="icon">
                    <i class="ion ion-cash"></i>
                </div>
                <a href="{{ route('fotocopy.pembayaran.index') }}" class="small-box-footer">More info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                    <p>Total Pengeluaran</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('fotocopy.pengeluaran.index') }}" class="small-box-footer">More info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totalPembayaran - $totalPengeluaran, 0, ',', '.') }}</h3>
                    <p>Saldo</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{ route('fotocopy.laporan.index') }}" class="small-box-footer">More info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Pembayaran</h3>
                </div>
                <div class="card-body">
                    <canvas id="pembayaranChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Pengeluaran</h3>
                </div>
                <div class="card-body">
                    <canvas id="pengeluaranChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pembayaran Terakhir</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pembayaranTerakhir as $pembayaran)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $pembayaran->tanggal }}</td>
                                    <td>{{ number_format($pembayaran->nominal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pengeluaran Terakhir</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pengeluaranTerakhir as $pengeluaran)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $pengeluaran->tanggal }}</td>
                                    <td>{{ number_format($pengeluaran->nominal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Data untuk grafik pembayaran
        var pembayaranData = @json($pembayaranPerBulan);
        var pembayaranLabels = pembayaranData.map(item => item.month);
        var pembayaranValues = pembayaranData.map(item => item.total);

        // Data untuk grafik pengeluaran
        var pengeluaranData = @json($pengeluaranPerBulan);
        var pengeluaranLabels = pengeluaranData.map(item => item.month);
        var pengeluaranValues = pengeluaranData.map(item => item.total);

        // Grafik pembayaran
        var pembayaranCtx = document.getElementById('pembayaranChart').getContext('2d');
        var pembayaranChart = new Chart(pembayaranCtx, {
            type: 'line',
            data: {
                labels: pembayaranLabels,
                datasets: [{
                    label: 'Total Pembayaran',
                    data: pembayaranValues,
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
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

        // Grafik pengeluaran
        var pengeluaranCtx = document.getElementById('pengeluaranChart').getContext('2d');
        var pengeluaranChart = new Chart(pengeluaranCtx, {
            type: 'line',
            data: {
                labels: pengeluaranLabels,
                datasets: [{
                    label: 'Total Pengeluaran',
                    data: pengeluaranValues,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
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
@endpush
