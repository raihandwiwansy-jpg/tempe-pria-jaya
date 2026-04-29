<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\Setoran;
use App\Models\Keuangan;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Notifications\DataTerbaruNotification;
use Exception;

class SetoranController extends Controller
{
    /**
     * Menampilkan riwayat setoran reseller yang sedang login
     */
    public function index()
    {
        $setorans = Setoran::where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('reseller.setoran.index', compact('setorans'));
    }

    /**
     * Form tambah setoran
     */
    public function create()
    {
        return view('reseller.setoran.create');
    }

    /**
     * Simpan data dan kirim notifikasi ke Admin
     */
    public function store(Request $request)
    {
        $request->validate([
            'jumlah_setoran' => 'required|numeric|min:1000',
            'tanggal_setoran' => 'required|date',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // 1. Upload Bukti (Jika ada)
            $path = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $path = $request->file('bukti_pembayaran')->store('setoran', 'public');
            }

            // 2. Simpan ke Tabel Setoran
            // Catatan: Pastikan kolom di database adalah 'user_id', bukan 'id_reseller'
            $setoran = Setoran::create([
                'user_id' => Auth::id(),
                'jumlah_setoran' => $request->jumlah_setoran,
                'tanggal_setoran' => $request->tanggal_setoran,
                'bukti_pembayaran' => $path,
                'keterangan' => $request->keterangan,
                'status' => 'pending'
            ]);

            // 3. Simpan Otomatis ke Tabel Keuangan Admin
            Keuangan::create([
                'tanggal'     => $request->tanggal_setoran,
                'jenis'       => 'omzet', 
                'tipe'        => 'masuk',
                'jumlah'      => $request->jumlah_setoran,
                'keterangan'  => 'Setoran Reseller: ' . Auth::user()->name . ' - ' . ($request->keterangan ?? 'No Ket'),
                'sumber_type' => 'setoran_reseller',
                'sumber_id'   => $setoran->id,
            ]);

            // 4. KIRIM NOTIFIKASI KE ADMIN
            $nominal = number_format($request->jumlah_setoran, 0, ',', '.');
            $admin = User::where('role', 'admin')->first();
            
            if ($admin) {
                $admin->notify(new DataTerbaruNotification(
                    "Setoran Masuk: Rp {$nominal} dari " . Auth::user()->name
                ));
            }

            // 5. NOTIFIKASI KE RESELLER (Log buat reseller sendiri)
            Auth::user()->notify(new DataTerbaruNotification(
                "Laporan setoran Rp {$nominal} telah terkirim."
            ));

            DB::commit();
            return redirect()->route('reseller.setoran.index')->with('success', 'Setoran berhasil terkirim!');

        } catch (\Exception $e) {
            DB::rollback();
            // Jika masih error, hapus file yang sempat terupload
            if ($path) { Storage::disk('public')->delete($path); }
            
            return redirect()->back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * Hapus laporan (Hanya jika belum diproses admin)
     */
    public function destroy($id)
    {
        try {
            $setoran = Setoran::where('id', $id)
                        ->where('user_id', Auth::id())
                        ->firstOrFail();

            if ($setoran->status !== 'pending') {
                return redirect()->back()->with('error', 'Data sudah diproses Admin, tidak bisa dihapus.');
            }

            if ($setoran->bukti_pembayaran) {
                Storage::disk('public')->delete($setoran->bukti_pembayaran);
            }

            $setoran->delete();
            return redirect()->back()->with('success', 'Laporan berhasil dihapus.');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }
}