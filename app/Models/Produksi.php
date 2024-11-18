<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    protected $table = 'produk';
    protected $fillable = [
        'namaProduk',
        'jumlahProduk',
        'gambar',
        'hargaProduk',
        'deskripsi',
    ];
}
