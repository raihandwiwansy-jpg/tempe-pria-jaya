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
    Schema::create('produksis', function (Blueprint $table) {
        $table->id();

        $table->date('tanggal');

        $table->integer('jumlah_produksi'); // kg tempe
        $table->integer('kedelai_kg'); // bahan utama
        $table->integer('plastik_kg'); // bahan bungkus

        $table->text('catatan')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksis');
    }
};
