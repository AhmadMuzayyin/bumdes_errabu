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

    // Relasi dengan Pengembalian Pinjaman
    public function pengembalianPinjaman()
    {
        return $this->hasMany(PengembalianPinjaman::class, 'pinjamans_id');
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

    // Format nominal pengembalian
    public function getNominalPengembalianAttribute($value)
    {
        if ($value) {
            return 'Rp. ' . number_format($value, 0, ',', '.');
        }
        return null;
    }

    // Format tanggal
    public function getTglPinjamAttribute($value)
    {
        return date('d F Y', strtotime($value));
    }
    // original tanggal pinjam
    public function getOriginalTglPinjamAttribute()
    {
        return $this->attributes['tgl_pinjam'];
    }

    // Mendapatkan nilai bunga pinjaman
    public function getBungaPinjamanAttribute()
    {
        $nominal = $this->attributes['nominal'];
        $totalPengembalian = $this->attributes['nominal_pengembalian'];

        // Bunga adalah selisih antara total pengembalian dan nominal pokok
        $bunga = $totalPengembalian - $nominal;

        return 'Rp. ' . number_format($bunga, 0, ',', '.');
    }

    // Mendapatkan persentase bunga yang digunakan
    public function getPersentaseBungaAttribute()
    {
        $nominal = $this->attributes['nominal'];
        $totalPengembalian = $this->attributes['nominal_pengembalian'];

        if ($nominal > 0) {
            $bunga = $totalPengembalian - $nominal;
            $persentase = ($bunga / $nominal) * 100;
            return number_format($persentase, 1) . '%';
        }

        return '0%';
    }
}
