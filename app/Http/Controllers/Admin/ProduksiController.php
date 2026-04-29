<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Memperbaiki namespace
use Exception; // Menambahkan import Exception
use App\Notifications\DataTerbaruNotification;
use Illuminate\Support\Facades\Auth;

class ProduksiController extends Controller
{
    /**
     * Menampilkan riwayat produksi
     */
    public function index()
    {
        $data = Produksi::latest()->get();
        return view('admin.produksi.index', compact('data'));
    }

    /**
     * Form tambah produksi
     */
    public function create()
    {
        return view('admin.produksi.create');
    }

    /**
     * Simpan data produksi baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jumlah_produksi' => 'required|integer|min:1',
            'kedelai_kg' => 'required|integer|min:1',
            'plastik_kg' => 'required|integer|min:1',
        ]);

        try {
            Produksi::create($request->all());
            return redirect('/admin/produksi')->with('success', 'Sesi produksi berhasil dicatat dalam sistem!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }

            // Kirim notifikasi ke semua reseller yang terdaftar
            $resellers = User::where('role', 'reseller')->get();
            foreach ($resellers as $reseller) {
                $reseller->notify(new DataTerbaruNotification('Sesi produksi baru telah dicatat: ' . $request->jumlah_produksi . ' unit pada tanggal ' . $request->tanggal));
            }
    }

    /**
     * Hapus data produksi (Swipe Delete)
     */
    public function destroy($id)
    {
        try {
            $produksi = Produksi::findOrFail($id);
            
            // 1. Hapus foto dari storage jika sistem produksi kamu menggunakan foto
            if ($produksi->foto) {
                if (Storage::disk('public')->exists($produksi->foto)) {
                    Storage::disk('public')->delete($produksi->foto);
                }
            }
            
            // 2. Hapus data dari database
            $produksi->delete();
            
            // Menggunakan redirect back agar user tetap di halaman yang sama setelah swipe delete
            return redirect()->back()->with('success', 'Data produksi berhasil dimusnahkan!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Sistem gagal menghapus data: ' . $e->getMessage());
        }
    }
}