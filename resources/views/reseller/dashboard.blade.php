@extends('layouts.reseller')

@section('title', 'Dashboard - Tempe Pria Jaya')

@section('content')
<div class="container mx-auto px-4 pb-12">
    <div class="relative overflow-hidden bg-white rounded-[2.5rem] shadow-2xl shadow-gray-100 border border-gray-100 mb-10">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-green-50 to-transparent opacity-60"></div>
        <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-green-600 opacity-5 rounded-full blur-3xl"></div>

        <div class="relative flex flex-col md:flex-row items-center p-8 md:p-16 gap-12">
            <div class="w-48 h-48 md:w-64 md:h-64 flex-shrink-0 bg-white rounded-[2rem] shadow-xl border-4 border-green-600 flex items-center justify-center overflow-hidden group">
                <div class="text-center">
                    <span class="material-symbols-rounded text-7xl text-green-600 group-hover:scale-110 transition-transform duration-500">eco</span>
                    <p class="font-black text-green-800 text-xs tracking-[0.2em] uppercase mt-2">Pria Jaya</p>
                </div>
            </div>

            <div class="flex-grow text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 rounded-full text-xs font-black uppercase tracking-widest mb-6">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    Bisnis Rumahan Premium
                </div>
                
                <h1 class="text-4xl md:text-6xl font-black text-gray-900 leading-tight mb-4">
                    Tempe <span class="text-green-600">Pria Jaya</span>
                </h1>
                
                <p class="text-lg md:text-xl text-gray-500 font-medium max-w-2xl leading-relaxed mb-8">
                    UMKM inovatif, menghadirkan Tempe cita rasa tradisional bergaya modern. Dedikasi kami adalah menjaga warisan kuliner dengan standar kualitas masa kini.
                </p>

                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    <a href="https://www.instagram.com/tempepriajaya" target="_blank" 
                       class="inline-flex items-center gap-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest transition-all shadow-lg shadow-green-200 active:scale-95">
                        <i class="fab fa-instagram text-xl"></i>
                        Ikuti Kami di Instagram
                    </a>
                    
                   
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-6">
                <span class="material-symbols-rounded">verified</span>
            </div>
            <h3 class="font-black text-gray-800 text-lg mb-2 uppercase tracking-tight">Kualitas Terjaga</h3>
            <p class="text-gray-500 text-sm leading-relaxed font-medium">Bahan kedelai pilihan dengan proses fermentasi yang dipantau ketat untuk hasil terbaik.</p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-6">
                <span class="material-symbols-rounded">temp_preferences_custom</span>
            </div>
            <h3 class="font-black text-gray-800 text-lg mb-2 uppercase tracking-tight">Rasa Autentik</h3>
            <p class="text-gray-500 text-sm leading-relaxed font-medium">Tekstur padat dan rasa gurih alami yang tidak berubah sejak generasi pertama.</p>
        </div>

        <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-6">
                <span class="material-symbols-rounded">shutter_speed</span>
            </div>
            <h3 class="font-black text-gray-800 text-lg mb-2 uppercase tracking-tight">Selalu Fresh</h3>
            <p class="text-gray-500 text-sm leading-relaxed font-medium">Produksi setiap hari menjamin reseller selalu mendapatkan stok tempe yang baru saja jadi.</p>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection