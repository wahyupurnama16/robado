<?php

namespace App\Models;

use App\Models\Produksi;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';

    protected $fillable = [
        'id_produk',
        'id_user',
        'nama',
        'noWa',
        'jumlahPemesanan',
        'totalHarga',
        'tanggalPengiriman',
        'jamPengiriman',
        'statusPembayaran',
        'statusPengiriman',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produksi::class, 'id_produk', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
