<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiGudang extends Model
{
    protected $fillable = [
        'barang_id',
        'tipe',
        'jumlah',
        'tanggal',
        'keterangan'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
