<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranFotoCopy extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public function harga_foto_copy()
    {
        return $this->belongsTo(HargaFotoCopy::class);
    }
}
