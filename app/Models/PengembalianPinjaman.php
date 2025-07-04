<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengembalianPinjaman extends Model
{
    use HasFactory;

    protected $table = 'pengembalian_pinjamans';

    protected $guarded = ['id'];

    // Relasi dengan Pinjaman
    public function pinjaman()
    {
        return $this->belongsTo(Pinjamans::class, 'pinjamans_id');
    }

    // Format nominal cicilan
    public function getNominalCicilanAttribute($value)
    {
        return 'Rp. ' . number_format($value, 0, ',', '.');
    }

    // Mendapatkan nilai asli nominal cicilan
    public function getOriginalNominalCicilanAttribute()
    {
        return $this->attributes['nominal_cicilan'];
    }

    // Format tanggal
    public function getTglPengembalianSementaraAttribute($value)
    {
        return date('d F Y', strtotime($value));
    }
    // Mendapatkan nilai asli tanggal pengembalian sementara
    public function getOriginalTglPengembalianSementaraAttribute()
    {
        return $this->attributes['tgl_pengembalian_sementara'];
    }
}
