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
        Schema::create('keuangan', function (Blueprint $table) {
            $table->id('id_keuangan');
            $table->date('tanggal');
            // 'jenis' untuk kategori uangnya
            $table->enum('jenis', ['kas', 'profit', 'omzet'])->default('omzet');
            // 'tipe' untuk arah uang (Masuk/Keluar)
            $table->enum('tipe', ['masuk', 'keluar']);
            // Kita pakai 'jumlah' sebagai kolom utama nominal uang
            $table->decimal('jumlah', 14, 2);
            $table->text('keterangan')->nullable();
            
            // Kolom tracking sumber (buat koneksi ke setoran reseller)
            $table->string('sumber_type', 50)->nullable(); 
            $table->integer('sumber_id')->nullable();      
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};