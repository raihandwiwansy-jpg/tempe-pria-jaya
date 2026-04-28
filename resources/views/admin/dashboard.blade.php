@extends('layouts.admin')

@section('title', 'Admin Control Center - Tempe Pria Jaya')

@section('content')
<div class="container mx-auto px-4 pb-12">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Admin Control Center</h2>
            <p class="text-gray-500 font-medium uppercase tracking-[0.2em] text-[10px] mt-1">Sistem Manajemen Terintegrasi Pria Jaya</p>
        </div>
        <div class="flex items-center gap-4 bg-white p-2 pr-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-green-100">
                <span class="material-symbols-rounded">admin_panel_settings</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Status Akses</p>
                <p class="text-sm font-bold text-gray-700">Administrator Utama</p>
            </div>
        </div>
    </div>

    <div class="relative bg-gray-900 rounded-[3rem] shadow-2xl overflow-hidden mb-12">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-green-600 skew-x-[-15deg] translate-x-20 opacity-20"></div>
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-yellow-500 opacity-10 rounded-full blur-3xl"></div>

        <div class="relative z-10 flex flex-col lg:flex-row items-center p-12 lg:p-20 gap-12">
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-green-500 to-yellow-500 rounded-[2.5rem] blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                <div class="relative w-56 h-56 bg-white rounded-[2.5rem] flex items-center justify-center overflow-hidden border border-gray-800">
                    <div class="text-center">
                        <span class="material-symbols-rounded text-8xl text-green-700">warehouse</span>
                        <p class="font-black text-gray-900 text-xs tracking-[0.3em] uppercase mt-2">Pusat</p>
                    </div>
                </div>
            </div>

            <div class="flex-grow text-center lg:text-left text-white">
                <div class="inline-block px-4 py-1 rounded-full border border-green-500/30 bg-green-500/10 text-green-400 text-[10px] font-black uppercase tracking-[0.3em] mb-6">
                    Master Data & Stock Control
                </div>
                <h1 class="text-5xl lg:text-7xl font-black mb-6 tracking-tight">
                    Tempe <span class="text-green-500">Pria Jaya</span> <span class="text-gray-500 text-3xl font-light block lg:inline">Official Admin</span>
                </h1>
                <p class="text-gray-400 text-lg lg:text-xl font-medium max-w-2xl leading-relaxed mb-10 italic">
                    "Menjaga kualitas dari setiap butir kedelai, memastikan rantai distribusi reseller berjalan sempurna melalui inovasi teknologi dan tradisi."
                </p>
                
                <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                    <a href="{{ route('admin.gudang.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-green-900/40 flex items-center gap-3">
                        <span class="material-symbols-rounded">inventory</span>
                        Kelola Stok Pusat
                    </a>
                    <a href="https://instagram.com/tempepriajaya" target="_blank" class="bg-transparent border-2 border-gray-700 hover:border-green-500 text-gray-300 hover:text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all flex items-center gap-3">
                        <i class="fab fa-instagram"></i>
                        Instagram Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-symbols-rounded text-9xl">visibility</span>
            </div>
            <h4 class="text-green-600 font-black text-xs uppercase tracking-[0.2em] mb-4">Visi Bisnis</h4>
            <h3 class="text-2xl font-black text-gray-800 mb-4 leading-tight">Inovasi Lokal, Standar Global.</h3>
            <p class="text-gray-500 text-sm font-medium leading-relaxed">Membawa Tempe Pria Jaya menjadi role model UMKM digital yang tetap mempertahankan akar budaya tradisional.</p>
        </div>

        <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-symbols-rounded text-9xl">hub</span>
            </div>
            <h4 class="text-green-600 font-black text-xs uppercase tracking-[0.2em] mb-4">Integritas Sistem</h4>
            <h3 class="text-2xl font-black text-gray-800 mb-4 leading-tight">Kontrol Penuh <br>Satu Pintu.</h3>
            <p class="text-gray-500 text-sm font-medium leading-relaxed">Manajemen gudang terpusat memastikan akurasi data stok antara pusat dan seluruh jaringan reseller.</p>
        </div>

        <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-symbols-rounded text-9xl">workspace_premium</span>
            </div>
            <h4 class="text-green-600 font-black text-xs uppercase tracking-[0.2em] mb-4">Filosofi Kualitas</h4>
            <h3 class="text-2xl font-black text-gray-800 mb-4 leading-tight">Kesempurnaan Dalam Proses.</h3>
            <p class="text-gray-500 text-sm font-medium leading-relaxed">Setiap keputusan admin didasarkan pada kualitas produk akhir yang diterima oleh konsumen Tempe Pria Jaya.</p>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection