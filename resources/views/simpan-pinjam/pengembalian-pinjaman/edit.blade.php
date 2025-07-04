@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Pengembalian Pinjaman</h3>
                    <div class="card-tools">
                        <a href="{{ route('pengembalian-pinjaman.index') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    <form action="{{ route('pengembalian-pinjaman.update', $pengembalian->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="pinjamans_id">Pinjaman <span class="text-danger">*</span></label>
                            <select name="pinjamans_id" id="pinjamans_id" class="form-control select2" required>
                                <option value="">-- Pilih Pinjaman --</option>
                                @foreach($pinjaman as $item)
                                    <option value="{{ $item->id }}" {{ old('pinjamans_id', $pengembalian->pinjamans_id) == $item->id ? 'selected' : '' }}
                                        data-nominal="{{ $item->getOriginalNominalAttribute() }}"
                                        data-total-dibayar="{{ App\Models\PengembalianPinjaman::where('pinjamans_id', $item->id)->sum('nominal_cicilan') - $pengembalian->getOriginalNominalCicilanAttribute() }}">
                                        {{ $item->nasabah->nama }} - {{ $item->tgl_pinjam }} - {{ $item->nominal }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Informasi Pinjaman -->
                        <div class="info-box mb-3" id="pinjamanInfo">
                            <span class="info-box-icon bg-info"><i class="fas fa-money-bill"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Informasi Pinjaman</span>
                                <span class="info-box-number">Nominal Pokok Pinjaman: <span id="totalPinjaman">{{ $pengembalian->pinjaman->nominal }}</span></span>
                                <span class="info-box-number">Total Sudah Dibayar (selain cicilan ini): <span id="totalDibayar">
                                    @php
                                        $totalDibayar = App\Models\PengembalianPinjaman::where('pinjamans_id', $pengembalian->pinjamans_id)
                                            ->where('id', '!=', $pengembalian->id)
                                            ->sum('nominal_cicilan');
                                        echo 'Rp. ' . number_format($totalDibayar, 0, ',', '.');
                                    @endphp
                                </span></span>
                                <span class="info-box-number">Sisa Yang Harus Dibayar: <span id="sisaPinjaman">
                                    @php
                                        $nominal = $pengembalian->pinjaman->getOriginalNominalAttribute();
                                        $sisaPembayaran = $nominal - $totalDibayar;
                                        echo 'Rp. ' . number_format($sisaPembayaran, 0, ',', '.');
                                    @endphp
                                </span></span>
                                <span class="info-box-text text-danger" id="warningMessage"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="nominal_cicilan">Nominal Cicilan yang Dibayarkan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="nominal_cicilan" id="nominal_cicilan" class="form-control" 
                                value="{{ old('nominal_cicilan', $pengembalian->getOriginalNominalCicilanAttribute()) }}" 
                                required min="0" step="1000">
                            <small class="form-text text-muted">Nominal cicilan yang dibayarkan pada tahap ini.</small>
                        </div>
                        <div class="form-group">
                            <label for="tgl_pengembalian_sementara">Tanggal Pembayaran Cicilan <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_pengembalian_sementara" id="tgl_pengembalian_sementara" class="form-control" value="{{ old('tgl_pengembalian_sementara', $pengembalian->getOriginalTglPengembalianSementaraAttribute()) }}" required>
                            <small class="form-text text-muted">Tanggal saat cicilan ini dibayarkan.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="Belum Lunas" {{ old('status', $pengembalian->status) == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                <option value="Lunas" {{ old('status', $pengembalian->status) == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                            </select>
                            <small class="form-text text-muted">Status pengembalian cicilan ini.</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Perbarui</button>
                            <a href="{{ route('pengembalian-pinjaman.index') }}" class="btn btn-default">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
        
        // Menampilkan informasi pinjaman ketika halaman dimuat
        updatePinjamanInfo();
        
        // Menampilkan informasi pinjaman ketika pinjaman berubah
        $('#pinjamans_id').change(function() {
            updatePinjamanInfo();
        });
        
        // Memantau perubahan pada nominal cicilan
        $('#nominal_cicilan').on('input', function() {
            checkStatus();
        });
        
        // Fungsi untuk memperbarui informasi pinjaman
        function updatePinjamanInfo() {
            const selectedOption = $('#pinjamans_id').find(':selected');
            console.log('Option terpilih:', selectedOption.val(), selectedOption.data());
            
            if (selectedOption.val()) {
                // Ambil nilai pokok pinjaman dari data attribute
                const nominal = parseFloat(selectedOption.data('nominal')) || 0;
                // Ambil total yang sudah dibayarkan tidak termasuk cicilan saat ini
                const totalDibayar = parseFloat(selectedOption.data('totalDibayar')) || 0;
                // Hitung sisa yang harus dibayar
                const remaining = nominal - totalDibayar;
                
                console.log('Data pinjaman:', {
                    nominal: nominal,
                    totalDibayar: totalDibayar,
                    remaining: remaining
                });
                
                // Format untuk tampilan
                const formattedNominal = new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(nominal);
                
                const formattedTotalDibayar = new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(totalDibayar);
                
                const formattedRemaining = new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(remaining);
                
                // Update tampilan
                $('#totalPinjaman').text(formattedNominal);
                $('#totalDibayar').text(formattedTotalDibayar);
                $('#sisaPinjaman').text(formattedRemaining);
                
                // Check status lunas
                checkStatus();
            }
        }
        
        // Fungsi untuk memeriksa status lunas/belum lunas
        function checkStatus() {
            const selectedOption = $('#pinjamans_id').find(':selected');
            
            if (selectedOption.val()) {
                // Ambil nilai untuk perhitungan
                const nominal = parseFloat(selectedOption.data('nominal')) || 0;
                const totalDibayar = parseFloat(selectedOption.data('totalDibayar')) || 0;
                const nominalCicilan = parseFloat($('#nominal_cicilan').val()) || 0;
                
                // Total pembayaran termasuk cicilan saat ini
                const totalPembayaran = totalDibayar + nominalCicilan;
                
                // Tentukan status berdasarkan total pembayaran vs nominal pinjaman
                if (totalPembayaran >= nominal) {
                    $('#status').val('Lunas');
                    $('#warningMessage').text('');
                } else {
                    $('#status').val('Belum Lunas');
                    $('#warningMessage').text('Total pembayaran belum mencukupi total pinjaman. Status akan diatur ke "Belum Lunas".');
                }
                
                console.log('Status check:', {
                    nominal: nominal,
                    totalDibayar: totalDibayar,
                    nominalCicilan: nominalCicilan,
                    totalPembayaran: totalPembayaran,
                    status: totalPembayaran >= nominal ? 'Lunas' : 'Belum Lunas'
                });
            }
        }
    });
</script>
@endsection
