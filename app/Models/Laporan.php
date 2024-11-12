<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'id_produk',
        'jumlahProduksi',
        'statusProduksi',
    ];
}
