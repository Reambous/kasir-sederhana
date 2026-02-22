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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->index();
            $table->date('tanggal');
            $table->string('nama_buyer')->nullable();
            $table->enum('metode_pembayaran', ['cash', 'non_cash']);
            $table->integer('uang_diterima')->default(0);
            $table->enum('status', ['on_progress', 'done'])->default('on_progress'); // Integer diganti enum agar jelas
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('potongan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
