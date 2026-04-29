<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use App\Models\User; // <--- WAJIB TAMBAH INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\DataTerbaruNotification;
use Illuminate\Support\Facades\Auth;

class KeuanganController extends Controller
{
    /**
     * Tampil Laporan Keuangan
     */
    public function index()
    {
        try {
            // Mengambil data terbaru berdasarkan tanggal transaksi dan waktu input
            $data = Keuangan::orderBy('tanggal', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->get();

            // Kalkulasi Saldo Kas (Hanya yang bertipe 'masuk' dikurangi 'keluar')
            $total_masuk = Keuangan::where('tipe', 'masuk')->sum('jumlah');
            $total_keluar = Keuangan::where('tipe', 'keluar')->sum('jumlah');
            
            $total_kas = $total_masuk - $total_keluar;

            return view('admin.keuangan.index', compact('data', 'total_kas'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan Form Tambah Transaksi
     */
    public function create()
    {
        return view('admin.keuangan.create');
    }

    /**
     * Simpan Transaksi Manual (Admin)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'tanggal'    => 'required|date',
            'jenis'      => 'required|in:kas,profit,omzet',
            'tipe'       => 'required|in:masuk,keluar',
            'jumlah'     => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            // 2. Eksekusi Simpan ke Database
            Keuangan::create([
                'tanggal'    => $request->tanggal,
                'jenis'      => $request->jenis,
                'tipe'       => $request->tipe,
                'jumlah'     => $request->jumlah,
                'keterangan' => $request->keterangan,
            ]);

            // 3. Persiapkan Pesan Notifikasi
            $nominal = number_format($request->jumlah, 0, ',', '.');
            $pesanNotif = "Transaksi Keuangan: " . strtoupper($request->jenis) . " ({$request->tipe}) sebesar Rp " . $nominal;

            // 4. KIRIM NOTIFIKASI
            // Notif ke Admin (Wajib agar log muncul di Dashboard Admin)
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification($pesanNotif));
            }

            // Notif ke Reseller (Hanya jika kamu ingin reseller tahu ada update keuangan pusat)
            $resellers = User::where('role', 'reseller')->get();
            foreach ($resellers as $reseller) {
                $reseller->notify(new DataTerbaruNotification("Update Keuangan: " . $pesanNotif));
            }

            // 5. SELESAI & PINDAH HALAMAN
            return redirect()->route('admin.keuangan.index')
                             ->with('success', 'Transaksi keuangan berhasil disimpan!');
            
        } catch (\Exception $e) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }
}