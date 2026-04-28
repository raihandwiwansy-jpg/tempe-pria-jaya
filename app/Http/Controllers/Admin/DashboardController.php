<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // 1. Statistik Utama (Hari Ini)
            $totalPesanan = Pemesanan::whereDate('created_at', today())->count();
            
            // Menggunakan query builder untuk tabel produksi
            $totalProduksi = DB::table('produksi')
                ->whereDate('tanggal', today())
                ->sum('jumlah_produksi') ?? 0;

            // Menghitung user dengan role reseller
            $totalReseller = User::where('role', 'reseller')->count();
            
            // 2. Keuangan (Total Saldo dari tabel keuangan)
            $totalKeuangan = DB::table('keuangan')->sum('nominal') ?? 0;

            // 3. Stok Bahan Baku Kritis (Stok di bawah 10 unit/kg)
            $stokKritis = Barang::where('stok', '<', 10)->get();

            // 4. Pesanan Terbaru (Ambil 5 data terakhir beserta data resellernya)
            // Pastikan di model Pemesanan sudah ada function reseller()
            $pesananTerbaru = Pemesanan::with('reseller')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            return view('admin.dashboard', compact(
                'totalPesanan', 
                'totalProduksi', 
                'totalReseller', 
                'totalKeuangan',
                'stokKritis',
                'pesananTerbaru'
            ));

        } catch (\Exception $e) {
            // Jika ada tabel yang belum dibuat atau query error, 
            // kirim nilai default agar halaman tidak langsung 500/error
            return view('admin.dashboard', [
                'totalPesanan' => 0,
                'totalProduksi' => 0,
                'totalReseller' => 0,
                'totalKeuangan' => 0,
                'stokKritis' => collect([]),
                'pesananTerbaru' => collect([]),
                'error' => 'Beberapa tabel mungkin belum siap: ' . $e->getMessage()
            ]);
        }
    }
}