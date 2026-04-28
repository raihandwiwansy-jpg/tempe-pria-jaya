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
       // database/migrations/xxxx_create_pemesanan_table.php
Schema::create('pemesanan', function (Blueprint $table) {
    $table->id('id_pemesanan');
    $table->foreignId('id_reseller_assign')->constrained('users'); // Menghubungkan ke tabel users
    $table->string('nama_pemesan');
    $table->text('alamat_pemesan');
    $table->integer('jumlah');
    $table->string('ukuran');
    $table->date('tanggal_kirim');
    $table->enum('status', ['pending', 'diproses', 'dikirim', 'selesai'])->default('pending');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};
