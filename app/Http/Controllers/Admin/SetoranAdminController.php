<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setoran;
use App\Models\User; // <--- WAJIB TAMBAH INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Notifications\DataTerbaruNotification;
use Illuminate\Support\Facades\Auth;

class SetoranAdminController extends Controller
{
    /**
     * Menampilkan daftar setoran dari semua reseller
     */
    public function index()
    {
        // Mengambil data setoran beserta data user (reseller) terkait
        // Diurutkan berdasarkan status 'pending' terlebih dahulu
        $setorans = Setoran::with('user')
            ->orderByRaw("FIELD(status, 'pending', 'disetujui', 'ditolak')")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.setoran.index', compact('setorans'));
    }

    /**
     * Memperbarui status setoran (Disetujui/Ditolak)
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|in:disetujui,ditolak'
        ]);

        try {
            DB::beginTransaction();

            $setoran = Setoran::findOrFail($id);
            
            // 1. Update status
            $setoran->status = $request->status;
            $setoran->save();

            // 2. Persiapkan Notifikasi
            $statusText = $request->status == 'disetujui' ? 'DISETUJUI' : 'DITOLAK';
            $nominal = number_format($setoran->jumlah_setoran, 0, ',', '.');

            // 3. KIRIM NOTIFIKASI KE RESELLER (Yang punya setoran)
            $reseller = $setoran->user;
            if ($reseller) {
                $reseller->notify(new DataTerbaruNotification("Setoran Anda senilai Rp {$nominal} telah {$statusText}."));
            }

            // 4. KIRIM NOTIFIKASI KE ADMIN (Untuk log dashboard)
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification("Konfirmasi Setoran: Reseller {$reseller->name} {$statusText} (Rp {$nominal})."));
            }

            DB::commit();

            $pesanSuccess = $request->status == 'disetujui' 
                ? 'Setoran berhasil dikonfirmasi dan disetujui.' 
                : 'Setoran telah ditolak.';

            return redirect()->route('admin.setoran.index')->with('success', $pesanSuccess);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus record setoran
     */
    public function destroy($id)
    {
        try {
            $setoran = Setoran::findOrFail($id);
            $namaReseller = $setoran->user->name ?? 'Reseller';

            // Hapus file bukti pembayaran dari storage jika ada
            if ($setoran->bukti_pembayaran) {
                Storage::disk('public')->delete($setoran->bukti_pembayaran);
            }

            $setoran->delete();

            // Notif ke Admin
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification("Record setoran dari {$namaReseller} telah dihapus dari sistem."));
            }

            return redirect()->back()->with('success', 'Data record setoran berhasil dihapus dari sistem.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}