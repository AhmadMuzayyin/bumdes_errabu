@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h1 class="mb-4">Dashboard Unit Simpan Pinjam</h1>
                    <div class="alert alert-info">
                        Selamat datang di Dashboard Unit Simpan Pinjam BUMDes Errabu
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Nasabah</span>
                    <span class="info-box-number">{{ $totalNasabah }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-wallet"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Saldo Simpanan</span>
                    <span class="info-box-number">Rp {{ number_format($saldoSimpanan, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-hand-holding-usd"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Pinjaman</span>
                    <span class="info-box-number">Rp {{ number_format($totalPinjaman, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pinjaman Belum Lunas</span>
                    <span class="info-box-number">Rp {{ number_format($pinjamanBelumLunas, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-percentage"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Bunga Pinjaman</span>
                    <span class="info-box-number">{{ $persentaseBunga }}%</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-secondary"><i class="fas fa-exchange-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Pengembalian</span>
                    <span class="info-box-number">Rp {{ number_format($totalPengembalian, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-money-bill-wave"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Bunga</span>
                    <span class="info-box-number">Rp {{ number_format($totalBungaPinjaman, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-chart-pie"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Persentase Pengembalian</span>
                    <span class="info-box-number">{{ $persentasePengembalian }}%</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Simpanan (12 Bulan Terakhir)</h3>
                </div>
                <div class="card-body">
                    <canvas id="simpananChart" width="100%"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Pinjaman (12 Bulan Terakhir)</h3>
                </div>
                <div class="card-body">
                    <canvas id="pinjamanChart" width="100%"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Status Pinjaman</h3>
                </div>
                <div class="card-body">
                    <canvas id="statusPinjamanChart" width="100%"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top 5 Peminjam</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nasabah</th>
                                    <th>Total Pinjaman</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topPeminjam as $peminjam)
                                    <tr>
                                        <td>{{ $peminjam->nasabah->nama }}</td>
                                        <td>Rp {{ number_format($peminjam->total_pinjaman, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">Tidak ada data peminjam</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan Keuangan</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Total Simpanan</th>
                                        <td>Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Pengambilan Simpanan</th>
                                        <td>Rp {{ number_format($totalPengambilanSimpanan, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="table-success">
                                        <th>Saldo Simpanan</th>
                                        <td>Rp {{ number_format($saldoSimpanan, 0, ',', '.') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Total Pinjaman</th>
                                        <td>Rp {{ number_format($totalPinjaman, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Pengembalian</th>
                                        <td>Rp {{ number_format($totalPengembalian, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Pengeluaran</th>
                                        <td>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                                    </tr>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto refresh dashboard setiap 5 menit (300000ms)
        const refreshInterval = 300000; // 5 menit
        setTimeout(function() {
            location.reload();
        }, refreshInterval);
        
        // Format angka ke format Rupiah
        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Data untuk chart simpanan
        const ctxSimpanan = document.getElementById('simpananChart').getContext('2d');
        const simpananChart = new Chart(ctxSimpanan, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_values($bulan)) !!},
                datasets: [{
                    label: 'Total Simpanan',
                    data: {!! json_encode(array_values($chartSimpanan)) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderWidth: 2,
                    pointHoverBorderColor: 'rgba(54, 162, 235, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return formatRupiah(value);
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        padding: 10,
                        titleColor: '#fff',
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyColor: '#fff',
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += formatRupiah(context.parsed.y);
                                return label;
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 15,
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    title: {
                        display: true,
                        text: 'Pertumbuhan Simpanan 12 Bulan Terakhir',
                        padding: {
                            top: 10,
                            bottom: 20
                        },
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    }
                }
            }
        });
        
        // Data untuk chart pinjaman
        const ctxPinjaman = document.getElementById('pinjamanChart').getContext('2d');
        const pinjamanChart = new Chart(ctxPinjaman, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_values($bulan)) !!},
                datasets: [{
                    label: 'Total Pinjaman',
                    data: {!! json_encode(array_values($chartPinjaman)) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    borderRadius: 5,
                    barThickness: 15,
                    maxBarThickness: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return formatRupiah(value);
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        padding: 10,
                        titleColor: '#fff',
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyColor: '#fff',
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += formatRupiah(context.parsed.y);
                                return label;
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 15,
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    title: {
                        display: true,
                        text: 'Pertumbuhan Pinjaman 12 Bulan Terakhir',
                        padding: {
                            top: 10,
                            bottom: 20
                        },
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    }
                }
            }
        });
        
        // Data untuk chart status pinjaman
        const ctxStatusPinjaman = document.getElementById('statusPinjamanChart').getContext('2d');
        const statusLabels = {!! json_encode($statusPinjaman->pluck('status')->toArray()) !!};
        const statusData = {!! json_encode($statusPinjaman->pluck('jumlah')->toArray()) !!};
        const totalStatus = statusData.reduce((a, b) => a + b, 0);
        
        // Menghitung persentase untuk setiap status
        const statusPercentages = statusData.map(value => ((value / totalStatus) * 100).toFixed(1));
        
        // Membuat label dengan persentase
        const statusLabelsWithPercentage = statusLabels.map((label, index) => {
            return `${label}: ${statusPercentages[index]}%`;
        });
        
        const statusPinjamanData = {
            labels: statusLabelsWithPercentage,
            datasets: [{
                label: 'Status Pinjaman',
                data: statusData,
                backgroundColor: [
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 2,
                hoverOffset: 10
            }]
        };
        
        const statusPinjamanChart = new Chart(ctxStatusPinjaman, {
            type: 'doughnut',
            data: statusPinjamanData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const percentage = ((value / totalStatus) * 100).toFixed(1);
                                return `${label.split(':')[0]}: ${value} (${percentage}%)`;
                            }
                        }
                    },
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map(function(label, i) {
                                        const meta = chart.getDatasetMeta(0);
                                        const style = meta.controller.getStyle(i);
                                        
                                        return {
                                            text: label,
                                            fillStyle: style.backgroundColor,
                                            strokeStyle: style.borderColor,
                                            lineWidth: style.borderWidth,
                                            pointStyle: 'circle',
                                            hidden: !chart.getDataVisibility(i),
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Distribusi Status Pinjaman',
                        padding: {
                            top: 10,
                            bottom: 15
                        },
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
