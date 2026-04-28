<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Produksi extends Model
{
    use HasFactory;
    protected $table = 'produksis'; // pastikan nama tabel sesuai DB
    protected $primaryKey = 'id'; // atau id_produksi sesuai migrasi kamu

    protected $fillable = [
        'tanggal',
        'jumlah_produksi',
        'kedelai_kg',
        'plastik_kg',
        'catatan'
    ];
}