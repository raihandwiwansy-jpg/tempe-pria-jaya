<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek dulu apakah tabel barangs sudah ada sebelum membuat
        if (!Schema::hasTable('barangs')) {
            Schema::create('barangs', function (Blueprint $table) {
                $table->id();
                $table->string('kode_barang')->unique();
                $table->string('nama_barang');
                $table->integer('stok_pusat')->default(0);
                $table->decimal('harga_modal', 12, 2);
                $table->timestamps();
            });
        }

        // Cek dulu apakah tabel katalog_resellers sudah ada sebelum membuat
        if (!Schema::hasTable('katalog_resellers')) {
            Schema::create('katalog_resellers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
                $table->string('nama_display'); 
                $table->string('kategori');
                $table->decimal('harga_jual_reseller', 12, 2);
                $table->text('deskripsi')->nullable();
                $table->string('foto')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Urutan drop: tabel anak dulu baru tabel induk
        Schema::dropIfExists('katalog_resellers');
        Schema::dropIfExists('barangs');
    }
};