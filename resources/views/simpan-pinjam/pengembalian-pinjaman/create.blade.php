@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Pengembalian Pinjaman</h3>
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
                    
                    <form action="{{ route('pengembalian-pinjaman.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nasabah_id">Nasabah</label>
                            <select name="nasabah_id" id="nasabah_id" class="form-control select2" required>
                                <option value="">-- Pilih Nasabah --</option>
                                @foreach($nasabah as $item)
                                    <option value="{{ $item->id }}" {{ old('nasabah_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }} - {{ $item->nik }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pinjamans_id">Pinjaman <span class="text-danger">*</span></label>
                            <select name="pinjamans_id" id="pinjamans_id" class="form-control" required>
                                <option value="">-- Pilih Nasabah Terlebih Dahulu --</option>
                            </select>
                        </div>
                        <div class="info-box mb-3 d-none" id="pinjamanInfo">
                            <span class="info-box-icon bg-info"><i class="fas fa-money-bill"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Informasi Pinjaman</span>
                                <span class="info-box-number">Nominal Pokok Pinjaman: <span id="totalPinjaman">0</span></span>
                                <span class="info-box-number">Total Harus Dikembalikan: <span id="totalPengembalian">0</span></span>
                                <span class="info-box-number">Sudah Dibayar: <span id="totalDibayar">0</span></span>
                                <span class="info-box-number">Sisa Pembayaran: <span id="sisaPinjaman">0</span></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nominal_cicilan">Nominal Cicilan yang Dibayarkan (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="nominal_cicilan" id="nominal_cicilan" class="form-control" value="{{ old('nominal_cicilan') }}" required min="0">
                            <small class="form-text text-muted">Masukkan jumlah uang yang ingin dibayarkan untuk cicilan kali ini.</small>
                        </div>
                        <div class="form-group">
                            <label for="tgl_pengembalian_sementara">Tanggal Pembayaran Cicilan <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_pengembalian_sementara" id="tgl_pengembalian_sementara" class="form-control" value="{{ old('tgl_pengembalian_sementara', date('Y-m-d')) }}" required>
                            <small class="form-text text-muted">Tanggal saat cicilan ini dibayarkan.</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('pengembalian-pinjaman.index') }}" class="btn btn-default">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
        
        // Mendapatkan pinjaman berdasarkan nasabah
        $('#nasabah_id').change(function() {
            const nasabahId = $(this).val();
            if (nasabahId) {
                $.ajax({
                    url: '/get-pinjaman-by-nasabah/' + nasabahId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('#pinjamans_id').empty();
                        $('#pinjamans_id').append('<option value="">-- Pilih Pinjaman --</option>');
                        // Menampilkan response di console untuk debugging
                        console.log('Response dari server:', response);
                        
                        $.each(response, function(key, value) {
                            console.log('Data pinjaman:', value);
                            
                            // Menggunakan attributes untuk mendapatkan nilai asli dari database
                            // nominal = nilai pokok pinjaman
                            let nominal_pokok = value.attributes ? value.attributes.nominal : 0;
                            
                            // Jika attributes tidak tersedia, ambil dari value.nominal dan konversi ke angka
                            if (!nominal_pokok && value.nominal) {
                                if (typeof value.nominal === 'string' && value.nominal.includes('Rp.')) {
                                    nominal_pokok = parseInt(value.nominal.replace('Rp. ', '').replace(/\./g, ''));
                                } else {
                                    nominal_pokok = parseFloat(value.nominal);
                                }
                            }
                            
                            // nominal_pengembalian = total yang harus dikembalikan (pokok + bunga)
                            // Jika nilai pengembalian tidak ada, gunakan nilai pokok pinjaman
                            // (berarti tidak ada bunga)
                            let total_pengembalian = value.nominal_pengembalian || nominal_pokok;
                            
                            if (typeof total_pengembalian === 'string' && total_pengembalian.includes('Rp.')) {
                                total_pengembalian = parseInt(total_pengembalian.replace('Rp. ', '').replace(/\./g, ''));
                            } else {
                                total_pengembalian = parseFloat(total_pengembalian) || nominal_pokok;
                            }
                            
                            // Sudah dibayar sejauh ini
                            let sudah_dibayar = 0;
                            
                            // Cek apakah ada data cicilan yang sudah dibayar
                            if (value.total_pembayaran) {
                                if (typeof value.total_pembayaran === 'string' && value.total_pembayaran.includes('Rp.')) {
                                    sudah_dibayar = parseInt(value.total_pembayaran.replace('Rp. ', '').replace(/\./g, ''));
                                } else {
                                    sudah_dibayar = parseFloat(value.total_pembayaran) || 0;
                                }
                            }
                            
                            console.log('Nilai pokok pinjaman:', nominal_pokok);
                            console.log('Total yang harus dikembalikan:', total_pengembalian);
                            console.log('Sudah dibayar:', sudah_dibayar);
                            
                            // Hitung sisa yang harus dibayar
                            const remaining = total_pengembalian - sudah_dibayar;
                            console.log('Sisa pembayaran:', remaining);
                            
                            // Format untuk tampilan
                            const formattedTotal = value.nominal;
                            
                            // Tampilkan tanggal
                            const display_date = value.tgl_pinjam || 'Tanggal tidak tersedia';
                            
                            $('#pinjamans_id').append('<option value="'+ value.id +'" ' + 
                                'data-pokok="'+ nominal_pokok +'" ' + 
                                'data-total-pengembalian="'+ total_pengembalian +'" ' + 
                                'data-paid="'+ sudah_dibayar +'" ' + 
                                'data-remaining="'+ remaining +'">' + 
                                display_date + ' - ' + formattedTotal + 
                            '</option>');
                        });
                    }
                });
            } else {
                $('#pinjamans_id').empty();
                $('#pinjamans_id').append('<option value="">-- Pilih Nasabah Terlebih Dahulu --</option>');
                $('#pinjamanInfo').addClass('d-none');
            }
        });
        
        // Menampilkan informasi pinjaman
        $('#pinjamans_id').change(function() {
            const selectedOption = $(this).find(':selected');
            console.log('Option terpilih:', selectedOption.val(), selectedOption.data());
            
            if (selectedOption.val()) {
                // Ambil data dari atribut data di option
                const pokokPinjaman = parseFloat(selectedOption.data('pokok')) || 0;
                const totalPengembalian = parseFloat(selectedOption.data('totalPengembalian')) || 0;
                const paid = parseFloat(selectedOption.data('paid')) || 0;
                const remaining = parseFloat(selectedOption.data('remaining')) || 0;
                
                console.log('Data dari option:', {
                    pokokPinjaman: pokokPinjaman,
                    totalPengembalian: totalPengembalian,
                    paid: paid,
                    remaining: remaining
                });
                
                // Format angka untuk tampilan
                const formattedPokok = new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(pokokPinjaman);
                
                const formattedTotalPengembalian = new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(totalPengembalian);
                
                const formattedPaid = new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(paid);
                
                const formattedRemaining = new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(remaining);
                
                // Update tampilan informasi
                $('#totalPinjaman').text(formattedPokok);
                $('#totalPengembalian').text(formattedTotalPengembalian);
                $('#totalDibayar').text(formattedPaid);
                $('#sisaPinjaman').text(formattedRemaining);
                $('#pinjamanInfo').removeClass('d-none');
                
                // Set maksimum nominal yang bisa diinput untuk cicilan
                $('#nominal_cicilan').attr('max', remaining);
                // Set nilai default untuk nominal cicilan
                $('#nominal_cicilan').val(remaining);
                
                console.log('Info pinjaman diperbarui:', {
                    formattedPokok: formattedPokok,
                    formattedTotalPengembalian: formattedTotalPengembalian,
                    formattedPaid: formattedPaid,
                    formattedRemaining: formattedRemaining
                });
            } else {
                $('#pinjamanInfo').addClass('d-none');
                $('#nominal_cicilan').val('');
                $('#nominal_cicilan').removeAttr('max');
            }
        });
    });
</script>
@endpush
