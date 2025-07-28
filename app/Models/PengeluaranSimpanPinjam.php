<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranSimpanPinjam extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public function getTglPengeluaranAttribute($value)
    {
        return date('d F Y', strtotime($value));
    }
    public function getOriginalTglPengeluaranAttribute()
    {
        return $this->attributes['tgl_pengeluaran'];
    }
}
