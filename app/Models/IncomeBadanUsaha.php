<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeBadanUsaha extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function getNominalAttribute($value)
    {
        return "Rp. " . number_format($value, 0, ',', '.');
    }
    public function getOriginalNominalAttribute()
    {
        return $this->attributes['nominal'];
    }
}
