<?php

namespace App\Models;

// Ambil fitur-fitur penting Laravel
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; // Ini WAJIB untuk fitur notifikasi
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Nama tabel (Opsional, pastikan sesuai dengan database kamu)
     */
    protected $table = 'users';

    /**
     * Kolom yang boleh diisi (Mass Assignment)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Admin atau Reseller
    ];

    /**
     * Kolom yang disembunyikan (untuk keamanan)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke Pemesanan (Sesuai ERD kamu)
     * Satu Reseller (User) memiliki banyak Pemesanan
     */
    public function pemesanan()
    {
        // Pastikan 'id_reseller_assign' adalah nama kolom di tabel pemesanan
        // Dan 'id' adalah nama kolom primary key di tabel users
        return $this->hasMany(Pemesanan::class, 'id_reseller_assign', 'id');
    }

    /**
     * FUNGSI TAMBAHAN (Biar makin keren)
     * Cek apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}