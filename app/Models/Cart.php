<?php

namespace App\Models;

use App\Models\Produksi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cart extends Model
{
    protected $fillable = [
        'id_produk',
        'id_user',
        'jumlahPemesanan',
    ];

    public function produk(): HasOne
    {
        return $this->HasOne(Produksi::class, 'id', 'id_produk');
    }
}
