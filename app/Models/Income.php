<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function badan_usaha()
    {
        return $this->belongsTo(BadanUsaha::class);
    }

    public function getTanggalAttribute($value)
    {
        return date('d F Y', strtotime($value));
    }
    public function getOriginalTanggalAttribute($value)
    {
        return $this->attributes['tanggal'];
    }

    public function getNominalAttribute($value)
    {
        return 'Rp. ' . number_format($value, 0, ',', '.');
    }

    public function getOriginalNominalAttribute($value)
    {
        return $this->attributes['nominal'];
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d F Y', strtotime($value));
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('d F Y', strtotime($value));
    }
}
