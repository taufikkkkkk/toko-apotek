<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $fillable = [
        'nama_pasien',
        'no_hp',
        'catatan',
        'foto_resep',
        'status',
    ];
}