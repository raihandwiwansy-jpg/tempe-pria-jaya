<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProduksiJadi;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\DataTerbaruNotification;
use Carbon\Carbon;
use Exception;

class ProduksiJadiController extends Controller
{
    public function index()
    {
        try {
            $data = ProduksiJadi::latest()->get();

            // Helper function untuk mempermudah rekapan per periode
            $getStats = function($query) {
                return [
                    'total' => $query->sum('total_produksi'),
                    's9x30' => $query->sum('size_9x30'),
                    's8x30' => $query->sum('size_8x30'),
                    's10x35' => $query->sum('size_10x35'),
                ];
            };

            // 1. Rekapan Harian
            $statHari = $getStats(ProduksiJadi::whereDate('tanggal', Carbon::today()));

            // 2. Rekapan Mingguan
            $statMinggu = $getStats(ProduksiJadi::whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]));

            // 3. Rekapan Bulanan
            $statBulan = $getStats(ProduksiJadi::whereMonth('tanggal', Carbon::now()->month)->whereYear('tanggal', Carbon::now()->year));

            // 4. Rekapan Tahunan
            $statTahun = $getStats(ProduksiJadi::whereYear('tanggal', Carbon::now()->year));

            return view('admin.produksi_jadi.index', compact('data', 'statHari', 'statMinggu', 'statBulan', 'statTahun'));

        } catch (Exception $e) {
            return back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'size_9x30' => 'required|integer|min:0',
            'size_8x30' => 'required|integer|min:0',
            'size_10x35' => 'required|integer|min:0',
        ]);

        try {
            $produksi = ProduksiJadi::create($request->all());

            // KIRIM NOTIFIKASI KE ADMIN
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $pesan = "Produksi Jadi Selesai: Total {$produksi->total_produksi} pcs (9x30: {$request->size_9x30}, 8x30: {$request->size_8x30}, 10x35: {$request->size_10x35})";
                $admin->notify(new DataTerbaruNotification($pesan));
            }

            // NOTIFIKASI KE RESELLER (Biar mereka tahu stok tempe baru sudah matang/siap)
            $resellers = User::where('role', 'reseller')->get();
            foreach ($resellers as $reseller) {
                $reseller->notify(new DataTerbaruNotification("Kabar Gembira! Stok tempe baru sudah tersedia. Silahkan cek menu order."));
            }

            return redirect()->route('admin.produksi_jadi.index')->with('success', 'Produksi barang jadi berhasil dicatat!');

        } catch (Exception $e) {
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            ProduksiJadi::findOrFail($id)->delete();
            return back()->with('success', 'Data produksi jadi dihapus!');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}