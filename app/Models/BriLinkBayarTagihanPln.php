<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BriLinkBayarTagihanPln extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'id_pelanggan',
        'nominal',
        'tgl_transaksi'
    ];
}
