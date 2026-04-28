@extends('layouts.admin')

@section('title', 'Order Control Center')

@section('content')
<div class="container mx-auto pb-32 px-4 sm:px-6 lg:px-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
        <div class="relative">
            <div class="absolute -left-6 top-0 bottom-0 w-2 bg-gradient-to-b from-emerald-600 to-emerald-400 rounded-full shadow-lg"></div>
            <h2 class="text-5xl font-[1000] text-gray-900 tracking-tight ml-2 leading-none">Order <span class="text-emerald-600">Hub</span></h2>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.3em] ml-2 mt-3 italic flex items-center gap-2">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
                Real-time Fulfillment System
            </p>
        </div>
        
        <a href="{{ route('admin.pesanan.create') }}" class="w-full md:w-auto bg-gray-900 hover:bg-emerald-600 text-white px-10 py-5 rounded-[2rem] shadow-2xl transition-all duration-500 flex items-center justify-center gap-4 font-black text-xs uppercase group border-b-4 border-black/20">
            <span class="material-symbols-rounded text-2xl group-hover:rotate-90 transition-transform">add_shopping_cart</span>
            New Manual Order
        </a>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Pending</p>
            <p class="text-2xl font-[1000] text-amber-500">{{ $pesanans->where('status', 'pending')->count() }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Active Process</p>
            <p class="text-2xl font-[1000] text-blue-500">{{ $pesanans->where('status', 'diproses')->count() }}</p>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="bg-white/70 backdrop-blur-xl rounded-[3.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.03)] border border-white overflow-hidden">
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Customer & Store</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Order Details</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Logistics</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-center">Operation</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @forelse($pesanans as $p)
                    <tr class="group relative overflow-hidden bg-white hover:bg-emerald-50/10 transition-all duration-500">
                        <td colspan="4" class="p-0 relative">
                            {{-- BACK LAYER: Cancel/Delete --}}
                            <div class="absolute inset-y-0 right-0 w-32 bg-red-600 flex items-center justify-center">
                                <form action="{{ route('admin.pesanan.destroy', $p->id_pemesanan) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex flex-col items-center text-white gap-1 active:scale-90 transition-transform">
                                        <span class="material-symbols-rounded text-3xl">cancel</span>
                                        <span class="text-[9px] font-black uppercase">Cancel</span>
                                    </button>
                                </form>
                            </div>

                            {{-- FRONT LAYER: Swipeable Content --}}
                            <div class="relative bg-white flex items-center py-8 px-10 transition-transform duration-500 ease-[cubic-bezier(0.2,0.8,0.2,1)] swipe-row">
                                
                                {{-- 1. Customer Info --}}
                                <div class="flex-1 flex items-center gap-6 min-w-[250px]">
                                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-inner">
                                        <span class="material-symbols-rounded text-3xl">person_pin</span>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-[9px] font-black bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-md uppercase tracking-tighter">
                                                {{ $p->reseller->name ?? 'Admin Direct' }}
                                            </span>
                                        </div>
                                        <h4 class="font-black text-gray-800 text-lg leading-none mb-1">{{ $p->nama_pemesan }}</h4>
                                        <p class="text-[10px] font-bold text-gray-400 flex items-center gap-1">
                                            <span class="material-symbols-rounded text-xs">distance</span>
                                            {{ Str::limit($p->alamat_pemesan, 30) }}
                                        </p>
                                    </div>
                                </div>

                                {{-- 2. Detail Produk & Quantity --}}
                                <div class="flex-1 px-6">
                                    <div class="bg-gray-50 p-4 rounded-3xl inline-flex flex-col min-w-[120px]">
                                        <span class="text-2xl font-[1000] text-gray-900 leading-none mb-1">{{ $p->jumlah }}<span class="text-xs ml-1 text-gray-400 font-bold uppercase">Pcs</span></span>
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">{{ $p->ukuran }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- 3. Timeline --}}
                                <div class="flex-1 px-6">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2 text-gray-800 font-black mb-1">
                                            <span class="material-symbols-rounded text-sm">event_available</span>
                                            <span class="text-sm tracking-tight">{{ \Carbon\Carbon::parse($p->tanggal_kirim)->format('d M, Y') }}</span>
                                        </div>
                                        <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-50 w-fit px-2 py-0.5 rounded-md">
                                            {{ \Carbon\Carbon::parse($p->tanggal_kirim)->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>

                                {{-- 4. Status Controller --}}
                                <div class="flex-1 flex flex-col items-end gap-3 min-w-[150px]">
                                    <form action="{{ route('admin.pesanan.status', $p->id_pemesanan) }}" method="POST" class="w-full">
                                        @csrf @method('PATCH')
                                        <div class="relative group/select">
                                            <select name="status" onchange="this.form.submit()" 
                                                class="appearance-none w-full bg-gray-900 text-white py-3 pl-4 pr-10 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] focus:ring-4 focus:ring-emerald-500/20 transition-all cursor-pointer border-none shadow-xl shadow-gray-200">
                                                <option value="pending" {{ $p->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="diproses" {{ $p->status == 'diproses' ? 'selected' : '' }}>Processing</option>
                                                <option value="dikirim" {{ $p->status == 'dikirim' ? 'selected' : '' }}>On Shipping</option>
                                                <option value="selesai" {{ $p->status == 'selesai' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                            <span class="material-symbols-rounded absolute right-3 top-1/2 -translate-y-1/2 text-white/50 pointer-events-none group-hover/select:text-emerald-400 transition-colors">unfold_more</span>
                                        </div>
                                    </form>

                                    {{-- Visual Progress Dot --}}
                                    <div class="flex gap-1">
                                        @php $levels = ['pending', 'diproses', 'dikirim', 'selesai']; $currentIdx = array_search($p->status, $levels); @endphp
                                        @foreach($levels as $index => $level)
                                            <div class="h-1 w-6 rounded-full {{ $index <= $currentIdx ? 'bg-emerald-500' : 'bg-gray-100' }} transition-colors duration-700"></div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Mobile Hint --}}
                                <div class="lg:hidden ml-4 text-gray-200">
                                    <span class="material-symbols-rounded animate-pulse">swipe_left</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-40 text-center">
                            <div class="flex flex-col items-center">
                                <span class="material-symbols-rounded text-7xl text-gray-100 animate-bounce">shopping_basket</span>
                                <h3 class="text-2xl font-[1000] text-gray-300 uppercase tracking-[0.3em] mt-6">No Orders Yet</h3>
                                <p class="text-gray-400 text-sm font-medium mt-2">Dapur produksi masih menunggu pesanan pertama.</p>
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
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfc; }
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
            if (e.target.tagName === 'SELECT') return;
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

        row.addEventListener('mousedown', e => {
            if (e.target.tagName === 'SELECT') return;
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