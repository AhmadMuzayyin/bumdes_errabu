<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BriLinkSetorTunai extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'jumlah',
        'nama',
        'norek',
        'nominal',
        'tgl_setor_tunai'
    ];
}
