@extends('layouts.app')

@push('css')
<style>
    .perhitungan-detail {
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
        margin-top: 10px;
    }
</style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Pinjaman</h3>
                    <div class="card-tools">
                        <a href="{{ route('pinjaman.index') }}" class="btn btn-warning btn-sm">
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
                    
                    <form action="{{ route('pinjaman.store') }}" method="POST">
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
                            <label for="nominal">Nominal Pinjaman (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="nominal" id="nominal" class="form-control" value="{{ old('nominal') }}" required min="0">
                        </div>
                        <div class="form-group">
                            <label for="tgl_pinjam">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control" value="{{ old('tgl_pinjam', date('Y-m-d')) }}" required>
                        </div>
                        
                        <div class="alert alert-info mb-3">
                            <strong>Informasi bunga pinjaman:</strong>
                            <p class="mb-0">Bunga saat ini: {{ $bunga }}</p>
                            <p class="mb-0">Nominal yang dipinjam akan ditambah bunga sebesar {{ $bunga }} dari jumlah pinjaman.</p>
                            <div id="perhitunganBunga" class="mt-2">
                                <p class="mb-0">Silahkan masukkan nominal pinjaman untuk melihat perhitungan.</p>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('pinjaman.index') }}" class="btn btn-default">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Format angka ke format rupiah
        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
        
        // Hitung bunga dan total berdasarkan nominal
        function hitungBunga() {
            const nominal = parseInt($('#nominal').val()) || 0;
            const persentaseBunga = {{ $original_bunga }};
            
            if (nominal > 0) {
                const bunga = nominal * persentaseBunga / 100;
                const total = nominal + bunga;
                
                $('#perhitunganBunga').html(`
                    <div class="perhitungan-detail">
                        <p class="mb-1"><strong>Perhitungan:</strong></p>
                        <p class="mb-1">Nominal pinjaman: ${formatRupiah(nominal)}</p>
                        <p class="mb-1">Bunga (${persentaseBunga}%): ${formatRupiah(bunga)}</p>
                        <p class="mb-0"><strong>Total yang harus dikembalikan: ${formatRupiah(total)}</strong></p>
                    </div>
                `);
            } else {
                $('#perhitunganBunga').html(`
                    <p class="mb-0">Silahkan masukkan nominal pinjaman untuk melihat perhitungan.</p>
                `);
            }
        }
        
        // Panggil fungsi hitungBunga saat nominal berubah
        $('#nominal').on('input', function() {
            hitungBunga();
        });
        
        // Init perhitungan bunga
        hitungBunga();
    });
</script>
@endpush

@section('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    });
</script>
@endsection
