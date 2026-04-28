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
        // 1. Kita cek dulu, kalau tabel 'absensi' atau 'absensis' sudah ada, kita hapus 
        // supaya tidak bentrok dengan migrasi lama kamu.
        Schema::dropIfExists('absensi');

        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            
            // 2. Pastikan tipe data ini sama dengan primary key di tabel karyawans
            $table->unsignedBigInteger('id_karyawan');
            
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'tidak'])->default('hadir');
            $table->timestamps();

            // 3. Referensi ke 'karyawans' (pakai S) sesuai dengan log migrasi kamu tadi
            $table->foreign('id_karyawan')
                  ->references('id_karyawan')
                  ->on('karyawans') 
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};