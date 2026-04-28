<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs'; // pastikan nama tabel sesuai DB
    protected $primaryKey = 'id'; // atau id_barang sesuai migrasi kamu
    
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'stok_pusat',
        'harga_modal'
    ];

    // Relasi ke Katalog (Satu barang master bisa punya satu tampilan katalog)
    public function katalog()
    {
        return $this->hasOne(KatalogReseller::class, 'barang_id');
    }
}