@extends('layouts.app', ['title' => 'Laporan Simpan Pinjam', 'activePage' => 'Laporan Simpan Pinjam'])
@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h3 class="card-title">Laporan Simpan Pinjam</h3>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- Filter -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="tanggal_awal">Periode Mulai</label>
                        <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" 
                            value="{{ $tanggalAwal ?? date('Y-m-01') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="tanggal_akhir">Periode Akhir</label>
                        <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" 
                            value="{{ $tanggalAkhir ?? date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="nasabah_id">Nasabah</label>
                        <select class="form-control" id="nasabah_id" name="nasabah_id">
                            <option value="">-- Semua Nasabah --</option>
                            @foreach($nasabah as $n)
                                <option value="{{ $n->id }}" {{ $nasabahId == $n->id ? 'selected' : '' }}>{{ $n->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" id="btnFilter" class="btn btn-primary mr-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button type="button" id="btnRefresh" class="btn btn-secondary">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- Tabs untuk navigasi laporan -->
                <ul class="nav nav-tabs mb-3" id="reportTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="simpanan-tab" data-toggle="tab" href="#simpanan" role="tab">Simpanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pinjaman-tab" data-toggle="tab" href="#pinjaman" role="tab">Pinjaman</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pengeluaran-tab" data-toggle="tab" href="#pengeluaran" role="tab">Pengeluaran</a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="reportTabsContent">
                    <!-- Tab Simpanan -->
                    <div class="tab-pane fade show active" id="simpanan" role="tabpanel">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Transaksi Simpanan</h3>
                                <div class="card-tools">
                                    <button class="btn btn-sm btn-danger" id="exportSimpananPdf">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped table-hover" id="simpananTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Tanggal</th>
                                            <th>Nasabah</th>
                                            <th>Jenis</th>
                                            <th class="text-right">Nominal</th>
                                            <th class="text-right">Saldo Berjalan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transaksiSimpanan['transaksi'] ?? [] as $i => $item)
                                        <tr data-nasabah-id="{{ $item['nasabah_id'] ?? '' }}">
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $item['tanggal'] }}</td>
                                            <td>{{ $item['nasabah_nama'] ?? '-' }}</td>
                                            <td>
                                                @if($item['jenis'] == 'Debit')
                                                    <span class="badge badge-success">{{ $item['jenis'] }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ $item['jenis'] }}</span>
                                                @endif
                                            </td>
                                            <td class="text-right">{{ number_format($item['nominal'], 0, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item['saldo_berjalan'], 0, ',', '.') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data transaksi simpanan</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <th colspan="5" class="text-right">Total Saldo Akhir:</th>
                                            <th class="text-right" id="saldoAkhirSimpanan">{{ number_format($transaksiSimpanan['saldo_akhir'] ?? 0, 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Pinjaman -->
                    <div class="tab-pane fade" id="pinjaman" role="tabpanel">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Transaksi Pinjaman (Belum Lunas)</h3>
                                <div class="card-tools">
                                    <button class="btn btn-sm btn-danger" id="exportPinjamanPdf">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped table-hover" id="pinjamanTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Tanggal</th>
                                            <th>Nasabah</th>
                                            <th class="text-right">Total Pinjaman</th>
                                            <th class="text-right">Total Pengembalian</th>
                                            <th class="text-right">Sisa Pinjaman</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transaksiPinjaman['pinjaman'] ?? [] as $i => $item)
                                        <tr data-nasabah-id="{{ $item['nasabah_id'] ?? '' }}">
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $item['tanggal'] }}</td>
                                            <td>{{ $item['nasabah_nama'] ?? '-' }}</td>
                                            <td class="text-right">{{ number_format($item['total_pinjaman'], 0, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item['total_pengembalian'], 0, ',', '.') }}</td>
                                            <td class="text-right">{{ number_format($item['nominal'], 0, ',', '.') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data pinjaman yang belum lunas</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <th colspan="5" class="text-right">Total Pinjaman Belum Lunas:</th>
                                            <th class="text-right" id="totalPinjamanBelumLunas">{{ number_format($transaksiPinjaman['total_pinjaman'] ?? 0, 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Pengeluaran -->
                    <div class="tab-pane fade" id="pengeluaran" role="tabpanel">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Pengeluaran</h3>
                                <div class="card-tools">
                                    <button class="btn btn-sm btn-danger" id="exportPengeluaranPdf">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped table-hover" id="pengeluaranTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Tanggal</th>
                                            <th>Kode</th>
                                            <th>Tujuan</th>
                                            <th class="text-right">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pengeluaran['pengeluaran'] ?? [] as $i => $item)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $item['tanggal'] }}</td>
                                            <td>{{ $item['kode'] }}</td>
                                            <td>{{ $item['tujuan'] }}</td>
                                            <td class="text-right">{{ number_format($item['nominal'], 0, ',', '.') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data pengeluaran</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <th colspan="4" class="text-right">Total Pengeluaran:</th>
                                            <th class="text-right" id="totalPengeluaran">{{ number_format($pengeluaran['total_pengeluaran'] ?? 0, 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rekap Simpanan & Pinjaman Per Nasabah -->
                <div class="card card-outline card-success mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Rekap Data Per Nasabah</h3>
                        <div class="card-tools">
                            <button class="btn btn-sm btn-danger" id="exportRekapPdf">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Nasabah</th>
                                    <th class="text-center">Detail</th>
                                    <th class="text-right">Saldo Simpanan</th>
                                    <th class="text-right">Sisa Pinjaman</th>
                                </tr>
                            </thead>
                            <tbody id="rekapTableBody">
                                @php
                                    $rekapData = collect();
                                    foreach($nasabah as $n) {
                                        $simpanan = collect($transaksiSimpanan['transaksi'] ?? [])->where('nasabah_id', $n->id)->last();
                                        $pinjaman = collect($transaksiPinjaman['pinjaman'] ?? [])->where('nasabah_id', $n->id)->sum('nominal');
                                        
                                        if ($simpanan || $pinjaman > 0) {
                                            $rekapData->push([
                                                'id' => $n->id,
                                                'nama' => $n->nama,
                                                'saldo_simpanan' => $simpanan ? $simpanan['saldo_berjalan'] : 0,
                                                'sisa_pinjaman' => $pinjaman
                                            ]);
                                        }
                                    }
                                @endphp
                                
                                @forelse($rekapData as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $item['nama'] }}</td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-xs btn-info export-nasabah" data-id="{{ $item['id'] }}">
                                            <i class="fas fa-file-pdf"></i> Export
                                        </a>
                                    </td>
                                    <td class="text-right">{{ number_format($item['saldo_simpanan'], 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($item['sisa_pinjaman'], 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data rekap</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <th colspan="3" class="text-right">Total:</th>
                                    <th class="text-right">{{ number_format($transaksiSimpanan['saldo_akhir'] ?? 0, 0, ',', '.') }}</th>
                                    <th class="text-right">{{ number_format($transaksiPinjaman['total_pinjaman'] ?? 0, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Set up CSRF token untuk AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Muat data jika tanggal sudah diset
        var tanggalAwal = $('#tanggal_awal').val();
        var tanggalAkhir = $('#tanggal_akhir').val();
        var nasabahId = $('#nasabah_id').val();
        
        if (tanggalAwal && tanggalAkhir) {
            loadDataViaAjax(tanggalAwal, tanggalAkhir, nasabahId);
        }
        
        // Konfigurasi DataTables default
        var dtConfig = {
            paging: true,
            lengthChange: false,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data yang ditampilkan",
                infoFiltered: "(disaring dari _MAX_ data keseluruhan)",
                zeroRecords: "Tidak ada data yang cocok",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        };
        
        // Inisialisasi DataTables
        $('#simpananTable').DataTable(dtConfig);
        $('#pinjamanTable').DataTable(dtConfig);
        $('#pengeluaranTable').DataTable(dtConfig);
        
        // Button Filter
        $('#btnFilter').on('click', function() {
            var tanggalAwal = $('#tanggal_awal').val();
            var tanggalAkhir = $('#tanggal_akhir').val();
            var nasabahId = $('#nasabah_id').val();
            
            // Validasi input tanggal
            if (!tanggalAwal || !tanggalAkhir) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Periode tanggal harus diisi',
                });
                return;
            }

            // Tampilkan loading
            Swal.fire({
                title: 'Memuat data...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Gunakan AJAX untuk memperbarui data tanpa reload halaman
            loadDataViaAjax(tanggalAwal, tanggalAkhir, nasabahId);
        });
        
        // Button Refresh
        $('#btnRefresh').on('click', function() {
            // Reset form dan reload data dari server
            $('#tanggal_awal').val('{{ date('Y-m-01') }}');
            $('#tanggal_akhir').val('{{ date('Y-m-d') }}');
            $('#nasabah_id').val('');
            
            // Tampilkan loading
            Swal.fire({
                title: 'Memuat data...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Load data default
            loadDataViaAjax($('#tanggal_awal').val(), $('#tanggal_akhir').val(), '');
        });
        
        // Export PDF buttons
        $('#exportSimpananPdf').on('click', function(e) {
            e.preventDefault();
            exportPdf('simpanan');
        });
        
        $('#exportPinjamanPdf').on('click', function(e) {
            e.preventDefault();
            exportPdf('pinjaman');
        });
        
        $('#exportPengeluaranPdf').on('click', function(e) {
            e.preventDefault();
            exportPdf('pengeluaran');
        });
        
        $('#exportRekapPdf').on('click', function(e) {
            e.preventDefault();
            exportPdf('rekap-simpanan');
        });
        
        // Export per nasabah
        $(document).on('click', '.export-nasabah', function(e) {
            e.preventDefault();
            var nasabahId = $(this).data('id');
            exportPdf('rekap-simpanan', nasabahId);
        });
        
        // Helper function untuk format rupiah
        function formatRupiah(angka) {
            if (angka === null || angka === undefined || isNaN(parseFloat(angka))) {
                return '0';
            }
            return parseFloat(angka).toFixed(0).replace(/\d(?=(\d{3})+$)/g, '$&.');
        }
        
        // Fungsi untuk memuat data via AJAX
        function loadDataViaAjax(tanggalAwal, tanggalAkhir, nasabahId) {
            $.ajax({
                url: "{{ route('laporan.update') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal_awal: tanggalAwal,
                    tanggal_akhir: tanggalAkhir,
                    nasabah_id: nasabahId
                },
                success: function(response) {
                    if (response.success) {
                        // Update URL untuk bookmark/share tanpa reload halaman
                        var newUrl = window.location.pathname;
                        var params = [];
                        
                        if (tanggalAwal) params.push('tanggal_awal=' + tanggalAwal);
                        if (tanggalAkhir) params.push('tanggal_akhir=' + tanggalAkhir);
                        if (nasabahId) params.push('nasabah_id=' + nasabahId);
                        
                        if (params.length > 0) {
                            newUrl += '?' + params.join('&');
                        }
                        
                        window.history.pushState({}, '', newUrl);
                        
                        // Update tabel dan data lainnya
                        updateTabelSimpanan(response.transaksiSimpanan);
                        updateTabelPinjaman(response.transaksiPinjaman);
                        updateTabelPengeluaran(response.pengeluaran);
                        updateRekapData();
                        
                        // Tutup loading
                        Swal.close();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat data. Silakan coba lagi.'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan server. Silakan coba lagi nanti.'
                    });
                }
            });
        }
        
        // Update tabel simpanan
        function updateTabelSimpanan(data) {
            var tbody = $('#simpananTable tbody');
            var html = '';
            
            if (data.transaksi && data.transaksi.length > 0) {
                $.each(data.transaksi, function(i, item) {
                    var badgeClass = item.jenis === 'Debit' ? 'badge-success' : 'badge-danger';
                    html += '<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td>' + item.tanggal + '</td>' +
                        '<td>' + (item.nasabah_nama || '-') + '</td>' +
                        '<td><span class="badge ' + badgeClass + '">' + item.jenis + '</span></td>' +
                        '<td class="text-right">' + formatRupiah(item.nominal) + '</td>' +
                        '<td class="text-right">' + formatRupiah(item.saldo_berjalan) + '</td>' +
                        '</tr>';
                });
            } else {
                html = '<tr><td colspan="6" class="text-center">Tidak ada data transaksi simpanan</td></tr>';
            }
            
            tbody.html(html);
            $('#saldoAkhirSimpanan').text(formatRupiah(data.saldo_akhir || 0));
            
            // Refresh DataTable
            if ($.fn.DataTable.isDataTable('#simpananTable')) {
                $('#simpananTable').DataTable().destroy();
            }
            $('#simpananTable').DataTable(dtConfig);
        }
        
        // Update tabel pinjaman
        function updateTabelPinjaman(data) {
            var tbody = $('#pinjamanTable tbody');
            var html = '';
            
            if (data.pinjaman && data.pinjaman.length > 0) {
                $.each(data.pinjaman, function(i, item) {
                    html += '<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td>' + item.tanggal + '</td>' +
                        '<td>' + (item.nasabah_nama || '-') + '</td>' +
                        '<td class="text-right">' + formatRupiah(item.total_pinjaman) + '</td>' +
                        '<td class="text-right">' + formatRupiah(item.total_pengembalian) + '</td>' +
                        '<td class="text-right">' + formatRupiah(item.nominal) + '</td>' +
                        '</tr>';
                });
            } else {
                html = '<tr><td colspan="6" class="text-center">Tidak ada data pinjaman yang belum lunas</td></tr>';
            }
            
            tbody.html(html);
            $('#totalPinjamanBelumLunas').text(formatRupiah(data.total_pinjaman || 0));
            
            // Refresh DataTable
            if ($.fn.DataTable.isDataTable('#pinjamanTable')) {
                $('#pinjamanTable').DataTable().destroy();
            }
            $('#pinjamanTable').DataTable(dtConfig);
        }
        
        // Update tabel pengeluaran
        function updateTabelPengeluaran(data) {
            var tbody = $('#pengeluaranTable tbody');
            var html = '';
            
            if (data.pengeluaran && data.pengeluaran.length > 0) {
                $.each(data.pengeluaran, function(i, item) {
                    html += '<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td>' + item.tanggal + '</td>' +
                        '<td>' + item.kode + '</td>' +
                        '<td>' + item.tujuan + '</td>' +
                        '<td class="text-right">' + formatRupiah(item.nominal) + '</td>' +
                        '</tr>';
                });
            } else {
                html = '<tr><td colspan="5" class="text-center">Tidak ada data pengeluaran</td></tr>';
            }
            
            tbody.html(html);
            $('#totalPengeluaran').text(formatRupiah(data.total_pengeluaran || 0));
            
            // Refresh DataTable
            if ($.fn.DataTable.isDataTable('#pengeluaranTable')) {
                $('#pengeluaranTable').DataTable().destroy();
            }
            $('#pengeluaranTable').DataTable(dtConfig);
        }
        
        // Update rekap data
        function updateRekapData() {
            // Ambil data dari tabel yang sudah diperbarui
            var transaksiSimpanan = [];
            var transaksiPinjaman = [];
            
            // Koleksi nasabah yang ada
            var nasabahs = {};
            
            // Isi dari tabel simpanan
            $('#simpananTable tbody tr').each(function() {
                var cells = $(this).find('td');
                if (cells.length > 1) {  // Pastikan bukan baris kosong
                    var nasabahNama = $(cells[2]).text();
                    var nasabahId = $(this).data('nasabah-id');
                    var saldoBerjalan = parseInt($(cells[5]).text().replace(/\./g, '')) || 0;
                    
                    nasabahs[nasabahId] = {
                        id: nasabahId,
                        nama: nasabahNama,
                        saldo_simpanan: saldoBerjalan,
                        sisa_pinjaman: 0
                    };
                }
            });
            
            // Isi dari tabel pinjaman
            $('#pinjamanTable tbody tr').each(function() {
                var cells = $(this).find('td');
                if (cells.length > 1) {  // Pastikan bukan baris kosong
                    var nasabahNama = $(cells[2]).text();
                    var nasabahId = $(this).data('nasabah-id');
                    var sisaPinjaman = parseInt($(cells[5]).text().replace(/\./g, '')) || 0;
                    
                    if (nasabahs[nasabahId]) {
                        nasabahs[nasabahId].sisa_pinjaman = sisaPinjaman;
                    } else {
                        nasabahs[nasabahId] = {
                            id: nasabahId,
                            nama: nasabahNama,
                            saldo_simpanan: 0,
                            sisa_pinjaman: sisaPinjaman
                        };
                    }
                }
            });
            
            // Perbarui tabel rekap
            var rekapData = Object.values(nasabahs);
            var html = '';
            var totalSimpanan = 0;
            var totalPinjaman = 0;
            
            if (rekapData.length > 0) {
                $.each(rekapData, function(i, item) {
                    if (item.saldo_simpanan > 0 || item.sisa_pinjaman > 0) {
                        html += '<tr>' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td>' + item.nama + '</td>' +
                            '<td class="text-center">' +
                            '<a href="#" class="btn btn-xs btn-info export-nasabah" data-id="' + item.id + '">' +
                            '<i class="fas fa-file-pdf"></i> Export' +
                            '</a>' +
                            '</td>' +
                            '<td class="text-right">' + formatRupiah(item.saldo_simpanan) + '</td>' +
                            '<td class="text-right">' + formatRupiah(item.sisa_pinjaman) + '</td>' +
                            '</tr>';
                            
                        totalSimpanan += item.saldo_simpanan;
                        totalPinjaman += item.sisa_pinjaman;
                    }
                });
            } else {
                html = '<tr><td colspan="5" class="text-center">Tidak ada data rekap</td></tr>';
            }
            
            $('#rekapTableBody').html(html);
            $('#rekapTableBody').closest('table').find('tfoot th:eq(3)').text(formatRupiah(totalSimpanan));
            $('#rekapTableBody').closest('table').find('tfoot th:eq(4)').text(formatRupiah(totalPinjaman));
        }
        
        // Export PDF
        function exportPdf(type, nasabahId = null) {
            var tanggalAwal = $('#tanggal_awal').val();
            var tanggalAkhir = $('#tanggal_akhir').val();
            
            // Validasi input
            if (!tanggalAwal || !tanggalAkhir) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Periode tanggal harus diisi untuk export PDF',
                });
                return;
            }
            
            // Tentukan URL berdasarkan tipe
            var routeUrl;
            switch (type) {
                case 'simpanan':
                    routeUrl = "{{ route('laporan.simpanan') }}";
                    break;
                case 'pinjaman':
                    routeUrl = "{{ route('laporan.pinjaman') }}";
                    break;
                case 'pengeluaran':
                    routeUrl = "{{ route('laporan.pengeluaran') }}";
                    break;
                case 'rekap-simpanan':
                    routeUrl = "{{ route('laporan.rekap-simpanan') }}";
                    break;
                default:
                    console.error('Tipe laporan tidak valid:', type);
                    return;
            }
            
            // Buat form untuk submit
            var form = $('<form>', {
                'method': 'POST',
                'action': routeUrl,
                'target': '_blank'
            });
            
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'tanggal_awal',
                'value': tanggalAwal
            }));
            
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'tanggal_akhir',
                'value': tanggalAkhir
            }));
            
            // Tambahkan nasabah_id jika ada
            if (nasabahId) {
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'nasabah_id',
                    'value': nasabahId
                }));
            }
            
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'tipe_laporan',
                'value': 'pdf'
            }));
            
            form.append($('<input>', {
                'type': 'hidden',
                'name': '_token',
                'value': "{{ csrf_token() }}"
            }));
            
            $('body').append(form);
            form.submit();
            form.remove();
        }
    });
</script>
@endsection
