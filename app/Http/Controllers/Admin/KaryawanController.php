<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User; // <--- WAJIB TAMBAH INI BIAR GAK EROR
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Notifications\DataTerbaruNotification;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    /**
     * Tampil Daftar Karyawan
     */
    public function index()
    {
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

        // 1. Simpan ke database
        $karyawan = Karyawan::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'gaji_perbulan' => $request->gaji_perbulan,
        ]);

        // 2. KIRIM NOTIFIKASI (Pindahkan ke atas return)
        // Kirim ke Admin agar muncul di log dashboard
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new DataTerbaruNotification('Karyawan baru bergabung: ' . $request->nama));
        }

        // Kirim ke semua reseller (Opsional)
        $resellers = User::where('role', 'reseller')->get();
        foreach ($resellers as $reseller) {
            $reseller->notify(new DataTerbaruNotification('Personel baru telah ditambahkan: ' . $request->nama));
        }

        // 3. SELESAI
        return redirect()->route('admin.karyawan.index')
                         ->with('success', 'Karyawan berhasil ditambahkan!');
    }

    /**
     * Proses Pencatatan Absensi
     */
    public function absensi(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required|exists:karyawans,id', // Pastikan kolom id benar
            'status' => 'required|in:hadir,tidak',
        ]);

        $today = now()->toDateString();
        $karyawan = Karyawan::find($request->id_karyawan);
        
        $cekAbsen = DB::table('absensi')
            ->where('id_karyawan', $request->id_karyawan)
            ->where('tanggal', $today)
            ->first();

        if ($cekAbsen) {
            DB::table('absensi')
                ->where('id', $cekAbsen->id)
                ->update([
                    'status' => $request->status,
                    'updated_at' => now()
                ]);
        } else {
            DB::table('absensi')->insert([
                'id_karyawan' => $request->id_karyawan,
                'tanggal' => $today,
                'status' => $request->status,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Tambah Notif untuk Absensi ke Admin
        $admin = User::where('role', 'admin')->first();
        if ($admin && $karyawan) {
            $statusText = $request->status == 'hadir' ? 'Hadir' : 'Tidak Hadir';
            $admin->notify(new DataTerbaruNotification("Absensi dicatat: {$karyawan->nama} status {$statusText}"));
        }

        return back()->with('success', 'Presensi berhasil dicatat untuk tanggal ' . $today);
    }

    public function destroy($id)
    {
        try {
            $karyawan = Karyawan::findOrFail($id);
            $nama = $karyawan->nama;
            $karyawan->delete();

            // Notif Penghapusan
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification("Data karyawan {$nama} telah dihapus."));
            }

            return redirect()->back()->with('success', 'Data personel telah dihapus permanen.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Detail Info Kehadiran
     */
    public function info($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $absensi = DB::table('absensi')
                    ->where('id_karyawan', $id)
                    ->orderBy('tanggal', 'desc')
                    ->get();

        $totalTidakHadir = DB::table('absensi')
                            ->where('id_karyawan', $id)
                            ->where('status', 'tidak')
                            ->count();

        $nominalPotongan = 20000;
        $totalPotongan = $totalTidakHadir * $nominalPotongan;
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