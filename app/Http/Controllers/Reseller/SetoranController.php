<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\Setoran;
use App\Models\Keuangan;
use App\Models\User; // Wajib ada untuk notif ke Admin
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Notifications\DataTerbaruNotification; // Wajib ada
use Exception;

class SetoranController extends Controller
{
    /**
     * Menampilkan daftar setoran milik reseller yang sedang login
     */
    public function index()
    {
        $setorans = Setoran::where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('reseller.setoran.index', compact('setorans'));
    }

    /**
     * Menampilkan form buat setoran baru
     */
    public function create()
    {
        return view('reseller.setoran.create');
    }

    /**
     * Menyimpan data setoran dan kirim notifikasi ke Admin
     */
    public function store(Request $request)
    {
        $request->validate([
            'jumlah_setoran' => 'required|numeric|min:1000',
            'tanggal_setoran' => 'required|date',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // 1. Proses Upload Bukti Pembayaran
            $path = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $path = $request->file('bukti_pembayaran')->store('setoran', 'public');
            }

            // 2. Simpan ke Tabel Setoran
            $setoran = Setoran::create([
                'user_id' => Auth::id(),
                'jumlah_setoran' => $request->jumlah_setoran,
                'tanggal_setoran' => $request->tanggal_setoran,
                'bukti_pembayaran' => $path,
                'keterangan' => $request->keterangan,
                'status' => 'pending'
            ]);

            // 3. Simpan ke Tabel Keuangan (Record Omzet Masuk)
            Keuangan::create([
                'tanggal'     => $request->tanggal_setoran,
                'jenis'       => 'omzet',
                'tipe'        => 'masuk',
                'jumlah'      => $request->jumlah_setoran,
                'keterangan'  => 'Setoran dari Reseller: ' . Auth::user()->name . ' (' . ($request->keterangan ?? 'Tanpa keterangan') . ')',
                'sumber_type' => 'setoran_reseller',
                'sumber_id'   => $setoran->id,
            ]);

            // 4. KIRIM NOTIFIKASI KE ADMIN
            $nominal = number_format($request->jumlah_setoran, 0, ',', '.');
            $namaReseller = Auth::user()->name;
            
            // Cari user Admin (asumsi role adalah 'admin')
            $admin = User::where('role', 'admin')->first();
            
            if ($admin) {
                $admin->notify(new DataTerbaruNotification(
                    "Setoran Baru! Reseller {$namaReseller} mengirim laporan setoran sebesar Rp {$nominal}. Silakan cek dan konfirmasi."
                ));
            }

            // (Opsional) Notif ke diri sendiri agar ada di log reseller
            Auth::user()->notify(new DataTerbaruNotification(
                "Laporan setoran Rp {$nominal} berhasil dikirim. Status: Menunggu Konfirmasi."
            ));

            DB::commit();
            return redirect()->route('reseller.setoran.index')->with('success', 'Laporan setoran berhasil dikirim dan tercatat di Keuangan Admin!')

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus laporan setoran (Hanya jika masih pending)
     */
    public function destroy($id)
    {
        try {
            // Cari data pastikan milik reseller yang sedang login
            $setoran = Setoran::where('id', $id)
                        ->where('user_id', Auth::id()) // Pastikan pakai user_id sesuai login
                        ->firstOrFail();

            // Proteksi: Hanya boleh hapus jika status masih pending
            if ($setoran->status !== 'pending') {
                return redirect()->back()->with('error', 'Laporan yang sudah diproses tidak dapat dihapus.');
            }

            // Hapus file bukti fisik di storage jika ada
            if ($setoran->bukti_pembayaran) {
                Storage::disk('public')->delete($setoran->bukti_pembayaran);
            }

            $setoran->delete();

            return redirect()->back()->with('success', 'Laporan setoran berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }
}