<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingPinjaman extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'setting_pinjamen'; // Sesuaikan dengan nama table di migration

    // Format bunga
    public function getBungaAttribute($value)
    {
        return $value . '%';
    }

    // Mendapatkan nilai asli bunga
    public function getOriginalBungaAttribute()
    {
        return $this->attributes['bunga'];
    }
}
