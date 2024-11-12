<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function ($table) {
            $table->string('jenisPerusahaan')->after("password");
            $table->string('alamat')->after("jenisPerusahaan");
            $table->string('noWa')->after("alamat");
            $table->enum('role', ['pelanggan', 'owner', 'admin', 'baker'])->after("noWa");;
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('role');
            $table->dropColumn('alamat');
            $table->dropColumn('noWa');
            $table->dropColumn('jenisPerusahaan');
        });

    }
};
