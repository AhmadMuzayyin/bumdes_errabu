<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjamans extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi dengan Nasabah
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    // Relasi dengan Setting Pinjaman
    public function settingPinjaman()
    {
        return $this->belongsTo(SettingPinjaman::class, 'setting_pinjaman_id');
    }

    // Relasi dengan Pengembalian Pinjaman
    public function pengembalianPinjaman()
    {
        return $this->hasMany(PengembalianPinjamans::class, 'pinjamans_id');
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
    public function getTglPinjamAttribute($value)
    {
        return date('d F Y', strtotime($value));
    }

    // Mendapatkan nilai asli tanggal
    public function getOriginalTglPinjamAttribute()
    {
        return $this->attributes['tgl_pinjam'];
    }
}
