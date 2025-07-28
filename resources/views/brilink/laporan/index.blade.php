@extends('layouts.app', ['title' => 'Laporan BRI Link'])
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Laporan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('brilink.laporan.index') }}" method="GET" id="filterForm" class="mb-0">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="start_date">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        value="{{ $start_date }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="end_date">Tanggal Selesai</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                        value="{{ $end_date }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary mr-2">Filter</button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group d-flex">
                                    <div class="flex-grow-1 mr-2">
                                        <label for="export_type">Jenis Laporan</label>
                                        <select class="form-control" id="export_type">
                                            <option value="all">Semua Laporan</option>
                                            <option value="setor-tunai">Hanya Setor Tunai</option>
                                            <option value="tarik-tunai">Hanya Tarik Tunai</option>
                                            <option value="bayar-tagihan">Hanya Bayar Tagihan PLN</option>
                                            <option value="pemasukan">Pemasukan</option>
                                            <option value="pengeluaran">Pengeluaran</option>
                                        </select>
                                    </div>
                                    <div class="align-self-end">
                                        <label class="d-block" style="visibility: hidden;">Export</label>
                                        <button type="button" id="exportPdfBtn" class="btn btn-success">
                                            <i class="fas fa-download"></i> Export PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Setor Tunai</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabelSetorTunai">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Nama</th>
                                    <th>No Rekening</th>
                                    <th>Jumlah</th>
                                    <th>Nominal</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($setor_tunai as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->kode_transaksi }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->norek }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>Rp.{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_setor_tunai)->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="5" class="text-right">Total Nominal</td>
                                    <td colspan="2">Rp. {{ number_format($total_setor, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Tarik Tunai</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabelTarikTunai">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Nama</th>
                                    <th>No Rekening</th>
                                    <th>No Rekening Tujuan</th>
                                    <th>Nominal</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tarik_tunai as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->kode_transaksi }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->norek }}</td>
                                        <td>{{ $item->norek_tujuan }}</td>
                                        <td>Rp. {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_tarik_tunai)->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="5" class="text-right">Total Nominal</td>
                                    <td colspan="2">Rp. {{ number_format($total_tarik, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Bayar Tagihan PLN</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabelBayarTagihan">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>ID Pelanggan</th>
                                    <th>Nominal</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bayar_tagihan as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->id_pelanggan }}</td>
                                        <td>Rp. {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="4" class="text-right">Total Nominal</td>
                                    <td colspan="2">Rp. {{ number_format($total_bayar_pln, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Pemasukan BRI Link</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabelBayarTagihan">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nominal</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_pemasukan = 0;
                                @endphp
                                @forelse ($pemasukan as $index => $item)
                                    @php
                                        $total_pemasukan += $item->originalNominal;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>Rp. {{ number_format($item->originalNominal, 0, ',', '.') }}</td>
                                        <td>
                                            <textarea class="form-control" readonly>{{ $item->keterangan }}</textarea>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="3" class="text-right">Total Nominal</td>
                                    <td colspan="2">Rp. {{ number_format($total_pemasukan, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Pengeluaran BRI Link</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabelBayarTagihan">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode</th>
                                    <th>Jenis Pengeluaran</th>
                                    <th>Jumlah</th>
                                    <th>Nominal</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_pegeluaran = 0;
                                @endphp
                                @forelse ($pengeluaran as $index => $item)
                                    @php
                                        $total_pegeluaran += $item->harga * $item->jumlah;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->jenis_pengeluaran }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-light font-weight-bold">
                                    <td colspan="4" class="text-right">Total Nominal</td>
                                    <td colspan="2">Rp. {{ number_format($total_pegeluaran, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rekap Transaksi</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="bg-primary">
                                    <th>Jenis Transaksi</th>
                                    <th>Jumlah Transaksi</th>
                                    <th>Total Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Pemasukan</td>
                                    <td>{{ count($pemasukan) }}</td>
                                    <td>Rp. {{ number_format($pemasukan->sum('originalNominal'), 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Pengeluaran</td>
                                    <td>{{ count($pengeluaran) }}</td>
                                    @php
                                        $total_pengeluaran = 0;
                                        foreach ($pengeluaran as $key => $value) {
                                            $total_pengeluaran += $value->harga * $value->jumlah;
                                        }
                                    @endphp
                                    <td>Rp. {{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Setor Tunai</td>
                                    <td>{{ count($setor_tunai) }}</td>
                                    <td>Rp. {{ number_format($total_setor, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Tarik Tunai</td>
                                    <td>{{ count($tarik_tunai) }}</td>
                                    <td>Rp. {{ number_format($total_tarik, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Bayar Tagihan PLN</td>
                                    <td>{{ count($bayar_tagihan) }}</td>
                                    <td>Rp. {{ number_format($total_bayar_pln, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td>Total</td>
                                    <td>{{ count($setor_tunai) + count($tarik_tunai) + count($bayar_tagihan) }}</td>
                                    <td>Rp.
                                        {{ number_format($total_setor + $total_tarik + $total_bayar_pln + $total_pengeluaran + $pemasukan->sum('originalNominal'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        /* Perbaikan alignment form filter */
        .form-group {
            margin-bottom: 0;
        }

        .align-items-end .form-group {
            margin-bottom: 0;
        }
    </style>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            // $('#tabelSetorTunai').DataTable();
            // $('#tabelTarikTunai').DataTable();
            // $('#tabelBayarTagihan').DataTable();

            // Export PDF handler
            $('#exportPdfBtn').click(function(e) {
                e.preventDefault();
                const type = $('#export_type').val();
                const start_date = $('#start_date').val();
                const end_date = $('#end_date').val();
                if (!start_date || !end_date) {
                    alert('Mohon pilih tanggal mulai dan tanggal selesai terlebih dahulu');
                    return;
                }
                let baseUrl = "{{ url('/') }}";
                let url = baseUrl + "/brilink/laporan/export-pdf/" + type;
                url += '?start_date=' + encodeURIComponent(start_date) + '&end_date=' + encodeURIComponent(
                    end_date);
                window.open(url, '_blank');
            });
        });
    </script>
@endpush
