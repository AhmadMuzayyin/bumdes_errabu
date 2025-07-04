@extends('layouts.app', ['title' => 'Dashboard BRI Link'])
@section('content')
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($nominalSetorTunai, 0, ',', '.') }}</h3>
                    <p>Total Setor Tunai ({{ $totalSetorTunai }} transaksi)</p>
                </div>
                <div class="icon">
                    <i class="ion ion-arrow-up-a"></i>
                </div>
                <a href="{{ route('brilink.setor-tunai.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($nominalTarikTunai, 0, ',', '.') }}</h3>
                    <p>Total Tarik Tunai ({{ $totalTarikTunai }} transaksi)</p>
                </div>
                <div class="icon">
                    <i class="ion ion-arrow-down-a"></i>
                </div>
                <a href="{{ route('brilink.tarik-tunai.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($nominalBayarPln, 0, ',', '.') }}</h3>
                    <p>Total Bayar Tagihan PLN ({{ $totalBayarPln }} transaksi)</p>
                </div>
                <div class="icon">
                    <i class="ion ion-flash"></i>
                </div>
                <a href="{{ route('brilink.bayar-tagihan-pln.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transaksi Terbaru</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabelTransaksi">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Nominal</th>
                                    <th>Jenis Transaksi</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksiTerbaru as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item['kode'] }}</td>
                                        <td>{{ $item['nama'] }}</td>
                                        <td>{{ number_format($item['nominal'], 0, ',', '.') }}</td>
                                        <td>{{ $item['jenis'] }}</td>
                                        <td>{{ $item['tanggal'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data transaksi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#tabelTransaksi').DataTable();
        });
    </script>
@endpush
