<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaFotoCopy extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public function getHargaAttribute($value)
    {
        return 'Rp. ' . number_format($value, 0, ',', '.');
    }
    public function getOriginalHargaAttribute()
    {
        return (int) str_replace('.', '', $this->attributes['harga']);
    }
    public function setHargaAttribute($value)
    {
        $this->attributes['harga'] = str_replace('.', '', $value);
    }
}
