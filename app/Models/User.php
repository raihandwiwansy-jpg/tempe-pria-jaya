<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Penting untuk membedakan admin/reseller
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi ke Pemesanan (Sesuai ERD)
     * Satu Reseller (User) memiliki banyak Pemesanan
     */
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_reseller_assign', 'id');
    }
}