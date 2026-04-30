<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\DataTerbaruNotification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KeuanganController extends Controller
{
    /**
     * Tampil Laporan Keuangan dengan Rekapan Lengkap
     */
    public function index()
    {
        try {
            // 1. Ambil data utama untuk tabel
            $data = Keuangan::orderBy('tanggal', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->get();

            // 2. Kalkulasi Saldo Kas Keseluruhan
            $total_masuk = Keuangan::where('tipe', 'masuk')->sum('jumlah');
            $total_keluar = Keuangan::where('tipe', 'keluar')->sum('jumlah');
            $total_kas = $total_masuk - $total_keluar;

            // 3. REKAPAN PEMASUKAN (Only tipe: masuk)
            $hariIni = Keuangan::where('tipe', 'masuk')
                        ->whereDate('tanggal', Carbon::today())
                        ->sum('jumlah');

            $mingguIni = Keuangan::where('tipe', 'masuk')
                        ->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                        ->sum('jumlah');

            $bulanIni = Keuangan::where('tipe', 'masuk')
                        ->whereMonth('tanggal', Carbon::now()->month)
                        ->whereYear('tanggal', Carbon::now()->year)
                        ->sum('jumlah');

            $tahunIni = Keuangan::where('tipe', 'masuk')
                        ->whereYear('tanggal', Carbon::now()->year)
                        ->sum('jumlah');

            // 4. KALKULASI PEMASUKAN BERSIH (Pemasukan - Pengeluaran di bulan berjalan)
            $pengeluaranBulanIni = Keuangan::where('tipe', 'keluar')
                                    ->whereMonth('tanggal', Carbon::now()->month)
                                    ->whereYear('tanggal', Carbon::now()->year)
                                    ->sum('jumlah');
            
            $pemasukanBersihBulanIni = $bulanIni - $pengeluaranBulanIni;

            return view('admin.keuangan.index', compact(
                'data', 
                'total_kas', 
                'hariIni', 
                'mingguIni', 
                'bulanIni', 
                'tahunIni',
                'pemasukanBersihBulanIni'
            ));
            
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
        $request->validate([
            'tanggal'    => 'required|date',
            'jenis'      => 'required|in:kas,profit,omzet',
            'tipe'       => 'required|in:masuk,keluar',
            'jumlah'     => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // 1. Simpan Transaksi
            $transaksi = Keuangan::create([
                'tanggal'    => $request->tanggal,
                'jenis'      => $request->jenis,
                'tipe'       => $request->tipe,
                'jumlah'     => $request->jumlah,
                'keterangan' => $request->keterangan,
            ]);

            // 2. Persiapkan Notifikasi
            $nominal = number_format($request->jumlah, 0, ',', '.');
            $tipeLabel = strtoupper($request->tipe);
            $pesanNotif = "Transaksi Baru: {$tipeLabel} Rp {$nominal} ({$request->jenis})";

            // 3. Kirim Notif ke Admin (Log Dashboard)
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification($pesanNotif));
            }

            // 4. Kirim Notif ke Reseller (Informasi Update)
            $resellers = User::where('role', 'reseller')->get();
            foreach ($resellers as $reseller) {
                $reseller->notify(new DataTerbaruNotification("Update Info Keuangan: " . $pesanNotif));
            }

            DB::commit();
            return redirect()->route('admin.keuangan.index')
                             ->with('success', 'Transaksi berhasil disimpan dan rekapan telah diperbarui!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }
}