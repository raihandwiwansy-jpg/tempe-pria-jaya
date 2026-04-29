<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Notifications\DataTerbaruNotification;
use Illuminate\Support\Facades\Auth;

class ResellerController extends Controller
{
    public function index()
    {
        // Ambil user yang rolenya reseller
        $data = User::where('role', 'reseller')->get();
        return view('admin.reseller.index', compact('data'));
    }

    public function create()
    {
        return view('admin.reseller.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        try {
            // 1. Simpan Reseller Baru
            $newReseller = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'reseller', 
            ]);

            // 2. Persiapkan Pesan
            $pesanNotif = "Mitra Baru Bergabung: " . $request->name . " (" . $request->email . ")";

            // 3. KIRIM NOTIFIKASI
            // Notif ke Admin (Wajib agar log muncul di Dashboard Admin)
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification($pesanNotif));
            }

            // Notif ke Reseller yang baru daftar (Welcome Message)
            $newReseller->notify(new DataTerbaruNotification("Selamat Datang di Sistem Tempe Pria Jaya! Akun Anda telah aktif."));

            // 4. SELESAI
            return redirect()->route('admin.reseller.index')->with('success', 'Reseller berhasil didaftarkan!');

        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftarkan reseller: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $namaReseller = $user->name;
            
            $user->delete();

            // Kirim notif ke Admin kalau ada mitra yang dihapus
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification("Kemitraan dengan {$namaReseller} telah dihentikan."));
            }

            return redirect()->back()->with('success', 'Kemitraan reseller telah berhasil dihentikan.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Sistem gagal menghapus data mitra: ' . $e->getMessage());
        }
    }
}