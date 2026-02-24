<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('so_products', function (Blueprint $table) {
            // Menambahkan kolom keterangan (boleh kosong/nullable) setelah kolom jumlah_akhir
            $table->string('keterangan')->nullable()->after('jumlah_akhir');
        });
    }

    public function down(): void
    {
        Schema::table('so_products', function (Blueprint $table) {
            $table->dropColumn('keterangan');
        });
    }
};
