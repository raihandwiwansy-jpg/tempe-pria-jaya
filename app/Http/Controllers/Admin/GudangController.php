<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Notifications\DataTerbaruNotification;
use Illuminate\Support\Facades\Auth;

class GudangController extends Controller
{
    /**
     * Menampilkan daftar stok pusat (Gudang Admin)
     */
    public function index()
    {
        $barangs = Barang::latest()->get();
        return view('admin.gudang.index', compact('barangs'));
    }

    /**
     * Menampilkan form tambah produk baru
     */
    public function create()
    {
        return view('admin.gudang.create');
    }

    /**
     * Menyimpan produk baru ke database
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang',
            'nama_barang' => 'required',
            'harga_modal' => 'required|numeric|min:0',
            'stok_pusat'  => 'required|numeric|min:0',
            'foto'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        try {
            $data = $request->all();

            // 2. Proses Foto jika ada
            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('produk', 'public');
            }

            // 3. Simpan ke Database
            Barang::create($data);

            return redirect()->route('admin.gudang.index')
                             ->with('success', 'Barang berhasil ditambahkan!');

        } catch (Exception $e) {
            return redirect()->back()
                             ->with('error', 'Error: ' . $e->getMessage())
                             ->withInput();
        }

        // Kirim notifikasi ke semua reseller yang terdaftar
        $resellers = User::where('role', 'reseller')->get();
        foreach ($resellers as $reseller) {
            $reseller->notify(new DataTerbaruNotification('Produk baru telah ditambahkan ke gudang: ' . $request->nama_barang));
        }

    }

    /**
     * Update stok (Tambah/Kurang) secara manual
     */
    public function transaksi(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tipe'      => 'required|in:masuk,keluar',
            'jumlah'    => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $barang = Barang::lockForUpdate()->findOrFail($request->barang_id);

                if ($request->tipe == 'masuk') {
                    $barang->stok_pusat += $request->jumlah;
                } else {
                    // Cek agar stok tidak minus
                    if ($barang->stok_pusat < $request->jumlah) {
                        throw new Exception('Stok tidak mencukupi untuk dikurangi!');
                    }
                    $barang->stok_pusat -= $request->jumlah;
                }

                $barang->save();
            });

            return redirect()->route('admin.gudang.index')
                             ->with('success', 'Stok berhasil diperbarui!');

        } catch (Exception $e) {
            return redirect()->route('admin.gudang.index')
                             ->with('error', $e->getMessage());
        }
    }

    /**
     * Menghapus produk secara permanen
     */
    public function destroy($id)
    {
        try {
            $barang = Barang::findOrFail($id);
            
            // 1. Hapus foto dari storage jika ada
            if ($barang->foto) {
                if (Storage::disk('public')->exists($barang->foto)) {
                    Storage::disk('public')->delete($barang->foto);
                }
            }
            
            // 2. Hapus data dari database
            $barang->delete();
            
            return redirect()->back()->with('success', 'Produk berhasil dihapus selamanya!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}