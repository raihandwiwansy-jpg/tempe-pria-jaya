<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\User; 
use Illuminate\Http\Request;
use Exception;
use App\Notifications\DataTerbaruNotification;
use Illuminate\Support\Facades\Auth;

class PesananAdminController extends Controller
{
    /**
     * Menampilkan semua pesanan dari semua reseller
     */
    public function index()
    {
        // Eager loading 'reseller' agar performa ringan saat ambil nama reseller
        $pesanans = Pemesanan::with('reseller')
                    ->latest()
                    ->get();

        return view('admin.pesanan.index', compact('pesanans'));
    }

    /**
     * Menampilkan form tambah pesanan (Sisi Admin)
     */
    public function create()
    {
        // Mengambil user dengan role reseller untuk pilihan dropdown
        $resellers = User::where('role', 'reseller')->get();
        
        return view('admin.pesanan.create', compact('resellers'));
    }

    /**
     * Menyimpan pesanan baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_reseller_assign' => 'required|exists:users,id',
            'nama_pemesan'       => 'required|string|max:255',
            'alamat_pemesan'     => 'required|string',
            'jumlah'             => 'required|numeric|min:1',
            'ukuran'             => 'required|string',
            'tanggal_kirim'      => 'required|date',
        ]);

        try {
            // 1. Simpan Pesanan
            $pesanan = Pemesanan::create([
                'id_reseller_assign' => $request->id_reseller_assign,
                'nama_pemesan'       => $request->nama_pemesan,
                'alamat_pemesan'     => $request->alamat_pemesan,
                'jumlah'             => $request->jumlah,
                'ukuran'             => $request->ukuran,
                'tanggal_kirim'      => $request->tanggal_kirim,
                'status'             => 'pending', 
            ]);

            // 2. KIRIM NOTIFIKASI
            // Notif ke Admin (Owner)
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification("Pesanan masuk baru: {$request->nama_pemesan} ({$request->jumlah} Pcs)"));
            }

            // Notif ke Reseller yang bersangkutan saja (Biar lebih spesifik)
            $resellerTarget = User::find($request->id_reseller_assign);
            if ($resellerTarget) {
                $resellerTarget->notify(new DataTerbaruNotification("Anda mendapat tugas pesanan baru untuk: " . $request->nama_pemesan));
            }

            return redirect()->route('admin.pesanan.index')->with('success', 'Pesanan baru berhasil dipublish ke sistem!');

        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambah pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Mengupdate status pesanan secara realtime/cepat
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,dikirim,selesai'
        ]);

        try {
            $pesanan = Pemesanan::findOrFail($id);
            $pesanan->update([
                'status' => $request->status
            ]);

            // KIRIM NOTIFIKASI PERUBAHAN STATUS
            // Notif ke Admin
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification("Status Pesanan #{$id} diubah menjadi: " . strtoupper($request->status)));
            }

            // Notif ke Reseller pemilik pesanan
            $reseller = User::find($pesanan->id_reseller_assign);
            if ($reseller) {
                $reseller->notify(new DataTerbaruNotification("Update Pesanan: Pelanggan {$pesanan->nama_pemesan} sekarang statusnya {$request->status}"));
            }

            return redirect()->back()->with('success', 'Status pesanan #' . $id . ' berhasil diperbarui!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal update status: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus/Membatalkan pesanan
     */
    public function destroy($id)
    {
        try {
            $pesanan = Pemesanan::findOrFail($id);
            $nama = $pesanan->nama_pemesan;
            $pesanan->delete();

            // Notif Pembatalan ke Admin
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification("Pesanan {$nama} telah dihapus/dibatalkan."));
            }

            return redirect()->back()->with('success', 'Pesanan telah berhasil dibatalkan dan dihapus!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pesanan: ' . $e->getMessage());
        }
    }
}