<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KatalogReseller extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'nama_display',
        'kategori',
        'harga_jual_reseller',
        'deskripsi',
        'foto'
    ];

    // Relasi balik ke Master Barang untuk ambil stok_pusat
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}