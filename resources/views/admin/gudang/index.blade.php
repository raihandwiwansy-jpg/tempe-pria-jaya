@extends('layouts.admin')

@section('title', 'Gudang Pusat Ultra Premium')

@section('content')
<div class="container mx-auto pb-32 px-4 sm:px-6 lg:px-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
        <div class="relative">
            <div class="absolute -left-6 top-0 bottom-0 w-2 bg-gradient-to-b from-green-600 to-emerald-400 rounded-full shadow-lg"></div>
            <h2 class="text-5xl font-[1000] text-gray-900 tracking-tight ml-2">Gudang <span class="text-green-600">Pusat</span></h2>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.3em] ml-2 mt-2 flex items-center gap-2">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                Inventory Control System
            </p>
        </div>
        
        <a href="{{ route('admin.gudang.create') }}" class="bg-gray-900 hover:bg-green-600 text-white px-10 py-5 rounded-[2rem] shadow-xl transition-all duration-500 flex items-center gap-4 font-black text-xs uppercase group border-b-4 border-black/20">
            <span class="material-symbols-rounded text-2xl group-hover:rotate-180 transition-transform duration-700">add_circle</span>
            Tambah Master Produk
        </a>
    </div>

    {{-- Main Table Container --}}
    <div class="bg-white rounded-[3.5rem] shadow-2xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-widest">Detail Produk</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-widest text-center">Engine Control</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-widest text-center">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @foreach($barangs as $b)
                    <tr class="group relative overflow-hidden bg-white">
                        <td colspan="3" class="p-0 relative">
                            {{-- LAYER BELAKANG: Tombol Hapus --}}
                            <div class="absolute inset-y-0 right-0 w-32 bg-red-600 flex items-center justify-center">
                                <form action="{{ route('admin.gudang.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex flex-col items-center text-white gap-1 active:scale-90 transition-transform">
                                        <span class="material-symbols-rounded text-3xl font-light">delete_forever</span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter">Destroy</span>
                                    </button>
                                </form>
                            </div>

                            {{-- LAYER DEPAN: Konten Utama --}}
                            <div class="relative bg-white flex flex-wrap lg:flex-nowrap items-center py-6 px-10 transition-all duration-300 ease-[cubic-bezier(0.2,0.8,0.2,1)] swipe-container"
                                 data-id="{{ $b->id }}">
                                
                                {{-- 1. Info Produk --}}
                                <div class="flex-1 flex items-center gap-6 min-w-[250px]">
                                    <div class="w-16 h-16 rounded-[1.5rem] bg-gray-100 overflow-hidden border-2 border-white shadow-md flex-shrink-0">
                                        @if($b->foto)
                                            <img src="{{ asset('storage/'.$b->foto) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <span class="material-symbols-rounded">inventory_2</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-black text-gray-800 text-lg leading-tight group-hover:text-green-600 transition-colors">{{ $b->nama_barang }}</h3>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1 italic">{{ $b->kode_barang }}</p>
                                    </div>
                                </div>

                                {{-- 2. Quick Adjust Control (Tambah/Kurang) --}}
                                <div class="flex-1 lg:flex-none flex items-center justify-center gap-4 px-6 my-4 lg:my-0">
                                    {{-- In --}}
                                    <form action="{{ route('admin.gudang.transaksi') }}" method="POST" class="flex bg-gray-50 p-1.5 rounded-3xl border border-gray-100 hover:border-green-300 transition-all shadow-inner">
                                        @csrf
                                        <input type="hidden" name="barang_id" value="{{ $b->id }}">
                                        <input type="hidden" name="tipe" value="masuk">
                                        <input type="number" name="jumlah" value="1" min="1" class="w-10 bg-transparent border-none focus:ring-0 text-xs font-black text-center" required>
                                        <button type="submit" class="w-10 h-10 bg-white text-green-600 rounded-2xl shadow-sm hover:bg-green-600 hover:text-white transition-all flex items-center justify-center">
                                            <span class="material-symbols-rounded">add</span>
                                        </button>
                                    </form>

                                    {{-- Out --}}
                                    <form action="{{ route('admin.gudang.transaksi') }}" method="POST" class="flex bg-gray-50 p-1.5 rounded-3xl border border-gray-100 hover:border-red-300 transition-all shadow-inner">
                                        @csrf
                                        <input type="hidden" name="barang_id" value="{{ $b->id }}">
                                        <input type="hidden" name="tipe" value="keluar">
                                        <input type="number" name="jumlah" value="1" min="1" class="w-10 bg-transparent border-none focus:ring-0 text-xs font-black text-center" required>
                                        <button type="submit" class="w-10 h-10 bg-white text-red-600 rounded-2xl shadow-sm hover:bg-red-600 hover:text-white transition-all flex items-center justify-center">
                                            <span class="material-symbols-rounded">remove</span>
                                        </button>
                                    </form>
                                </div>

                                {{-- 3. Status Stok --}}
                                <div class="flex-none px-6 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-3xl font-[1000] {{ $b->stok_pusat <= 10 ? 'text-red-500' : 'text-gray-900' }} leading-none">
                                            {{ $b->stok_pusat }}
                                        </span>
                                        <span class="text-[8px] font-black uppercase text-gray-400 tracking-tighter mt-1">Items In Stock</span>
                                    </div>
                                </div>

                                {{-- 4. Mobile Hint --}}
                                <div class="lg:hidden ml-4 text-gray-200">
                                    <span class="material-symbols-rounded animate-pulse">swipe_left</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfc; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .swipe-container { cursor: grab; touch-action: pan-y; position: relative; z-index: 10; }
    .swipe-container:active { cursor: grabbing; }
</style>

<script>
    document.querySelectorAll('.swipe-container').forEach(container => {
        let startX = 0;
        let currentX = 0;
        let isDragging = false;
        const maxSlide = -120; // Ukuran area tombol hapus
        const snapPoint = -60;

        container.addEventListener('touchstart', e => {
            // Biarkan input number tetap bisa diklik
            if (e.target.tagName === 'INPUT' || e.target.closest('button')) return;
            
            startX = e.touches[0].clientX - currentX;
            container.style.transition = 'none';
            isDragging = true;
        });

        container.addEventListener('touchmove', e => {
            if (!isDragging) return;
            let x = e.touches[0].clientX - startX;
            if (x <= 0 && x >= maxSlide) {
                currentX = x;
                container.style.transform = `translateX(${x}px)`;
            }
        });

        container.addEventListener('touchend', () => {
            if (!isDragging) return;
            isDragging = false;
            container.style.transition = 'transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            currentX = currentX < snapPoint ? maxSlide : 0;
            container.style.transform = `translateX(${currentX}px)`;
        });

        // Mouse Events untuk Desktop
        container.addEventListener('mousedown', e => {
            if (e.target.tagName === 'INPUT' || e.target.closest('button') || e.target.closest('a')) return;
            startX = e.clientX - currentX;
            container.style.transition = 'none';
            isDragging = true;
        });

        window.addEventListener('mousemove', e => {
            if (!isDragging) return;
            let x = e.clientX - startX;
            if (x <= 0 && x >= maxSlide) {
                currentX = x;
                container.style.transform = `translateX(${x}px)`;
            }
        });

        window.addEventListener('mouseup', () => {
            if (!isDragging) return;
            isDragging = false;
            container.style.transition = 'transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            currentX = currentX < snapPoint ? maxSlide : 0;
            container.style.transform = `translateX(${currentX}px)`;
        });
    });

    // Menutup swipe saat klik di luar
    document.addEventListener('mousedown', (e) => {
        if (!e.target.closest('.swipe-container')) {
            document.querySelectorAll('.swipe-container').forEach(el => {
                el.style.transform = 'translateX(0px)';
            });
        }
    });
</script>
@endsection