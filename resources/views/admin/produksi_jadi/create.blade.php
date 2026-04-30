@extends('layouts.admin')

@section('title', 'Create Finished Goods')

@section('content')
<div class="container mx-auto pb-32 px-4 sm:px-6 lg:px-8">
    {{-- Header Section --}}
    <div class="mb-12">
        <a href="{{ route('admin.produksi-jadi.index') }}" class="group flex items-center gap-2 text-gray-400 hover:text-emerald-600 transition-colors mb-6">
            <span class="material-symbols-rounded text-lg group-hover:-translate-x-1 transition-transform">arrow_back</span>
            <span class="text-[10px] font-black uppercase tracking-widest">Kembali ke Analytics</span>
        </a>

        <div class="relative group">
            <div class="absolute -left-6 top-0 bottom-0 w-2 bg-gradient-to-b from-emerald-600 to-teal-400 rounded-full shadow-[0_0_15px_rgba(16,185,129,0.4)]"></div>
            <h2 class="text-5xl font-[1000] text-gray-900 tracking-tight ml-2 leading-none">Input <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Barang Jadi</span></h2>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.3em] ml-2 mt-3">Batch Production Entry</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white/70 backdrop-blur-2xl rounded-[3.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.03)] border border-white overflow-hidden">
            <div class="p-12">
                <form action="{{ route('admin.produksi_jadi.store') }}" method="POST" class="space-y-10">
                    @csrf
                    
                    {{-- Section 1: Time Log --}}
                    <div class="relative">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                                <span class="material-symbols-rounded text-xl">calendar_today</span>
                            </div>
                            <h3 class="text-lg font-black text-gray-800 tracking-tighter">Waktu Produksi</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-4 mb-2 block">Pilih Tanggal</label>
                                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required 
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white rounded-[2rem] px-8 py-5 font-bold text-gray-700 transition-all outline-none">
                            </div>
                            <div class="bg-emerald-50/50 rounded-[2rem] p-6 border border-emerald-100 flex items-center gap-4">
                                <span class="material-symbols-rounded text-emerald-500">info</span>
                                <p class="text-[10px] font-bold text-emerald-700 uppercase tracking-tight leading-relaxed">Pastikan tanggal sesuai dengan jadwal pengemasan tempe di gudang.</p>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-50">

                    {{-- Section 2: Variant Quantities --}}
                    <div>
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                                <span class="material-symbols-rounded text-xl">inventory_2</span>
                            </div>
                            <h3 class="text-lg font-black text-gray-800 tracking-tighter">Detail Output Per Ukuran (Pcs)</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            {{-- Size 9x30 --}}
                            <div class="relative group">
                                <div class="absolute -top-3 left-6 bg-white px-3 py-1 rounded-full border border-gray-100 z-10">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Varian 9x30</span>
                                </div>
                                <input type="number" name="size_9x30" value="0" min="0" required
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-[2rem] px-8 py-10 text-4xl font-[1000] text-gray-900 transition-all outline-none text-center">
                            </div>

                            {{-- Size 8x30 --}}
                            <div class="relative group">
                                <div class="absolute -top-3 left-6 bg-white px-3 py-1 rounded-full border border-gray-100 z-10">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Varian 8x30</span>
                                </div>
                                <input type="number" name="size_8x30" value="0" min="0" required
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-indigo-500 focus:bg-white rounded-[2rem] px-8 py-10 text-4xl font-[1000] text-gray-900 transition-all outline-none text-center">
                            </div>

                            {{-- Size 10x35 --}}
                            <div class="relative group">
                                <div class="absolute -top-3 left-6 bg-white px-3 py-1 rounded-full border border-gray-100 z-10">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Varian 10x35</span>
                                </div>
                                <input type="number" name="size_10x35" value="0" min="0" required
                                    class="w-full bg-gray-50 border-2 border-transparent focus:border-rose-500 focus:bg-white rounded-[2rem] px-8 py-10 text-4xl font-[1000] text-gray-900 transition-all outline-none text-center">
                            </div>
                        </div>
                    </div>

                    {{-- Footer Action --}}
                    <div class="pt-10 flex flex-col md:flex-row items-center gap-6">
                        <button type="submit" class="w-full md:flex-1 bg-gray-900 hover:bg-emerald-600 text-white py-6 rounded-[2rem] font-black text-xs uppercase tracking-[0.3em] transition-all duration-500 shadow-2xl shadow-gray-200 active:scale-95 flex items-center justify-center gap-4">
                            <span class="material-symbols-rounded">cloud_upload</span>
                            Simpan Data Produksi
                        </button>
                        <p class="text-[9px] font-bold text-gray-300 uppercase tracking-widest text-center md:text-left md:w-48 leading-relaxed">
                            Data akan otomatis mengupdate stok gudang & notifikasi reseller.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;700;800;1000&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
</style>
@endsection