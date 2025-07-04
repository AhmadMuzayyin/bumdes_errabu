<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranSimpanPinjam extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Format jumlah
    public function getJumlahAttribute($value)
    {
        return 'Rp. ' . number_format($value, 0, ',', '.');
    }

    // Mendapatkan nilai asli jumlah
    public function getOriginalJumlahAttribute()
    {
        return $this->attributes['jumlah'];
    }

    // Format tanggal
    public function getTglPengeluaranAttribute($value)
    {
        return date('d F Y', strtotime($value));
    }
    // Mendapatkan nilai asli tanggal
    public function getOriginalTglPengeluaranAttribute()
    {
        return $this->attributes['tgl_pengeluaran'];
    }
}
