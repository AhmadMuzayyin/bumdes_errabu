<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengembalianPinjamans extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi dengan Pinjaman
    public function pinjamans()
    {
        return $this->belongsTo(Pinjamans::class);
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

    // Mendapatkan nilai asli tanggal
    public function getOriginalTglPengembalianSementaraAttribute()
    {
        return $this->attributes['tgl_pengembalian_sementara'];
    }
}
