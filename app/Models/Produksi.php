<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    protected $table = 'produksis';
    protected $fillable = [
        'namaProduk',
        'jumlahProduk',
        'gambar',
        'hargaProduk',
    ];
}
