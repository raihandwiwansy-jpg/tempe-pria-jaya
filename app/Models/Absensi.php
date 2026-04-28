<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'status',
        'potongan'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
