@extends('layouts.app', ['title' => 'Laporan Simpan Pinjam', 'activePage' => 'Laporan Simpan Pinjam'])

@include('helpers.format_helpers')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="card-title">Laporan Simpan Pinjam</h3>
                    </div>
                    <div class="col-auto">
                        <div class="dropdown">
                            <button class="btn btn-danger dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </button>
                            <div class="dropdown-menu" aria-labelledby="exportDropdown">
                                <a class="dropdown-item" href="#" id="exportSimpanan">Laporan Simpanan</a>
                                <a class="dropdown-item" href="#" id="exportPengambilanSimpanan">Laporan Pengambilan</a>
                                <a class="dropdown-item" href="#" id="exportPinjaman">Laporan Pinjaman</a>
                                <a class="dropdown-item" href="#" id="exportPengembalianPinjaman">Laporan Pengembalian Pinjaman</a>
                                <a class="dropdown-item" href="#" id="exportPengeluaran">Laporan Pengeluaran</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="start">Periode Mulai</label>
                        <input type="date" class="form-control" id="start" name="start" value="{{ date('Y-m-01') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end">Periode Akhir</label>
                        <input type="date" class="form-control" id="end" name="end" value="{{ date('Y-m-t') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" id="btnFilter" class="btn btn-primary mr-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a role="button" href="{{ route('laporan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-sync-alt"></i> Reset
                        </a>
                    </div>
                </div>

                <!-- Tabs for different reports -->
                <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="simpanan-tab" data-toggle="tab" href="#simpanan" role="tab">
                            <i class="fas fa-money-bill-wave"></i> Simpanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pengambilan-tab" data-toggle="tab" href="#pengambilan" role="tab">
                            <i class="fas fa-hand-holding-usd"></i> Pengambilan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pinjaman-tab" data-toggle="tab" href="#pinjaman" role="tab">
                            <i class="fas fa-hand-holding-heart"></i> Pinjaman
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pengembalian-tab" data-toggle="tab" href="#pengembalian" role="tab">
                            <i class="fas fa-hand-holding"></i> Pengembalian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pengeluaran-tab" data-toggle="tab" href="#pengeluaran" role="tab">
                            <i class="fas fa-shopping-cart"></i> Pengeluaran
                        </a>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="reportTabsContent">
                    <!-- Tab Simpanan -->
                    <div class="tab-pane fade show active" id="simpanan" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nasabah</th>
                                        <th>Tanggal</th>
                                        <th>Nominal</th>
                                    </tr>
                                </thead>
                                <tbody id="simpananTableBody">
                                    <!-- Data akan diisi melalui Ajax -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Tab Pengambilan -->
                    <div class="tab-pane fade" id="pengambilan" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nasabah</th>
                                        <th>Tanggal</th>
                                        <th>Nominal</th>
                                    </tr>
                                </thead>
                                <tbody id="pengambilanTableBody">
                                    <!-- Data akan diisi melalui Ajax -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Tab Pinjaman -->
                    <div class="tab-pane fade" id="pinjaman" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nasabah</th>
                                        <th>Tanggal</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="pinjamanTableBody">
                                    <!-- Data akan diisi melalui Ajax -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Tab Pengembalian -->
                    <div class="tab-pane fade" id="pengembalian" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pinjaman</th>
                                        <th>Tanggal</th>
                                        <th>Cicilan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="pengembalianTableBody">
                                    <!-- Data akan diisi melalui Ajax -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Tab Pengeluaran -->
                    <div class="tab-pane fade" id="pengeluaran" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Tujuan</th>
                                    </tr>
                                </thead>
                                <tbody id="pengeluaranTableBody">
                                    <!-- Data akan diisi melalui Ajax -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
$(document).ready(function() {
    // Function to load data based on filter
    function loadData() {
        var startDate = $('#start').val();
        var endDate = $('#end').val();
        
        // Load all data
        loadSimpanan(startDate, endDate);
        loadPengambilan(startDate, endDate);
        loadPinjaman(startDate, endDate);
        loadPengembalian(startDate, endDate);
        loadPengeluaran(startDate, endDate);
    }
    
    // Filter button click
    $('#btnFilter').on('click', function() {
        loadData();
    });
    
    // Initial load
    loadData();
    
    // PDF export buttons
    $('#exportSimpanan').on('click', function(e) {
        e.preventDefault();
        exportPdf('simpanan');
    });
    
    $('#exportPengambilanSimpanan').on('click', function(e) {
        e.preventDefault();
        exportPdf('pengambilan');
    });
    
    $('#exportPinjaman').on('click', function(e) {
        e.preventDefault();
        exportPdf('pinjaman');
    });
    
    $('#exportPengembalianPinjaman').on('click', function(e) {
        e.preventDefault();
        exportPdf('pengembalian');
    });
    
    $('#exportPengeluaran').on('click', function(e) {
        e.preventDefault();
        exportPdf('pengeluaran');
    });
});

function loadSimpanan(startDate, endDate) {
    $.ajax({
        url: "{{ route('laporan.api.simpanan') }}",
        type: "POST",
        data: {
            "_token": "{{ csrf_token() }}",
            "tanggal_awal": startDate,
            "tanggal_akhir": endDate
        },
        success: function(response) {
            updateSimpananTable(response.data);
            $('#totalSimpanan').text('Rp ' + formatNumber(response.total));
        }
    });
}

function loadPengambilan(startDate, endDate) {
    $.ajax({
        url: "{{ route('laporan.api.pengambilan') }}",
        type: "POST",
        data: {
            "_token": "{{ csrf_token() }}",
            "tanggal_awal": startDate,
            "tanggal_akhir": endDate
        },
        success: function(response) {
            updatePengambilanTable(response.data);
            $('#totalPengambilan').text('Rp ' + formatNumber(response.total));
        }
    });
}

function loadPinjaman(startDate, endDate) {
    $.ajax({
        url: "{{ route('laporan.api.pinjaman') }}",
        type: "POST",
        data: {
            "_token": "{{ csrf_token() }}",
            "tanggal_awal": startDate,
            "tanggal_akhir": endDate
        },
        success: function(response) {
            updatePinjamanTable(response.data);
            $('#totalPinjaman').text('Rp ' + formatNumber(response.total));
        }
    });
}

function loadPengembalian(startDate, endDate) {
    $.ajax({
        url: "{{ route('laporan.api.pengembalian') }}",
        type: "POST",
        data: {
            "_token": "{{ csrf_token() }}",
            "tanggal_awal": startDate,
            "tanggal_akhir": endDate
        },
        success: function(response) {
            updatePengembalianTable(response.data);
            $('#totalPengembalian').text('Rp ' + formatNumber(response.total));
        }
    });
}

function loadPengeluaran(startDate, endDate) {
    $.ajax({
        url: "{{ route('laporan.api.pengeluaran') }}",
        type: "POST",
        data: {
            "_token": "{{ csrf_token() }}",
            "tanggal_awal": startDate,
            "tanggal_akhir": endDate
        },
        success: function(response) {
            updatePengeluaranTable(response.data);
        }
    });
}

function updateSimpananTable(data) {
    var html = '';
    if (data.length > 0) {
        $.each(data, function(index, item) {
            html += `<tr>
                <td>${index + 1}</td>
                <td>${item.nasabah.nama}</td>
                <td>${formatDate(item.tgl_simpan)}</td>
                <td class="text-right">${item.nominal}</td>
            </tr>`;
        });
    } else {
        html = '<tr><td colspan="4" class="text-center">Tidak ada data</td></tr>';
    }
    $('#simpananTableBody').html(html);
}

function updatePengambilanTable(data) {
    var html = '';
    if (data.length > 0) {
        $.each(data, function(index, item) {
            html += `<tr>
                <td>${index + 1}</td>
                <td>${item.nasabah.nama}</td>
                <td>${formatDate(item.tgl_pengambilan)}</td>
                <td class="text-right">${item.nominal}</td>
            </tr>`;
        });
    } else {
        html = '<tr><td colspan="4" class="text-center">Tidak ada data</td></tr>';
    }
    $('#pengambilanTableBody').html(html);
}

function updatePinjamanTable(data) {
    var html = '';
    if (data.length > 0) {
        $.each(data, function(index, item) {
            html += `<tr>
                <td>${index + 1}</td>
                <td>${item.nasabah.nama}</td>
                <td>${formatDate(item.tgl_pinjam)}</td>
                <td class="text-right">${item.nominal}</td>
                <td>${item.status}</td>
            </tr>`;
        });
    } else {
        html = '<tr><td colspan="5" class="text-center">Tidak ada data</td></tr>';
    }
    $('#pinjamanTableBody').html(html);
}

function updatePengembalianTable(data) {
    var html = '';
    if (data.length > 0) {
        $.each(data, function(index, item) {
            html += `<tr>
                <td>${index + 1}</td>
                <td>${item.pinjaman.nasabah.nama}</td>
                <td>${formatDate(item.tgl_pengembalian_sementara)}</td>
                <td class="text-right">${item.nominal_cicilan}</td>
                <td>${item.status}</td>
            </tr>`;
        });
    } else {
        html = '<tr><td colspan="5" class="text-center">Tidak ada data</td></tr>';
    }
    $('#pengembalianTableBody').html(html);
}

function updatePengeluaranTable(data) {
    var html = '';
    if (data.length > 0) {
        $.each(data, function(index, item) {
            html += `<tr>
                <td>${index + 1}</td>
                <td>${item.kode}</td>
                <td>${formatDate(item.tgl_pengeluaran)}</td>
                <td class="text-right">${item.jumlah}</td>
                <td>${item.tujuan || '-'}</td>
            </tr>`;
        });
    } else {
        html = '<tr><td colspan="5" class="text-center">Tidak ada data</td></tr>';
    }
    $('#pengeluaranTableBody').html(html);
}

function formatDate(dateString) {
    var date = new Date(dateString);
    return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
}

function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

function exportPdf(type) {
    var startDate = $('#start').val();
    var endDate = $('#end').val();
    
    var url = '';
    switch(type) {
        case 'simpanan':
            url = "{{ route('laporan.simpanan') }}";
            break;
        case 'pengambilan':
            url = "{{ route('laporan.pengambilan-simpanan') }}";
            break;
        case 'pinjaman':
            url = "{{ route('laporan.pinjaman') }}";
            break;
        case 'pengembalian':
            url = "{{ route('laporan.pengembalian-pinjaman') }}";
            break;
        case 'pengeluaran':
            url = "{{ route('laporan.pengeluaran') }}";
            break;
    }
    
    // Create form
    var form = $('<form>', {
        'method': 'post',
        'action': url,
        'target': '_blank'
    }).append($('<input>', {
        'name': '_token',
        'value': '{{ csrf_token() }}',
        'type': 'hidden'
    })).append($('<input>', {
        'name': 'tanggal_awal',
        'value': startDate,
        'type': 'hidden'
    })).append($('<input>', {
        'name': 'tanggal_akhir',
        'value': endDate,
        'type': 'hidden'
    })).append($('<input>', {
        'name': 'tipe_laporan',
        'value': 'pdf',
        'type': 'hidden'
    }));
    
    // Append to body, submit, and remove
    $('body').append(form);
    form.submit();
    form.remove();
}
</script>
@endpush
@endsection
