@extends('layouts.admin')

@section('title', 'Katalog Reseller Intelligence')

@section('content')
<div class="container mx-auto pb-32 px-4 sm:px-6 lg:px-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
        <div class="relative group">
            <div class="absolute -left-6 top-0 bottom-0 w-2 bg-gradient-to-b from-emerald-600 to-teal-400 rounded-full shadow-[0_0_15px_rgba(16,185,129,0.4)]"></div>
            <h2 class="text-5xl font-[1000] text-gray-900 tracking-tight ml-2 leading-none italic">Katalog <span class="text-emerald-600">Reseller</span></h2>
            <div class="flex items-center gap-3 ml-2 mt-3">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.4em]">Marketplace Distribution Channel</p>
            </div>
        </div>
        
        <a href="{{ route('admin.barang_reseller.create') }}" class="w-full md:w-auto bg-gray-900 hover:bg-emerald-600 text-white px-10 py-5 rounded-[2rem] shadow-xl transition-all duration-500 flex items-center justify-center gap-4 font-black text-xs uppercase tracking-widest group border-b-4 border-black/20">
            <span class="material-symbols-rounded text-2xl group-hover:scale-125 transition-transform">rocket_launch</span>
            Publish Barang Baru
        </a>
    </div>

    {{-- Stats Mini --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Produk</p>
            <p class="text-2xl font-[1000] text-gray-800">{{ $katalog->count() }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Kategori Aktif</p>
            <p class="text-2xl font-[1000] text-emerald-600">{{ $katalog->unique('kategori')->count() }}</p>
        </div>
    </div>

    {{-- Main Container --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-[3.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.03)] border border-white overflow-hidden">
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Identity</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Channel Info</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-center">Market Price</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-right">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @forelse($katalog as $item)
                    <tr class="group relative overflow-hidden bg-white hover:bg-emerald-50/10 transition-all duration-500">
                        <td colspan="4" class="p-0 relative">
                            {{-- BACK LAYER: Danger Action (DELETE) --}}
                            <div class="absolute inset-y-0 right-0 w-32 bg-red-600 flex items-center justify-center">
                                <form action="{{ route('admin.barang_reseller.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Tarik barang ini dari katalog?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex flex-col items-center text-white gap-1 active:scale-90 transition-transform">
                                        <span class="material-symbols-rounded text-3xl font-light">tab_unselected</span>
                                        <span class="text-[9px] font-black uppercase">Unpublish</span>
                                    </button>
                                </form>
                            </div>

                            {{-- FRONT LAYER: Swipeable Content --}}
                            <div class="relative bg-white flex items-center py-8 px-10 transition-transform duration-500 ease-[cubic-bezier(0.2,0.8,0.2,1)] swipe-row">
                                
                                {{-- 1. Product Brand & Display --}}
                                <div class="flex-1 flex items-center gap-8 min-w-[300px]">
                                    <div class="relative flex-shrink-0">
                                        <div class="w-20 h-20 rounded-[2.2rem] bg-gray-100 overflow-hidden border-2 border-white shadow-xl group-hover:rotate-3 transition-transform duration-500">
                                            @if($item->foto)
                                                <img src="{{ asset('storage/' . $item->foto) }}" class="w-full h-full object-cover scale-110 group-hover:scale-100 transition-transform duration-700">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50">
                                                    <span class="material-symbols-rounded text-3xl">photo_library</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="absolute -bottom-2 -right-2 bg-emerald-500 text-white p-1.5 rounded-xl shadow-lg border-2 border-white">
                                            <span class="material-symbols-rounded text-sm block">verified</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="font-black text-gray-800 text-xl tracking-tight leading-none mb-2">{{ $item->nama_display }}</h3>
                                        <div class="flex items-center gap-3">
                                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-1 rounded-md border border-gray-100">{{ $item->barang->kode_barang }}</span>
                                            <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100">{{ $item->kategori }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. Inventory Sync --}}
                                <div class="flex-1 px-10 hidden lg:block">
                                    <div class="flex flex-col gap-1">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Warehouse Sync</p>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xl font-black text-gray-800">{{ $item->barang->stok_pusat }}</span>
                                            <span class="text-xs font-bold text-gray-400 uppercase">Available Units</span>
                                        </div>
                                        <div class="w-32 h-1.5 bg-gray-100 rounded-full mt-1 overflow-hidden">
                                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ min($item->barang->stok_pusat, 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- 3. Pricing --}}
                                <div class="flex-1 flex flex-col items-center justify-center">
                                    <p class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] mb-1">Reseller Price</p>
                                    <div class="flex items-start">
                                        <span class="text-xs font-black text-gray-400 mt-1 mr-1">Rp</span>
                                        <span class="text-3xl font-[1000] text-gray-900 tracking-tighter leading-none">
                                            {{ number_format($item->harga_jual_reseller, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                {{-- 4. Controls --}}
                                <div class="flex items-center gap-6">
                                    <div class="hidden sm:flex flex-col items-end">
                                        <span class="flex h-3 w-3 relative">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                        </span>
                                        <p class="text-[8px] font-black text-emerald-500 uppercase mt-1">Live</p>
                                    </div>
                                    <div class="lg:hidden text-gray-200">
                                        <span class="material-symbols-rounded animate-pulse">swipe_left</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-40 text-center bg-white">
                            <div class="flex flex-col items-center">
                                <div class="w-32 h-32 bg-emerald-50 rounded-[3rem] flex items-center justify-center mb-8 rotate-12 group-hover:rotate-0 transition-transform">
                                    <span class="material-symbols-rounded text-6xl text-emerald-200">storefront</span>
                                </div>
                                <h3 class="text-2xl font-[1000] text-gray-300 uppercase tracking-[0.2em]">Empty Showcase</h3>
                                <p class="text-gray-400 text-sm mt-3 font-medium">Belum ada barang yang dipajang di katalog reseller.</p>
                                <a href="{{ route('admin.barang_reseller.create') }}" class="mt-8 text-emerald-600 font-black text-xs uppercase tracking-widest hover:bg-emerald-50 px-6 py-3 rounded-full transition-all border border-emerald-100">Publish Now</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;700;800;1000&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fafafa; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .swipe-row { cursor: grab; user-select: none; }
    .swipe-row:active { cursor: grabbing; }
</style>

<script>
    document.querySelectorAll('.swipe-row').forEach(row => {
        let startX = 0;
        let currentX = 0;
        let isDragging = false;
        const maxSlide = -120;
        const snapPoint = -60;

        row.addEventListener('touchstart', e => {
            if (e.target.closest('button')) return;
            startX = e.touches[0].clientX - currentX;
            row.style.transition = 'none';
            isDragging = true;
        });

        row.addEventListener('touchmove', e => {
            if (!isDragging) return;
            let x = e.touches[0].clientX - startX;
            if (x <= 0 && x >= maxSlide - 20) {
                currentX = x;
                row.style.transform = `translateX(${x}px)`;
            }
        });

        row.addEventListener('touchend', () => {
            isDragging = false;
            row.style.transition = 'transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            currentX = currentX < snapPoint ? maxSlide : 0;
            row.style.transform = `translateX(${currentX}px)`;
        });

        // Mouse Support
        row.addEventListener('mousedown', e => {
            if (e.target.closest('button')) return;
            startX = e.clientX - currentX;
            row.style.transition = 'none';
            isDragging = true;
        });

        window.addEventListener('mousemove', e => {
            if (!isDragging) return;
            let x = e.clientX - startX;
            if (x <= 0 && x >= maxSlide - 20) {
                currentX = x;
                row.style.transform = `translateX(${x}px)`;
            }
        });

        window.addEventListener('mouseup', () => {
            if (!isDragging) return;
            isDragging = false;
            row.style.transition = 'transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            currentX = currentX < snapPoint ? maxSlide : 0;
            row.style.transform = `translateX(${currentX}px)`;
        });
    });
</script>
@endsection