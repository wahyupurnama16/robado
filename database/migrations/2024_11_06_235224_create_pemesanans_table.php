<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->string('id_produk');
            $table->string('id_user');
            $table->string('namaPemesan')->nullable();
            $table->string('noWa')->nullable();
            $table->string('jumlahPemesanan')->nullable();
            $table->string('totalHarga')->nullable();
            $table->date('tanggalPengiriman')->nullable();
            $table->time('jamPengiriman')->nullable();
            $table->boolean('statusPembayaran')->nullable();
            $table->boolean('statusPengiriman')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
