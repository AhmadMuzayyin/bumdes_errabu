<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function getNominalAttribute($value)
    {
        return 'Rp. ' . number_format($value, 0, ',', '.');
    }

    public function getOriginalNominalAttribute()
    {
        return $this->attributes['nominal'];
    }

    public function scopeWhereTipeTransaksi($query, $kategori)
    {
        return $query;
    }
}
