<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use App\Models\User; // <--- WAJIB TAMBAH INI BIAR GAK ERROR
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;
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
            // 1. Simpan data produksi
            Produksi::create($request->all());

            // 2. Siapkan Pesan Notifikasi
            $pesan = "Produksi Baru: {$request->jumlah_produksi} unit (Kedelai: {$request->kedelai_kg}kg, Plastik: {$request->plastik_kg}kg)";

            // 3. KIRIM NOTIFIKASI
            // Notif ke Admin (Agar log muncul di dashboard)
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification($pesan));
            }

            // Notif ke semua Reseller (Agar mereka tahu stok bakal bertambah)
            $resellers = User::where('role', 'reseller')->get();
            foreach ($resellers as $reseller) {
                $reseller->notify(new DataTerbaruNotification("Info Produksi: Stok baru sedang diproses sebanyak {$request->jumlah_produksi} unit."));
            }

            // 4. SELESAI & REDIRECT
            return redirect('/admin/produksi')->with('success', 'Sesi produksi berhasil dicatat dalam sistem!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus data produksi (Swipe Delete)
     */
    public function destroy($id)
    {
        try {
            $produksi = Produksi::findOrFail($id);
            
            // 1. Hapus foto dari storage jika ada
            if ($produksi->foto) {
                if (Storage::disk('public')->exists($produksi->foto)) {
                    Storage::disk('public')->delete($produksi->foto);
                }
            }
            
            // 2. Hapus data dari database
            $produksi->delete();
            
            // Notif ke Admin kalau ada data yang dihapus
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification("Data produksi tanggal {$produksi->tanggal} telah dihapus."));
            }
            
            return redirect()->back()->with('success', 'Data produksi berhasil dimusnahkan!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Sistem gagal menghapus data: ' . $e->getMessage());
        }
    }
}