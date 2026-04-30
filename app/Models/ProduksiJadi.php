<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduksiJadi extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'size_9x30',
        'size_8x30',
        'size_10x35',
        'total_produksi',
        'catatan'
    ];

    // Boot method untuk otomatis hitung total sebelum save
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->total_produksi = $model->size_9x30 + $model->size_8x30 + $model->size_10x35;
        });
        static::updating(function ($model) {
            $model->total_produksi = $model->size_9x30 + $model->size_8x30 + $model->size_10x35;
        });
    }
}