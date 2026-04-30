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
     * Menampilkan riwayat produksi dengan rekapan
     */
    public function index()
    {
        try {
            $data = Produksi::latest()->get();

            // 1. Rekapan Harian
            $hariIni = Produksi::whereDate('tanggal', Carbon::today());
            $prodHariIni = $hariIni->sum('jumlah_produksi');
            $kedelaiHariIni = $hariIni->sum('kedelai_kg');

            // 2. Rekapan Minggu Ini
            $mingguIni = Produksi::whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            $prodMingguIni = $mingguIni->sum('jumlah_produksi');

            // 3. Rekapan Bulan Ini
            $bulanIni = Produksi::whereMonth('tanggal', Carbon::now()->month)
                                ->whereYear('tanggal', Carbon::now()->year);
            $prodBulanIni = $bulanIni->sum('jumlah_produksi');
            $kedelaiBulanIni = $bulanIni->sum('kedelai_kg');
            $plastikBulanIni = $bulanIni->sum('plastik_kg');

            // 4. Rekapan Tahun Ini
            $prodTahunIni = Produksi::whereYear('tanggal', Carbon::now()->year)->sum('jumlah_produksi');

            return view('admin.produksi.index', compact(
                'data', 
                'prodHariIni', 
                'kedelaiHariIni', 
                'prodMingguIni', 
                'prodBulanIni', 
                'kedelaiBulanIni', 
                'plastikBulanIni', 
                'prodTahunIni'
            ));
        } catch (Exception $e) {
            return back()->with('error', 'Gagal memuat data produksi: ' . $e->getMessage());
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
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification($pesan));
            }

            $resellers = User::where('role', 'reseller')->get();
            foreach ($resellers as $reseller) {
                $reseller->notify(new DataTerbaruNotification("Info Produksi: Stok baru diproses {$request->jumlah_produksi} unit."));
            }

            return redirect('/admin/produksi')->with('success', 'Sesi produksi berhasil dicatat!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus data produksi
     */
    public function destroy($id)
    {
        try {
            $produksi = Produksi::findOrFail($id);
            
            if ($produksi->foto) {
                if (Storage::disk('public')->exists($produksi->foto)) {
                    Storage::disk('public')->delete($produksi->foto);
                }
            }
            
            $produksi->delete();
            
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification("Data produksi tanggal {$produksi->tanggal} telah dihapus."));
            }
            
            return redirect()->back()->with('success', 'Data produksi berhasil dihapus!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}