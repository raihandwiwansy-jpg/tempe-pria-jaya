@extends('layouts.admin')

@section('title', 'Tambah Produk Gudang')

@section('content')
<div class="container mx-auto pb-20">
    <div class="mb-10">
        <a href="{{ route('admin.gudang.index') }}" class="group inline-flex items-center text-gray-500 hover:text-green-600 transition-colors mb-4">
            <span class="material-symbols-rounded text-sm">arrow_back_ios</span>
            <span class="text-xs font-bold uppercase tracking-widest ml-2">Kembali</span>
        </a>
        <div class="border-l-4 border-green-600 pl-4">
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Tambah Master Produk</h2>
        </div>
    </div>

    <div class="max-w-4xl">
        <form action="{{ route('admin.gudang.store') }}" method="POST" class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
            @csrf <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Kode Barang</label>
                    <input type="text" name="kode_barang" value="{{ old('kode_barang') }}" class="w-full bg-gray-50 border-none focus:ring-2 focus:ring-green-500 rounded-2xl p-4 font-bold text-gray-700" required>
                    @error('kode_barang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Nama Barang</label>
                    <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" class="w-full bg-gray-50 border-none focus:ring-2 focus:ring-green-500 rounded-2xl p-4 font-bold text-gray-700" required>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Harga Modal (Rp)</label>
                    <input type="number" name="harga_modal" value="{{ old('harga_modal') }}" class="w-full bg-gray-50 border-none focus:ring-2 focus:ring-green-500 rounded-2xl p-4 font-bold text-gray-700" required>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Stok Awal</label>
                    <input type="number" name="stok_pusat" value="{{ old('stok_pusat', 0) }}" class="w-full bg-gray-50 border-none focus:ring-2 focus:ring-green-500 rounded-2xl p-4 font-bold text-gray-700" required>
                </div>
            </div>

            <div class="bg-gray-50 p-8 flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-lg flex items-center gap-3">
                    <span class="material-symbols-rounded">save</span>
                    Simpan ke Gudang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection