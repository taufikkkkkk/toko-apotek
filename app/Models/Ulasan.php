<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $fillable = [
        'obat_id',
        'nama',
        'rating',
        'komentar',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}