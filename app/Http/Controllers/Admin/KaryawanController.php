<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User; 
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

        try {
            DB::beginTransaction();

            $karyawan = Karyawan::create([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'gaji_perbulan' => $request->gaji_perbulan,
            ]);

            // Kirim Notif ke Admin
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification('Karyawan baru bergabung: ' . $request->nama));
            }

            // Kirim ke semua reseller
            $resellers = User::where('role', 'reseller')->get();
            foreach ($resellers as $reseller) {
                $reseller->notify(new DataTerbaruNotification('Personel baru telah ditambahkan: ' . $request->nama));
            }

            DB::commit();
            return redirect()->route('admin.karyawan.index')->with('success', 'Karyawan berhasil ditambahkan!');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambah karyawan: ' . $e->getMessage());
        }
    }

    /**
     * Proses Pencatatan Absensi
     */
    public function absensi(Request $request)
    {
        // Validasi menunjuk langsung ke kolom id_karyawan di tabel karyawans
        $request->validate([
            'id_karyawan' => 'required|exists:karyawans,id_karyawan', 
            'status' => 'required|in:hadir,tidak',
        ], [
            'id_karyawan.exists' => 'Data karyawan tidak ditemukan.'
        ]);

        try {
            $today = now()->toDateString();
            
            // Cari karyawan menggunakan kolom id_karyawan
            $karyawan = Karyawan::where('id_karyawan', $request->id_karyawan)->first();
            
            if (!$karyawan) {
                 return redirect()->back()->with('error', 'Karyawan tidak ditemukan.');
            }

            // Cek apakah sudah absen hari ini
            $cekAbsen = DB::table('absensi')
                ->where('id_karyawan', $request->id_karyawan)
                ->where('tanggal', $today)
                ->first();

            if ($cekAbsen) {
                // Update menggunakan id_absensi (sesuaikan nama PK tabel absensimu)
                DB::table('absensi')
                    ->where('id_absensi', $cekAbsen->id_absensi) 
                    ->update([
                        'status' => $request->status,
                        'updated_at' => now()
                    ]);
            } else {
                // Simpan data baru
                DB::table('absensi')->insert([
                    'id_karyawan' => $request->id_karyawan,
                    'tanggal' => $today,
                    'status' => $request->status,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Kirim Notif ke Admin
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $statusText = $request->status == 'hadir' ? 'Hadir' : 'Tidak Hadir';
                $admin->notify(new DataTerbaruNotification("Absensi: {$karyawan->nama} dicatat {$statusText}"));
            }

            return back()->with('success', 'Presensi ' . $karyawan->nama . ' berhasil dicatat!');

        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Data Karyawan
     */
    public function destroy($id)
    {
        try {
            // Karena PK bukan 'id', kita cari manual
            $karyawan = Karyawan::where('id_karyawan', $id)->firstOrFail();
            $nama = $karyawan->nama;
            $karyawan->delete();

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
     * Detail Info Kehadiran & Gaji
     */
    public function info($id)
    {
        $karyawan = Karyawan::where('id_karyawan', $id)->firstOrFail();

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