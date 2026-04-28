<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\KatalogReseller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Perbaikan namespace storage

class BarangResellerController extends Controller
{
    /**
     * Menampilkan daftar katalog yang aktif untuk reseller.
     */
    public function index()
    {
        // Mengambil data katalog beserta relasi master barangnya (Eager Loading)
        $katalog = KatalogReseller::with('barang')->latest()->get();
        return view('admin.barang_reseller.index', compact('katalog'));
    }

    /**
     * Menampilkan form untuk mempublish barang ke katalog reseller.
     */
    public function create()
    {
        // Ambil semua barang dari gudang pusat untuk dipilih
        $masterBarang = Barang::all(); 
        return view('admin.barang_reseller.create', compact('masterBarang'));
    }

    /**
     * Menyimpan data produk ke tabel katalog_resellers.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id'           => 'required|exists:barangs,id',
            'nama_display'        => 'required|string|max:255',
            'kategori'            => 'required',
            'harga_jual_reseller' => 'required|numeric|min:0',
            'foto'                => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'deskripsi'           => 'nullable|string'
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            // Menyimpan di folder 'produk' dalam disk 'public'
            $path = $request->file('foto')->store('produk', 'public');
        }

        KatalogReseller::create([
            'barang_id'           => $request->barang_id,
            'nama_display'        => $request->nama_display,
            'kategori'            => $request->kategori,
            'harga_jual_reseller' => $request->harga_jual_reseller,
            'deskripsi'           => $request->deskripsi,
            'foto'                => $path
        ]);

        return redirect()->route('admin.barang_reseller.index')
                         ->with('success', 'Produk berhasil dipublish ke Katalog Reseller!');
    }

    /**
     * Menghapus barang dari katalog (Tarik dari peredaran reseller).
     */
    public function destroy($id)
    {
        $katalog = KatalogReseller::findOrFail($id);
        
        // Hapus file fisik foto dari storage jika ada
        if ($katalog->foto && Storage::disk('public')->exists($katalog->foto)) {
            Storage::disk('public')->delete($katalog->foto);
        }
        
        // Hapus data dari database
        $katalog->delete();

        return redirect()->back()->with('success', 'Barang berhasil ditarik dari katalog reseller.');
    }
}