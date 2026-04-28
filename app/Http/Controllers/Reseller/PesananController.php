<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan; // Pastikan nama model sesuai (Pemesanan)
use Illuminate\Http\Request;
use exception;  
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    public function index()
    {
        // Hanya ambil pesanan milik reseller yang login
        $pesanans = Pemesanan::where('id_reseller_assign', Auth::id())
                    ->orderBy('id_pemesanan', 'desc')
                    ->get();

        return view('reseller.pesanan.index', compact('pesanans'));
    }

    public function create()
    {
        return view('reseller.pesanan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemesan' => 'required|string|max:255',
            'alamat_pemesan' => 'required|string',
            'jumlah' => 'required|numeric|min:1',
            'ukuran' => 'required',
            'tanggal_kirim' => 'required|date',
        ]);

        Pemesanan::create([
            'id_reseller_assign' => Auth::id(),
            'nama_pemesan' => $request->nama_pemesan,
            'alamat_pemesan' => $request->alamat_pemesan,
            'jumlah' => $request->jumlah,
            'ukuran' => $request->ukuran,
            'tanggal_kirim' => $request->tanggal_kirim,
            'status' => 'pending'
        ]);

        return redirect()->route('reseller.pesanan.index')->with('success', 'Pesanan berhasil dibuat!');
    }

    /**
     * Update status pesanan oleh Reseller
     */
    public function updateStatus(Request $request, $id)
    {
        // 1. Validasi input status
        $request->validate([
            'status' => 'required|in:pending,diproses,dikirim,selesai'
        ]);

        // 2. Cari pesanan berdasarkan ID dan pastikan milik reseller yang sedang login
        // Kita pakai id_pemesanan sesuai primary key di model kamu
        $pesanan = Pemesanan::where('id_pemesanan', $id)
                            ->where('id_reseller_assign', Auth::id())
                            ->firstOrFail();

        // 3. Update status
        $pesanan->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status pesanan #' . $id . ' berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            $pesanan = Pemesanan::where('id_pemesanan', $id)
                        ->where('id_reseller', Auth::id())
                        ->firstOrFail();

            // Opsional: Beri batasan, misalnya hanya bisa hapus jika status masih 'pending'
            /*
            if ($pesanan->status != 'pending') {
                return redirect()->back()->with('error', 'Pesanan yang sudah diproses tidak bisa dibatalkan.');
            }
            */

            $pesanan->delete();

            return redirect()->back()->with('success', 'Pesanan telah berhasil dibatalkan dan dihapus.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pesanan: ' . $e->getMessage());
        }
    }
}