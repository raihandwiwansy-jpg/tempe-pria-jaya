<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\KatalogReseller;
use Illuminate\Http\Request;

class InfoBarangController extends Controller
{
    public function index()
    {
        // Kita panggil relasi 'barang' agar bisa akses stok_pusat
        $katalog = KatalogReseller::with('barang')->get();
        return view('reseller.info-barang.index', compact('katalog'));
    }
}