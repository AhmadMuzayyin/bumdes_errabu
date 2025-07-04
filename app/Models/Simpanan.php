<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
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

    // Format tanggal
    public function getTglSimpanFormatedAttribute($value)
    {
        return date('d F Y', strtotime($value));
    }
}
