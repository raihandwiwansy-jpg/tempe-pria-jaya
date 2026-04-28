<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('setoran', function (Blueprint $table) {
        $table->id();
        // Menghubungkan ke user (reseller)
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->decimal('jumlah_setoran', 15, 2);
        $table->date('tanggal_setoran');
        $table->string('bukti_pembayaran')->nullable(); // Nama file foto
        $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setorans');
    }
};
