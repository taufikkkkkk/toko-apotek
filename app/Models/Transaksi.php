<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'kode_transaksi',
        'nama_pembeli',
        'no_hp',
        'alamat',
        'metode_pembayaran',
        'kurir',
        'ongkir',
        'total_harga',
        'status',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}