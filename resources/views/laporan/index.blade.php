@extends('layouts.app', ['title' => 'Laporan', 'activePage' => 'Laporan Keuangan'])

@include('helpers.format_helpers')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title">Laporan Keuangan</h3>
                        </div>
                        <div class="col-auto">
                            <div class="dropdown">
                                <button class="btn btn-danger dropdown-toggle" type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-file-pdf"></i> Export PDF
                                </button>
                                <div class="dropdown-menu" aria-labelledby="exportDropdown">
                                    <a class="dropdown-item" href="#" id="exportAll">Semua Laporan</a>
                                    <a class="dropdown-item" href="#" id="exportIncome">Laporan Dana Masuk</a>
                                    <a class="dropdown-item" href="#" id="exportExpense">Laporan Dana Keluar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="start">Periode Mulai</label>
                            <input type="date" class="form-control" id="start" name="start"
                                value="{{ $tanggalMulai }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end">Periode Akhir</label>
                            <input type="date" class="form-control" id="end" name="end"
                                value="{{ $tanggalSelesai }}">
                        </div>
                        <div class="col-md-3">
                            <label for="badan_usaha_id">Badan Usaha</label>
                            <select class="form-control" id="badan_usaha_id" name="badan_usaha_id">
                                <option value="">-- Semua Badan Usaha --</option>
                                @foreach($badanUsahaList as $bu)
                                    <option value="{{ $bu->id }}" {{ $badanUsahaId == $bu->id ? 'selected' : '' }}>{{ $bu->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" id="btnFilter" class="btn btn-primary mr-2">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a role="button" href="{{ route('laporan.umum.index') }}" class="btn btn-secondary">
                                <i class="fas fa-sync-alt"></i> Reset
                            </a>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-money-bill"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Dana Masuk</span>
                                    <span class="info-box-number">Rp {{ number_format($totalDanaMasuk, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Dana Keluar</span>
                                    <span class="info-box-number">Rp {{ number_format($totalDanaKeluar, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Saldo</span>
                                    <span class="info-box-number">Rp {{ number_format($totalDanaMasuk - $totalDanaKeluar, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs for different reports -->
                    <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="summary-tab" data-toggle="tab" href="#summary" role="tab">
                                <i class="fas fa-chart-pie"></i> Ringkasan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="income-tab" data-toggle="tab" href="#income" role="tab">
                                <i class="fas fa-hand-holding-usd"></i> Dana Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="expense-tab" data-toggle="tab" href="#expense" role="tab">
                                <i class="fas fa-file-invoice-dollar"></i> Dana Keluar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="simpan-pinjam-tab" data-toggle="tab" href="#simpan-pinjam" role="tab">
                                <i class="fas fa-landmark"></i> Simpan Pinjam
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="fotocopy-tab" data-toggle="tab" href="#fotocopy" role="tab">
                                <i class="fas fa-copy"></i> Foto Copy
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="brilink-tab" data-toggle="tab" href="#brilink" role="tab">
                                <i class="fas fa-exchange-alt"></i> BRI Link
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content mt-3" id="reportTabsContent">
                        <!-- Summary Tab -->
                        <div class="tab-pane fade show active" id="summary" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-success">
                                            <h3 class="card-title">Ringkasan Dana Masuk</h3>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="incomeChart" height="250"></canvas>
                                            <table class="table table-bordered mt-3">
                                                <tr>
                                                    <th>Sumber</th>
                                                    <th class="text-right">Jumlah</th>
                                                </tr>
                                                <tr>
                                                    <td>Umum</td>
                                                    <td class="text-right">Rp {{ number_format($summaryDanaMasuk['umum'] ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Simpan Pinjam</td>
                                                    <td class="text-right">Rp {{ number_format($summaryDanaMasuk['simpan_pinjam'] ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Foto Copy</td>
                                                    <td class="text-right">Rp {{ number_format($summaryDanaMasuk['fotocopy'] ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>BRI Link</td>
                                                    <td class="text-right">Rp {{ number_format($summaryDanaMasuk['brilink'] ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                                <tr class="bg-light font-weight-bold">
                                                    <td>Total</td>
                                                    <td class="text-right">Rp {{ number_format($totalDanaMasuk, 0, ',', '.') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-danger">
                                            <h3 class="card-title">Ringkasan Dana Keluar</h3>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="expenseChart" height="250"></canvas>
                                            <table class="table table-bordered mt-3">
                                                <tr>
                                                    <th>Sumber</th>
                                                    <th class="text-right">Jumlah</th>
                                                </tr>
                                                <tr>
                                                    <td>Umum</td>
                                                    <td class="text-right">Rp {{ number_format($summaryDanaKeluar['umum'] ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Simpan Pinjam</td>
                                                    <td class="text-right">Rp {{ number_format($summaryDanaKeluar['simpan_pinjam'] ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Foto Copy</td>
                                                    <td class="text-right">Rp {{ number_format($summaryDanaKeluar['fotocopy'] ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                                <tr class="bg-light font-weight-bold">
                                                    <td>Total</td>
                                                    <td class="text-right">Rp {{ number_format($totalDanaKeluar, 0, ',', '.') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Income Tab -->
                        <div class="tab-pane fade" id="income" role="tabpanel">
                            <div class="card">
                                <div class="card-header bg-success">
                                    <h3 class="card-title">Dana Masuk</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped" id="danaMasukTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tanggal</th>
                                                <th>Badan Usaha</th>
                                                <th>Keterangan</th>
                                                <th class="text-right">Jumlah Dana Masuk</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($danaMasuk['umum'] as $item)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                                    <td>{{ $item->badan_usaha->nama }}</td>
                                                    <td>{{ $item->deskripsi ?? '-' }}</td>
                                                    <td class="text-right">{{ formatNominalSafe($item->nominal) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Expense Tab -->
                        <div class="tab-pane fade" id="expense" role="tabpanel">
                            <div class="card">
                                <div class="card-header bg-danger">
                                    <h3 class="card-title">Dana Keluar</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped" id="danaKeluarTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tanggal</th>
                                                <th>Tujuan</th>
                                                <th>Keterangan</th>
                                                <th class="text-right">Jumlah Dana Keluar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($danaKeluar['umum'] as $item)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                                    <td>{{ $item->tujuan }}</td>
                                                    <td>{{ $item->deskripsi ?? '-' }}</td>
                                                    <td class="text-right">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Simpan Pinjam Tab -->
                        <div class="tab-pane fade" id="simpan-pinjam" role="tabpanel">
                            <!-- Nested tabs for Simpan Pinjam -->
                            <ul class="nav nav-tabs" id="simpanPinjamTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="simpanan-tab" data-toggle="tab" href="#simpanan" role="tab">Simpanan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pengambilan-tab" data-toggle="tab" href="#pengambilan" role="tab">Pengambilan Simpanan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pinjaman-tab" data-toggle="tab" href="#pinjaman" role="tab">Pinjaman</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pengembalian-tab" data-toggle="tab" href="#pengembalian" role="tab">Pengembalian Pinjaman</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pengeluaran-sp-tab" data-toggle="tab" href="#pengeluaran-sp" role="tab">Pengeluaran</a>
                                </li>
                            </ul>
                            
                            <!-- Simpan Pinjam Tab Content -->
                            <div class="tab-content mt-3" id="simpanPinjamTabsContent">
                                <!-- Simpanan -->
                                <div class="tab-pane fade show active" id="simpanan" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tanggal</th>
                                                    <th>Nasabah</th>
                                                    <th>Kategori</th>
                                                    <th class="text-right">Nominal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @forelse($simpanPinjamData['simpanan'] as $item)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->tgl_simpan)->format('d/m/Y') }}</td>
                                                        <td>{{ $item->nasabah->nama ?? 'N/A' }}</td>
                                                        <td>{{ ucfirst($item->kategori) }}</td>
                                                        <td class="text-right">{{ $item->nominal }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">Tidak ada data simpanan</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Pengambilan Simpanan -->
                                <div class="tab-pane fade" id="pengambilan" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tanggal</th>
                                                    <th>Nasabah</th>
                                                    <th>Kategori</th>
                                                    <th class="text-right">Nominal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @forelse($simpanPinjamData['pengambilan_simpanan'] as $item)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->tgl_pengambilan)->format('d/m/Y') }}</td>
                                                        <td>{{ $item->nasabah->nama ?? 'N/A' }}</td>
                                                        <td>{{ ucfirst($item->kategori) }}</td>
                                                        <td class="text-right">{{ $item->nominal }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">Tidak ada data pengambilan simpanan</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Pinjaman -->
                                <div class="tab-pane fade" id="pinjaman" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tanggal</th>
                                                    <th>Nasabah</th>
                                                    <th class="text-right">Nominal</th>
                                                    <th class="text-right">Total Pengembalian</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @forelse($simpanPinjamData['pinjaman'] as $item)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</td>
                                                        <td>{{ $item->nasabah->nama ?? 'N/A' }}</td>
                                                        <td class="text-right">{{ $item->nominal }}</td>
                                                        <td class="text-right">{{ $item->nominal_pengembalian }}</td>
                                                        <td>{{ $item->status }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center">Tidak ada data pinjaman</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Pengembalian Pinjaman -->
                                <div class="tab-pane fade" id="pengembalian" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tanggal</th>
                                                    <th>Nasabah</th>
                                                    <th class="text-right">Nominal</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @forelse($simpanPinjamData['pengembalian_pinjaman'] as $item)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->tgl_pengembalian_sementara)->format('d/m/Y') }}</td>
                                                        <td>{{ $item->nasabah->nama ?? 'N/A' }}</td>
                                                        <td class="text-right">{{ $item->nominal_cicilan }}</td>
                                                        <td>{{ $item->status }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">Tidak ada data pengembalian pinjaman</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Pengeluaran Simpan Pinjam -->
                                <div class="tab-pane fade" id="pengeluaran-sp" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tanggal</th>
                                                    <th>Kode</th>
                                                    <th>Tujuan</th>
                                                    <th class="text-right">Nominal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @forelse($simpanPinjamData['pengeluaran'] as $item)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->tgl_pengeluaran)->format('d/m/Y') }}</td>
                                                        <td>{{ $item->kode }}</td>
                                                        <td>{{ $item->tujuan }}</td>
                                                        <td class="text-right">{{ $item->jumlah }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">Tidak ada data pengeluaran</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fotocopy Tab -->
                        <div class="tab-pane fade" id="fotocopy" role="tabpanel">
                            <!-- Nested tabs for Fotocopy -->
                            <ul class="nav nav-tabs" id="fotocopyTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pembayaran-fc-tab" data-toggle="tab" href="#pembayaran-fc" role="tab">Pembayaran</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pengeluaran-fc-tab" data-toggle="tab" href="#pengeluaran-fc" role="tab">Pengeluaran</a>
                                </li>
                            </ul>
                            
                            <!-- Fotocopy Tab Content -->
                            <div class="tab-content mt-3" id="fotocopyTabsContent">
                                <!-- Pembayaran Fotocopy -->
                                <div class="tab-pane fade show active" id="pembayaran-fc" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tanggal</th>
                                                    <th>Nama</th>
                                                    <th>Jumlah Lembar</th>
                                                    <th class="text-right">Nominal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @forelse($danaMasuk['fotocopy'] as $item)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->tgl_pembayaran)->format('d/m/Y') }}</td>
                                                        <td>{{ $item->nama }}</td>
                                                        <td>{{ $item->jumlah_lembar }}</td>
                                                        <td class="text-right">{{ $item->total_pembayaran }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">Tidak ada data pembayaran fotocopy</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Pengeluaran Fotocopy -->
                                <div class="tab-pane fade" id="pengeluaran-fc" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tanggal</th>
                                                    <th>Keterangan</th>
                                                    <th class="text-right">Nominal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @forelse($danaKeluar['fotocopy'] as $item)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->tgl_pengeluaran)->format('d/m/Y') }}</td>
                                                        <td>{{ $item->keterangan }}</td>
                                                        <td class="text-right">{{ $item->jumlah}}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">Tidak ada data pengeluaran fotocopy</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BRI Link Tab -->
                        <div class="tab-pane fade" id="brilink" role="tabpanel">
                            <!-- Nested tabs for BRI Link -->
                            <ul class="nav nav-tabs" id="brilinkTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="setor-tab" data-toggle="tab" href="#setor" role="tab">Setor Tunai</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tarik-tab" data-toggle="tab" href="#tarik" role="tab">Tarik Tunai</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="bayar-tab" data-toggle="tab" href="#bayar" role="tab">Bayar Tagihan PLN</a>
                                </li>
                            </ul>
                            
                            <!-- BRI Link Tab Content -->
                            <div class="tab-content mt-3" id="brilinkTabsContent">
                                <!-- Setor Tunai -->
                                <div class="tab-pane fade show active" id="setor" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tanggal</th>
                                                    <th>Kode</th>
                                                    <th>Nama</th>
                                                    <th>No. Rekening</th>
                                                    <th class="text-right">Nominal</th>
                                                    <th class="text-right">Admin</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @forelse($brilinkTransaksi['setor_tunai'] as $item)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->tgl_setor_tunai)->format('d/m/Y') }}</td>
                                                        <td>{{ $item->kode_transaksi }}</td>
                                                        <td>{{ $item->nama }}</td>
                                                        <td>{{ $item->norek }}</td>
                                                        <td class="text-right">{{ $item->nominal }}</td>
                                                        <td class="text-right">{{ $item->admin_fee ?? 0 }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">Tidak ada data setor tunai</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Tarik Tunai -->
                                <div class="tab-pane fade" id="tarik" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tanggal</th>
                                                    <th>Kode</th>
                                                    <th>Nama</th>
                                                    <th>No. Rekening</th>
                                                    <th class="text-right">Nominal</th>
                                                    <th class="text-right">Admin</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @forelse($brilinkTransaksi['tarik_tunai'] as $item)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->tgl_tarik_tunai)->format('d/m/Y') }}</td>
                                                        <td>{{ $item->kode_transaksi }}</td>
                                                        <td>{{ $item->nama }}</td>
                                                        <td>{{ $item->norek }}</td>
                                                        <td class="text-right">{{ $item->nominal }}</td>
                                                        <td class="text-right">{{ $item->admin_fee ?? 0 }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">Tidak ada data tarik tunai</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Bayar Tagihan PLN -->
                                <div class="tab-pane fade" id="bayar" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped datatable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tanggal</th>
                                                    <th>Kode</th>
                                                    <th>Nama</th>
                                                    <th>ID Pelanggan</th>
                                                    <th class="text-right">Nominal</th>
                                                    <th class="text-right">Admin</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @forelse($brilinkTransaksi['bayar_pln'] as $item)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d/m/Y') }}</td>
                                                        <td>{{ $item->kode }}</td>
                                                        <td>{{ $item->nama }}</td>
                                                        <td>{{ $item->id_pelanggan }}</td>
                                                        <td class="text-right">{{ $item->nominal }}</td>
                                                        <td class="text-right">{{ $item->admin_fee ?? 0 }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">Tidak ada data pembayaran tagihan PLN</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form for PDF Export -->
    <form id="exportForm" action="{{ route('laporan.umum.export-pdf') }}" method="POST" target="_blank" class="d-none">
        @csrf
        <input type="hidden" name="start" id="exportStart" value="{{ $tanggalMulai }}">
        <input type="hidden" name="end" id="exportEnd" value="{{ $tanggalSelesai }}">
        <input type="hidden" name="type" id="exportType" value="all">
        <input type="hidden" name="badan_usaha_id" id="exportBadanUsaha" value="{{ $badanUsahaId }}">
    </form>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function() {
        // Konfigurasi DataTable
        var dataTableOptions = {
            paging: true,
            lengthChange: false,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: true,
            retrieve: true, // Mencegah error inisialisasi ulang
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
        
        // Inisialisasi tabel hanya pada tab yang aktif saat pertama kali
        function initActiveTabTables() {
            $('.tab-pane.active .datatable').each(function() {
                var $table = $(this);
                
                try {
                    // Validasi struktur tabel
                    if ($table.find('thead tr').length === 0 || $table.find('tbody').length === 0) {
                        console.warn('Tabel tidak memiliki struktur yang valid', $table);
                        return;
                    }
                    
                    // Hitung jumlah kolom pada header
                    var headerColsCount = $table.find('thead tr:first th').length;
                    
                    // Pastikan colspan pada pesan "tidak ada data" sesuai dengan jumlah kolom
                    $table.find('tbody tr td[colspan]').each(function() {
                        $(this).attr('colspan', headerColsCount);
                    });
                    
                    // Inisialisasi DataTable dengan destroy terlebih dahulu untuk menghindari error
                    if ($.fn.DataTable.isDataTable($table)) {
                        $table.DataTable().destroy();
                    }
                    
                    $table.DataTable(dataTableOptions);
                } catch (e) {
                    console.error('Error initializing DataTable:', e);
                }
            });
        }
        
        // Inisialisasi tabel pada tab yang aktif saat awal loading
        initActiveTabTables();
        
        // Inisialisasi tabel saat tab diaktifkan
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var targetId = $(e.target).attr('href');
            $(targetId + ' .datatable').each(function() {
                var $table = $(this);
                
                try {
                    // Hanya inisialisasi jika belum diinisialisasi atau perlu diperbarui
                    if (!$.fn.DataTable.isDataTable($table)) {
                        // Validasi struktur tabel
                        if ($table.find('thead tr').length === 0 || $table.find('tbody').length === 0) {
                            console.warn('Tabel tidak memiliki struktur yang valid', $table);
                            return;
                        }
                        
                        // Hitung jumlah kolom pada header
                        var headerColsCount = $table.find('thead tr:first th').length;
                        
                        // Pastikan colspan pada pesan "tidak ada data" sesuai dengan jumlah kolom
                        $table.find('tbody tr td[colspan]').each(function() {
                            $(this).attr('colspan', headerColsCount);
                        });
                        
                        $table.DataTable(dataTableOptions);
                    }
                } catch (e) {
                    console.error('Error initializing DataTable on tab change:', e);
                }
            });
        });
        // Fungsi tambahan untuk memperbaiki struktur tabel yang bermasalah
        function fixTableStructure() {
            // Fix untuk tabel kosong atau struktur yang tidak konsisten
            $('.datatable').each(function() {
                var $table = $(this);
                var headerColsCount = $table.find('thead tr:first th').length;
                
                if (headerColsCount > 0) {
                    // Periksa apakah ada baris tbody dan td[colspan]
                    if ($table.find('tbody tr').length > 0) {
                        $table.find('tbody tr').each(function() {
                            var $row = $(this);
                            // Jika row memiliki colspan, sesuaikan dengan jumlah header
                            if ($row.find('td[colspan]').length > 0) {
                                $row.find('td[colspan]').attr('colspan', headerColsCount);
                            } 
                            // Jika jumlah kolom tidak sesuai dengan header
                            else if ($row.find('td').length != headerColsCount) {
                                // Jika kurang, tambahkan kolom kosong
                                while ($row.find('td').length < headerColsCount) {
                                    $row.append('<td></td>');
                                }
                                // Jika lebih, hapus kolom berlebih
                                if ($row.find('td').length > headerColsCount) {
                                    $row.find('td').slice(headerColsCount).remove();
                                }
                            }
                        });
                    }
                }
            });
        }
        
        // Jalankan fix struktur tabel sebelum inisialisasi DataTable
        fixTableStructure();
        
        // Filter Button
        $('#btnFilter').on('click', function() {
            var start = $('#start').val();
            var end = $('#end').val();
            var badanUsaha = $('#badan_usaha_id').val();
            
            if (!start || !end) {
                alert('Periode tanggal harus diisi');
                return;
            }
            
            let url = "{{ route('laporan.umum.index') }}?start=" + start + "&end=" + end;
            
            if (badanUsaha) {
                url += "&badan_usaha_id=" + badanUsaha;
            }
            
            window.location.href = url;
        });
        
        // Chart Initialization
        // Income Chart
        var incomeCtx = document.getElementById('incomeChart').getContext('2d');
        var incomeChart = new Chart(incomeCtx, {
            type: 'pie',
            data: {
                labels: ['Umum', 'Simpan Pinjam', 'Foto Copy', 'BRI Link'],
                datasets: [{
                    label: 'Dana Masuk',
                    data: [
                        {{ $summaryDanaMasuk['umum'] ?? 0 }},
                        {{ $summaryDanaMasuk['simpan_pinjam'] ?? 0 }},
                        {{ $summaryDanaMasuk['fotocopy'] ?? 0 }},
                        {{ $summaryDanaMasuk['brilink'] ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Distribusi Dana Masuk'
                    }
                }
            }
        });
        
        // Expense Chart
        var expenseCtx = document.getElementById('expenseChart').getContext('2d');
        var expenseChart = new Chart(expenseCtx, {
            type: 'pie',
            data: {
                labels: ['Umum', 'Simpan Pinjam', 'Foto Copy'],
                datasets: [{
                    label: 'Dana Keluar',
                    data: [
                        {{ $summaryDanaKeluar['umum'] ?? 0 }},
                        {{ $summaryDanaKeluar['simpan_pinjam'] ?? 0 }},
                        {{ $summaryDanaKeluar['fotocopy'] ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(201, 203, 207, 0.8)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(201, 203, 207, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Distribusi Dana Keluar'
                    }
                }
            }
        });
        
        // Export PDF
        $('#exportAll, #exportIncome, #exportExpense').on('click', function(e) {
            e.preventDefault();
            
            const exportType = $(this).attr('id') === 'exportAll' ? 'all' : 
                              ($(this).attr('id') === 'exportIncome' ? 'income' : 'expense');
            
            $('#exportType').val(exportType);
            $('#exportStart').val($('#start').val());
            $('#exportEnd').val($('#end').val());
            $('#exportBadanUsaha').val($('#badan_usaha_id').val());
            
            $('#exportForm').submit();
        });
    });
</script>
@endpush
