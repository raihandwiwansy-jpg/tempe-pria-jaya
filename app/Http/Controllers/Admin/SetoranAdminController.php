<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setoran; // Pastikan Model Setoran sudah dibuat
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
            
            // Update status
            $setoran->status = $request->status;
            $setoran->save();

            DB::commit();

            $pesan = $request->status == 'disetujui' 
                ? 'Setoran berhasil dikonfirmasi dan disetujui.' 
                : 'Setoran telah ditolak.';

            return redirect()->route('admin.setoran.index')->with('success', $pesan);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

            // Kirim notifikasi ke reseller terkait
            $reseller = $setoran->user; // Asumsi relasi user() sudah didefinisikan di model Setoran
            if ($reseller) {
                $statusText = $request->status == 'disetujui' ? 'disetujui' : 'ditolak';
                $reseller->notify(new DataTerbaruNotification('Setoran Anda dengan ID #' . $setoran->id . ' telah ' . $statusText . '.'));
            }
    }

    public function destroy($id)
    {
        try {
            $setoran = Setoran::findOrFail($id);

            // Hapus file bukti pembayaran dari storage jika ada
            if ($setoran->bukti_pembayaran) {
                Storage::disk('public')->delete($setoran->bukti_pembayaran);
            }

            $setoran->delete();

            return redirect()->back()->with('success', 'Data record setoran berhasil dihapus dari sistem.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}