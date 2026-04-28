<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;

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

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'reseller', // Set otomatis sebagai reseller
        ]);

        return redirect()->route('admin.reseller.index')->with('success', 'Reseller berhasil didaftarkan!');
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Opsional: Cek jika reseller masih punya pesanan aktif sebelum dihapus
            // if($user->pesanans()->where('status', '!=', 'selesai')->exists()) {
            //     return redirect()->back()->with('error', 'Tidak bisa menghapus mitra yang memiliki pesanan aktif!');
            // }

            $user->delete();

            return redirect()->back()->with('success', 'Kemitraan reseller telah berhasil dihentikan.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Sistem gagal menghapus data mitra: ' . $e->getMessage());
        }
    }
}