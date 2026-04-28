<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawans';
    protected $primaryKey = 'id_karyawan';
    
    // Set ke false jika di database tidak ada kolom created_at & updated_at
    public $timestamps = false; 

    protected $fillable = [
        'nama',
        'alamat',
        'gaji_perbulan'
    ];
}