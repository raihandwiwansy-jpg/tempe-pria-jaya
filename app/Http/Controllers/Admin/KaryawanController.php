<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Memperbaiki namespace
use Exception; // Menambahkan import Exception
use App\Notifications\DataTerbaruNotification;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    /**
     * Tampil Daftar Karyawan
     */
    public function index()
    {
        // Ambil semua data karyawan dari tabel karyawans
        $data = Karyawan::all();
        return view('admin.karyawan.index', compact('data'));
    }

    /**
     * Halaman Tambah Karyawan
     */
    public function create()
    {
        return view('admin.karyawan.create');
    }

    /**
     * Simpan Data Karyawan Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'gaji_perbulan' => 'required|numeric',
        ]);

        Karyawan::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'gaji_perbulan' => $request->gaji_perbulan,
        ]);

        return redirect()->route('admin.karyawan.index')
                         ->with('success', 'Karyawan berhasil ditambahkan!');

            // Kirim notifikasi ke semua reseller yang terdaftar
        $resellers = User::where('role', 'reseller')->get();
        foreach ($resellers as $reseller) {
            $reseller->notify(new DataTerbaruNotification('Karyawan baru telah ditambahkan: ' . $request->nama));
        }
        
    }

    /**
     * Proses Pencatatan Absensi
     * Logika: Menyimpan status hadir/tidak ke tabel absensi
     */
    public function absensi(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required|exists:karyawans,id_karyawan',
            'status' => 'required|in:hadir,tidak',
        ]);

        // Cek apakah hari ini karyawan tersebut sudah absen?
        $today = now()->toDateString();
        $cekAbsen = DB::table('absensi')
            ->where('id_karyawan', $request->id_karyawan)
            ->where('tanggal', $today)
            ->first();

        if ($cekAbsen) {
            // Jika sudah ada, kita update statusnya
            DB::table('absensi')
                ->where('id', $cekAbsen->id)
                ->update([
                    'status' => $request->status,
                    'updated_at' => now()
                ]);
        } else {
            // Jika belum ada, buat record baru
            DB::table('absensi')->insert([
                'id_karyawan' => $request->id_karyawan,
                'tanggal' => $today,
                'status' => $request->status,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return back()->with('success', 'Presensi berhasil dicatat untuk tanggal ' . $today);
    }

    public function destroy($id)
    {
        try {
            $karyawan = Karyawan::findOrFail($id);
            
            // Opsional: Hapus juga data absensi terkait jika perlu
            // $karyawan->absensis()->delete();
            
            $karyawan->delete();

            return redirect()->back()->with('success', 'Data personel telah dihapus permanen dari sistem.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Detail Info Kehadiran & Kalkulasi Potongan Gaji
     */
    public function info($id)
    {
        // 1. Ambil data karyawan atau 404 jika tidak ketemu
        $karyawan = Karyawan::findOrFail($id);

        // 2. Ambil riwayat absensi dari tabel absensi
        $absensi = DB::table('absensi')
                    ->where('id_karyawan', $id)
                    ->orderBy('tanggal', 'desc')
                    ->get();

        // 3. Hitung berapa kali statusnya "tidak" (mangkir)
        $totalTidakHadir = DB::table('absensi')
                            ->where('id_karyawan', $id)
                            ->where('status', 'tidak')
                            ->count();

        // 4. Hitung Potongan Gaji (20k per hari tidak hadir)
        $nominalPotongan = 20000;
        $totalPotongan = $totalTidakHadir * $nominalPotongan;
        
        // 5. Hitung Gaji Akhir
        $gajiBersih = $karyawan->gaji_perbulan - $totalPotongan;

        return view('admin.karyawan.info', compact(
            'karyawan', 
            'absensi', 
            'totalTidakHadir', 
            'totalPotongan', 
            'gajiBersih'
        ));
    }
}