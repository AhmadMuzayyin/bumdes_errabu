<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BriLinkTarikTunai extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'nama',
        'norek',
        'norek_tujuan',
        'nominal',
        'tgl_tarik_tunai'
    ];
}
