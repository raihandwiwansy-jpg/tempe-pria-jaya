<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    use HasFactory;

    // Nama tabel sesuai migrasi kamu
    protected $table = 'keuangan';

    // Karena kamu pakai 'id_keuangan' di migrasi, Laravel wajib dikasih tahu:
    protected $primaryKey = 'id_keuangan';

    // Kolom yang boleh diisi
    protected $fillable = [
        'tanggal',
        'jenis',
        'tipe',
        'jumlah',
        'keterangan',
        'sumber_type',
        'sumber_id'
    ];

    // Opsional: Memastikan created_at dibaca sebagai object Carbon agar bisa di-format di view
    protected $dates = ['tanggal', 'created_at', 'updated_at'];
}