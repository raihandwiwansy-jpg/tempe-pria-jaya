<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';

    protected $fillable = [
        'id_reseller_assign',
        'nama_pemesan',
        'alamat_pemesan',
        'jumlah',
        'ukuran',
        'tanggal_kirim',
        'status'
    ];

    // Relasi balik ke User (Reseller)
    public function reseller()
    {
        return $this->belongsTo(User::class, 'id_reseller_assign', 'id');
    }
}