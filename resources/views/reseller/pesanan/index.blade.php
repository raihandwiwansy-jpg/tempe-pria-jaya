@extends('layouts.reseller')

@section('title', 'My Order Management')

@section('content')
<div class="container mx-auto pb-32 px-4 md:px-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-8">
        <div class="relative">
            <div class="absolute -left-6 top-0 bottom-0 w-2 bg-gradient-to-b from-emerald-600 to-teal-400 rounded-full shadow-[0_0_15px_rgba(16,185,129,0.4)]"></div>
            <h2 class="text-5xl font-[1000] text-gray-900 tracking-tight ml-2 leading-none">Order <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Center</span></h2>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.4em] ml-2 mt-3 flex items-center gap-2">
                <span class="material-symbols-rounded text-xs text-emerald-500">conveyor_belt</span>
                Partner Order Tracking System
            </p>
        </div>
        
        <a href="{{ route('reseller.pesanan.create') }}" class="group bg-gray-900 hover:bg-emerald-600 text-white px-10 py-5 rounded-[2.5rem] shadow-2xl transition-all duration-500 flex items-center gap-4 font-black text-xs uppercase tracking-widest active:scale-95 border-b-4 border-black/20">
            <span class="material-symbols-rounded text-2xl group-hover:rotate-180 transition-transform duration-700">add_shopping_cart</span>
            New Order Entry
        </a>
    </div>

    {{-- Orders Table Card --}}
    <div class="bg-white/70 backdrop-blur-2xl rounded-[3.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.03)] border border-white overflow-hidden">
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Customer & Shipping</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Unit Specifications</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-center">Current Status</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-right">Workflow Control</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @forelse($pesanans as $p)
                    <tr class="group relative bg-white hover:bg-emerald-50/20 transition-all duration-500">
                        <td colspan="4" class="p-0 relative">
                            {{-- BACK LAYER: Danger Action --}}
                            <div class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-red-600 to-red-500 flex items-center justify-center">
                                <form action="{{ route('reseller.pesanan.destroy', $p->id_pemesanan) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini secara permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex flex-col items-center text-white gap-1 active:scale-90 transition-transform">
                                        <span class="material-symbols-rounded text-3xl">delete_sweep</span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter text-white/80">Cancel</span>
                                    </button>
                                </form>
                            </div>

                            {{-- FRONT LAYER: Content --}}
                            <div class="relative bg-white flex items-center py-8 px-10 transition-transform duration-500 ease-[cubic-bezier(0.2,0.8,0.2,1)] swipe-row">
                                
                                {{-- Customer Info --}}
                                <div class="flex-1">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
                                            <span class="material-symbols-rounded text-emerald-600">person_pin_circle</span>
                                        </div>
                                        <div>
                                            <h4 class="font-black text-gray-800 text-lg tracking-tight">{{ $p->nama_pemesan }}</h4>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-1">
                                                <span class="material-symbols-rounded text-[12px]">local_shipping</span>
                                                ETA: {{ \Carbon\Carbon::parse($p->tanggal_kirim)->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="mt-3 text-[11px] font-medium text-gray-500 leading-relaxed max-w-xs pl-16">
                                        {{ Str::limit($p->alamat_pemesan, 60) }}
                                    </p>
                                </div>

                                {{-- Unit Detail --}}
                                <div class="flex-1 flex justify-center">
                                    <div class="bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 flex items-center gap-4">
                                        <div class="text-right">
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Quantity</p>
                                            <p class="text-xl font-black text-gray-800 tracking-tighter leading-none">{{ $p->jumlah }} Unit</p>
                                        </div>
                                        <div class="w-[1px] h-8 bg-gray-200"></div>
                                        <div>
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Dimension</p>
                                            <p class="text-sm font-black text-emerald-600 tracking-widest">{{ strtoupper($p->ukuran) }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Status Indicator --}}
                                <div class="flex-1 flex justify-center">
                                    @php
                                        $colors = [
                                            'pending' => 'amber',
                                            'diproses' => 'blue',
                                            'dikirim' => 'indigo',
                                            'selesai' => 'emerald'
                                        ];
                                        $c = $colors[$p->status] ?? 'gray';
                                    @endphp
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="px-6 py-2 rounded-full text-[10px] font-[1000] uppercase tracking-[0.2em] bg-{{ $c }}-50 text-{{ $c }}-600 border border-{{ $c }}-100/50 shadow-sm">
                                            {{ $p->status }}
                                        </span>
                                        @if($p->status != 'selesai')
                                            <div class="flex gap-1">
                                                <div class="w-1 h-1 rounded-full bg-{{ $c }}-400 animate-ping"></div>
                                                <div class="w-1 h-1 rounded-full bg-{{ $c }}-400 animate-ping delay-100"></div>
                                                <div class="w-1 h-1 rounded-full bg-{{ $c }}-400 animate-ping delay-200"></div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Control Action --}}
                                <div class="flex items-center gap-4 ml-10">
                                    <form action="{{ route('reseller.pesanan.status', $p->id_pemesanan) }}" method="POST" class="relative group/select">
                                        @csrf @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" 
                                            class="appearance-none bg-white border-2 border-gray-100 text-gray-700 py-3 pl-5 pr-12 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all cursor-pointer shadow-sm">
                                            <option value="pending" {{ $p->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="diproses" {{ $p->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="dikirim" {{ $p->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                            <option value="selesai" {{ $p->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                        <span class="material-symbols-rounded absolute right-4 top-1/2 -translate-y-1/2 text-emerald-500 pointer-events-none group-hover/select:rotate-180 transition-transform">expand_more</span>
                                    </form>
                                    
                                    <div class="md:hidden text-gray-200">
                                        <span class="material-symbols-rounded animate-pulse">swipe_left</span>
                                    </div>
                                </div>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-40 text-center">
                            <span class="material-symbols-rounded text-8xl text-gray-100">shopping_bag</span>
                            <h3 class="text-xl font-black text-gray-300 uppercase tracking-[0.3em] mt-6">No Active Orders</h3>
                            <p class="text-[10px] font-bold text-gray-400 mt-2 uppercase tracking-widest">Start your first order by clicking the button above</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800;1000&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .swipe-row { cursor: grab; user-select: none; }
</style>

<script>
    // Global Swipe Implementation
    document.querySelectorAll('.swipe-row').forEach(row => {
        let startX = 0, currentX = 0, isDragging = false;
        const maxSlide = -120, snapPoint = -60;

        row.addEventListener('touchstart', e => { 
            if (e.target.closest('select') || e.target.closest('button')) return;
            startX = e.touches[0].clientX - currentX; 
            row.style.transition = 'none'; isDragging = true; 
        });

        row.addEventListener('touchmove', e => {
            if (!isDragging) return;
            let x = e.touches[0].clientX - startX;
            if (x <= 0 && x >= maxSlide - 20) { currentX = x; row.style.transform = `translateX(${x}px)`; }
        });

        row.addEventListener('touchend', () => {
            isDragging = false;
            row.style.transition = 'transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            currentX = currentX < snapPoint ? maxSlide : 0;
            row.style.transform = `translateX(${currentX}px)`;
        });

        // Mouse Support
        row.addEventListener('mousedown', e => {
            if (e.target.closest('select') || e.target.closest('button')) return;
            startX = e.clientX - currentX;
            row.style.transition = 'none'; isDragging = true;
        });

        window.addEventListener('mousemove', e => {
            if (!isDragging) return;
            let x = e.clientX - startX;
            if (x <= 0 && x >= maxSlide - 20) { currentX = x; row.style.transform = `translateX(${x}px)`; }
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