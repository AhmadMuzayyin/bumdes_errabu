@extends('layouts.app', ['title' => 'Pemasukan Simpan Pinjam'])
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Data Pemasukan Simpan Pinjam</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nominal</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($incomes as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nominal }}</td>
                                        <td>
                                            <textarea class="form-control" readonly>{{ $item->jenis_pemasukan }}</textarea>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="font-weight-bold">Total</td>
                                    <td class="font-weight-bold" colspan="4">
                                        Rp. {{ number_format($incomes->sum('originalNominal'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
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
            $('#table').DataTable();
        });
    </script>
@endpush
