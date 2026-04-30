<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Notifications\DataTerbaruNotification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProduksiController extends Controller
{
    /**
     * Menampilkan riwayat produksi dengan rekapan lengkap (Harian, Mingguan, Bulanan, Tahunan)
     */
    public function index()
    {
        try {
            // Ambil semua data produksi terbaru
            $data = Produksi::latest()->get();

            // --- 1. REKAPAN HARIAN (TODAY) ---
            $hariIni = Produksi::whereDate('tanggal', Carbon::today());
            $statHari = [
                'prod'    => $hariIni->sum('jumlah_produksi'),
                'kedelai' => $hariIni->sum('kedelai_kg'),
                'plastik' => $hariIni->sum('plastik_kg'),
            ];

            // --- 2. REKAPAN MINGGU INI (THIS WEEK) ---
            $mingguIni = Produksi::whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            $statMinggu = [
                'prod'    => $mingguIni->sum('jumlah_produksi'),
                'kedelai' => $mingguIni->sum('kedelai_kg'),
                'plastik' => $mingguIni->sum('plastik_kg'),
            ];

            // --- 3. REKAPAN BULAN INI (THIS MONTH) ---
            $bulanIni = Produksi::whereMonth('tanggal', Carbon::now()->month)
                                ->whereYear('tanggal', Carbon::now()->year);
            $statBulan = [
                'prod'    => $bulanIni->sum('jumlah_produksi'),
                'kedelai' => $bulanIni->sum('kedelai_kg'),
                'plastik' => $bulanIni->sum('plastik_kg'),
            ];

            // --- 4. REKAPAN TAHUN INI (THIS YEAR) ---
            $tahunIni = Produksi::whereYear('tanggal', Carbon::now()->year);
            $statTahun = [
                'prod'    => $tahunIni->sum('jumlah_produksi'),
                'kedelai' => $tahunIni->sum('kedelai_kg'),
                'plastik' => $tahunIni->sum('plastik_kg'),
            ];

            return view('admin.produksi.index', compact(
                'data', 
                'statHari', 
                'statMinggu', 
                'statBulan', 
                'statTahun'
            ));

        } catch (Exception $e) {
            return back()->with('error', 'Sistem gagal memuat data produksi: ' . $e->getMessage());
        }
    }

    /**
     * Form tambah produksi
     */
    public function create()
    {
        return view('admin.produksi.create');
    }

    /**
     * Simpan data produksi baru dan kirim notifikasi
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
            // 1. Simpan data ke database
            Produksi::create($request->all());

            // 2. Notifikasi untuk Admin
            $pesanAdmin = "Produksi Baru: {$request->jumlah_produksi} unit (Bahan: {$request->kedelai_kg}kg kedelai)";
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification($pesanAdmin));
            }

            // 3. Notifikasi untuk semua Reseller
            $resellers = User::where('role', 'reseller')->get();
            foreach ($resellers as $reseller) {
                $reseller->notify(new DataTerbaruNotification("Info Stok: Produksi baru sebanyak {$request->jumlah_produksi} unit selesai diproses."));
            }

            return redirect('/admin/produksi')->with('success', 'Data produksi berhasil ditambahkan ke dalam sistem!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus data produksi dan bersihkan storage jika ada foto
     */
    public function destroy($id)
    {
        try {
            $produksi = Produksi::findOrFail($id);
            
            // Hapus file foto dari folder storage jika ada
            if ($produksi->foto) {
                if (Storage::disk('public')->exists($produksi->foto)) {
                    Storage::disk('public')->delete($produksi->foto);
                }
            }
            
            // Hapus record dari database
            $produksi->delete();
            
            // Notifikasi penghapusan ke admin
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification("Peringatan: Data produksi tanggal {$produksi->tanggal} telah dihapus oleh sistem."));
            }
            
            return redirect()->back()->with('success', 'Sesi produksi berhasil dihapus dari riwayat.');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Sistem gagal menghapus data: ' . $e->getMessage());
        }
    }
}