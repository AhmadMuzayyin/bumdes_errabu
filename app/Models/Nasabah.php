<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi dengan Simpanan
    public function simpanan()
    {
        return $this->hasMany(Simpanan::class);
    }

    // Relasi dengan Pengambilan Simpanan
    public function pengambilanSimpanan()
    {
        return $this->hasMany(PengambilanSimpanan::class);
    }

    // Relasi dengan Pinjaman
    public function pinjaman()
    {
        return $this->hasMany(Pinjamans::class);
    }

    // Format tanggal
    public function getTglBergabungFormatedAttribute($value)
    {
        return date('d F Y', strtotime($value));
    }
}
