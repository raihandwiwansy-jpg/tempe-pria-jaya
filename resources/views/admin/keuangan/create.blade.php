@extends('layouts.admin')

@section('title', 'Tambah Catatan Keuangan')

@section('content')
<div class="container mx-auto p-6 max-w-2xl">
    {{-- Notifikasi Error Validasi --}}
    @if ($errors->any())
    <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm">
        <p class="font-bold">Gagal Menyimpan:</p>
        <ul class="list-disc ml-5 text-xs">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-green-600 p-8">
            <h2 class="text-white font-black text-xl uppercase tracking-tight">Tambah Transaksi Baru</h2>
            <p class="text-green-100 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">Input Arus Kas, Profit, atau Omzet</p>
        </div>
        
        {{-- Pastikan Route mengarah ke admin.keuangan.store --}}
        <form action="{{ route('admin.keuangan.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Input Tanggal --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Tanggal Transaksi</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" 
                           class="w-full border-gray-100 bg-gray-50 rounded-2xl focus:ring-green-500 focus:border-green-500 font-bold text-sm">
                </div>

                {{-- Input Jumlah --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" placeholder="0" 
                           class="w-full border-gray-100 bg-gray-50 rounded-2xl focus:ring-green-500 focus:border-green-500 font-bold text-sm shadow-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Input Jenis (Kategori) --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Jenis Kategori</label>
                    <select name="jenis" class="w-full border-gray-100 bg-gray-50 rounded-2xl focus:ring-green-500 focus:border-green-500 font-bold text-sm">
                        <option value="omzet">Omzet</option>
                        <option value="kas">Kas</option>
                        <option value="profit">Profit</option>
                    </select>
                </div>

                {{-- Input Tipe (Masuk/Keluar) --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Tipe Arus</label>
                    <select name="tipe" class="w-full border-gray-100 bg-gray-50 rounded-2xl focus:ring-green-500 focus:border-green-500 font-bold text-sm">
                        <option value="masuk" class="text-green-600 font-bold">Uang Masuk (+)</option>
                        <option value="keluar" class="text-red-600 font-bold">Uang Keluar (-)</option>
                    </select>
                </div>
            </div>

            {{-- Input Keterangan --}}
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Keterangan Tambahan</label>
                <textarea name="keterangan" rows="3" 
                          class="w-full border-gray-100 bg-gray-50 rounded-2xl focus:ring-green-500 focus:border-green-500 font-bold text-sm" 
                          placeholder="Misal: Hasil Setoran Reseller A atau Pembelian Bahan Baku"></textarea>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-col md:flex-row gap-4 pt-4">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-green-100 text-[10px] uppercase tracking-widest"> 
                    Simpan Transaksi 
                </button>
                <a href="{{ route('admin.keuangan.index') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-500 font-black py-4 rounded-2xl text-center transition-all text-[10px] uppercase tracking-widest"> 
                    Batal 
                </a>
            </div>
        </form>
    </div>
</div>
@endsection