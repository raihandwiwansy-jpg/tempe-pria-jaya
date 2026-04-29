<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\User; // <--- WAJIB TAMBAH INI
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

            // 4. KIRIM NOTIFIKASI (Taruh di sini sebelum return)
            // Notif ke Admin sendiri
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new DataTerbaruNotification('Barang baru terdaftar di sistem: ' . $request->nama_barang));
            }

            // Notif ke semua Reseller (Opsional, jika ingin reseller tahu ada barang baru masuk gudang)
            $resellers = User::where('role', 'reseller')->get();
            foreach ($resellers as $reseller) {
                $reseller->notify(new DataTerbaruNotification('Ada stok barang baru di gudang: ' . $request->nama_barang));
            }

            return redirect()->route('admin.gudang.index')
                             ->with('success', 'Barang berhasil ditambahkan!');

        } catch (Exception $e) {
            return redirect()->back()
                             ->with('error', 'Error: ' . $e->getMessage())
                             ->withInput();
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
                    $pesan = "Stok masuk: " . $barang->nama_barang . " sebanyak " . $request->jumlah;
                } else {
                    if ($barang->stok_pusat < $request->jumlah) {
                        throw new Exception('Stok tidak mencukupi untuk dikurangi!');
                    }
                    $barang->stok_pusat -= $request->jumlah;
                    $pesan = "Stok keluar: " . $barang->nama_barang . " sebanyak " . $request->jumlah;
                }

                $barang->save();

                // Kirim notifikasi transaksi ke Admin
                $admin = User::where('role', 'admin')->first();
                if ($admin) {
                    $admin->notify(new DataTerbaruNotification($pesan));
                }
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
            
            if ($barang->foto) {
                if (Storage::disk('public')->exists($barang->foto)) {
                    Storage::disk('public')->delete($barang->foto);
                }
            }
            
            $barang->delete();
            
            return redirect()->back()->with('success', 'Produk berhasil dihapus selamanya!');

        } catch (Exception $e) {
            // Perbaikan typo kurung tutup di baris asli kamu
            return redirect()->back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}