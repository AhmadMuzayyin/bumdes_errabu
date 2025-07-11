<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengambilanSimpanan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi dengan Nasabah
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    // Format nominal
    public function getNominalAttribute($value)
    {
        return 'Rp. ' . number_format($value, 0, ',', '.');
    }

    // Mendapatkan nilai asli nominal
    public function getOriginalNominalAttribute()
    {
        return $this->attributes['nominal'];
    }

    // Mendapatkan nilai asli tgl pengambilan
    public function getOriginalTglPengambilanAttribute()
    {
        return $this->attributes['tgl_pengambilan'];
    }

    // Format tanggal
    public function getTglPengambilanAttribute($value)
    {
        return date('d F Y', strtotime($value));
    }

    // Scope untuk kategori (berdasarkan aturan bisnis, kategori tidak ada dalam migration)
    public function scopeWhereTipeTransaksi($query, $kategori)
    {
        return $query; // Sebenarnya tanpa filter kategori karena tidak ada kolom kategori
    }
}
