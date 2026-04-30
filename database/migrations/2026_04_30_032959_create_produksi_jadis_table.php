<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('produksi_jadis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            // Ukuran-ukuran tempe
            $table->integer('size_9x30')->default(0);
            $table->integer('size_8x30')->default(0);
            $table->integer('size_10x35')->default(0);
            $table->integer('total_produksi')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produksi_jadis');
    }
};