<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\Setoran;
use App\Models\Keuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class SetoranController extends Controller
{
    public function index()
    {
        $setorans = Setoran::where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('reseller.setoran.index', compact('setorans'));
    }

    public function create()
    {
        return view('reseller.setoran.create');
    }

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

            // 3. OTOMATIS: Simpan ke Tabel Keuangan (SESUAI STRUKTUR BARU)
            Keuangan::create([
                'tanggal'    => $request->tanggal_setoran,
                'jenis'      => 'omzet',          // Kategori: omzet (sesuai enum di migrasi)
                'tipe'       => 'masuk',          // Arah uang: masuk (sesuai enum di migrasi)
                'jumlah'     => $request->jumlah_setoran, // Menggunakan 'jumlah', bukan 'nominal'
                'keterangan' => 'Setoran dari Reseller: ' . Auth::user()->name . ' (' . ($request->keterangan ?? 'Tanpa keterangan') . ')',
                'sumber_type' => 'setoran_reseller',
                'sumber_id'   => $setoran->id_setoran ?? $setoran->id, // Sesuaikan primary key setoranmu
            ]);

            DB::commit();
            return redirect()->route('reseller.setoran.index')->with('success', 'Laporan setoran berhasil dikirim dan tercatat di Keuangan Admin!');

        } catch (\Exception $e) {
            DB::rollback();
            // Dump error jika ingin debug lebih dalam: dd($e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Cari data pastikan milik reseller yang sedang login
            $setoran = Setoran::where('id', $id)
                        ->where('id_reseller', Auth::id())
                        ->firstOrFail();

            // Proteksi: Hanya boleh hapus jika status masih pending
            if ($setoran->status !== 'pending') {
                return redirect()->back()->with('error', 'Laporan yang sudah diproses tidak dapat dihapus.');
            }

            // Hapus file bukti fisik di storage jika ada
            if ($setoran->bukti_pembayaran) {
                Storage::delete('public/' . $setoran->bukti_pembayaran);
            }

            $setoran->delete();

            return redirect()->back()->with('success', 'Laporan setoran berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }
}