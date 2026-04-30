@extends('layouts.admin')

@section('title', 'Neural Production Analytics')

@section('content')
<div class="container mx-auto pb-32 px-4 sm:px-6 lg:px-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
        <div class="relative group">
            <div class="absolute -left-6 top-0 bottom-0 w-2 bg-gradient-to-b from-indigo-600 to-emerald-400 rounded-full shadow-[0_0_15px_rgba(79,70,229,0.4)]"></div>
            <h2 class="text-5xl font-[1000] text-gray-900 tracking-tight ml-2 leading-none">Riwayat <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-emerald-500">Produksi</span></h2>
            <div class="flex items-center gap-3 ml-2 mt-3">
                <span class="flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.3em]">Manufacturing Intelligence System</p>
            </div>
        </div>
        
        <a href="{{ route('admin.produksi.create') }}" class="w-full md:w-auto bg-gray-900 hover:bg-indigo-600 text-white px-10 py-5 rounded-[2rem] shadow-[0_20px_40px_rgba(0,0,0,0.1)] transition-all duration-500 flex items-center justify-center gap-4 font-black text-xs uppercase tracking-widest active:scale-95 group border-b-4 border-black/20">
            <span class="material-symbols-rounded text-2xl group-hover:rotate-45 transition-transform duration-500">factory</span>
            Tambah Sesi Produksi
        </a>
    </div>

    {{-- REKAPAN ANALYTICS CARDS (NEW SECTION) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white/80 backdrop-blur-xl p-8 rounded-[3rem] border border-white shadow-sm hover:shadow-indigo-100 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest leading-none">Today's Output</p>
                <span class="material-symbols-rounded text-indigo-200 group-hover:text-indigo-500 transition-colors">bolt</span>
            </div>
            <h3 class="text-3xl font-[1000] text-gray-900 tracking-tighter">{{ number_format($prodHariIni) }} <span class="text-xs font-bold text-gray-400 uppercase">Kg</span></h3>
            <div class="mt-4 flex items-center gap-2">
                <div class="px-2 py-1 bg-amber-50 rounded-lg border border-amber-100 flex items-center gap-1">
                    <span class="material-symbols-rounded text-[10px] text-amber-600">nutrition</span>
                    <span class="text-[9px] font-black text-amber-700">{{ $kedelaiHariIni }}kg</span>
                </div>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">Raw Material</p>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-xl p-8 rounded-[3rem] border border-white shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <p class="text-[9px] font-black text-purple-500 uppercase tracking-widest leading-none">Weekly Cycles</p>
                <span class="material-symbols-rounded text-purple-200">event_repeat</span>
            </div>
            <h3 class="text-3xl font-[1000] text-gray-900 tracking-tighter">{{ number_format($prodMingguIni) }} <span class="text-xs font-bold text-gray-400 uppercase">Kg</span></h3>
            <div class="w-full h-1.5 bg-purple-50 rounded-full mt-5 overflow-hidden">
                <div class="w-2/3 h-full bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full"></div>
            </div>
        </div>

        <div class="bg-gray-900 p-8 rounded-[3rem] shadow-2xl shadow-indigo-200 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-[9px] font-black text-emerald-400 uppercase tracking-widest leading-none mb-6 text-center">Monthly Resource Consumption</p>
                <div class="flex justify-between items-center px-2">
                    <div class="text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-tighter mb-1">Kedelai</p>
                        <h4 class="text-xl font-[1000] text-white tracking-tighter">{{ number_format($kedelaiBulanIni) }}kg</h4>
                    </div>
                    <div class="h-8 w-[1px] bg-gray-800"></div>
                    <div class="text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-tighter mb-1">Plastik</p>
                        <h4 class="text-xl font-[1000] text-white tracking-tighter">{{ number_format($plastikBulanIni) }}kg</h4>
                    </div>
                </div>
            </div>
            {{-- Decorative glow --}}
            <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-indigo-500/20 blur-[50px] rounded-full"></div>
        </div>

        <div class="bg-white/80 backdrop-blur-xl p-8 rounded-[3rem] border border-white shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <p class="text-[9px] font-black text-orange-500 uppercase tracking-widest leading-none">Annual Accumulation</p>
                <span class="material-symbols-rounded text-orange-200">analytics</span>
            </div>
            <h3 class="text-3xl font-[1000] text-gray-900 tracking-tighter">{{ number_format($prodTahunIni) }} <span class="text-xs font-bold text-gray-400 uppercase">Kg</span></h3>
            <p class="text-[9px] text-gray-400 font-bold mt-4 uppercase tracking-widest italic">Total unit produced in {{ now()->year }}</p>
        </div>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
    <div class="fixed top-10 right-10 z-[100] animate-bounce-in">
        <div class="bg-white/90 backdrop-blur-xl border-l-8 border-indigo-500 shadow-2xl p-6 rounded-3xl flex items-center gap-5 pr-12 relative">
            <div class="bg-indigo-500 text-white p-2 rounded-xl">
                <span class="material-symbols-rounded">precision_manufacturing</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-indigo-500 uppercase">Production Logged</p>
                <p class="text-gray-700 font-bold text-sm">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="absolute top-2 right-2 text-gray-300 hover:text-red-500">
                <span class="material-symbols-rounded text-sm">close</span>
            </button>
        </div>
    </div>
    @endif

    {{-- Main Production Card --}}
    <div class="bg-white/70 backdrop-blur-2xl rounded-[3.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.03)] border border-white overflow-hidden">
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-10 py-10 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Timeline</th>
                        <th class="px-10 py-10 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-center">Output (KG)</th>
                        <th class="px-10 py-10 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-center hidden md:table-cell">Resources Used</th>
                        <th class="px-10 py-10 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-right">Control</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @forelse($data as $d)
                    <tr class="group relative overflow-hidden bg-white hover:bg-indigo-50/20 transition-all duration-500">
                        <td colspan="4" class="p-0 relative">
                            {{-- BACK LAYER: Danger Action --}}
                            <div class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-red-600 to-red-500 flex items-center justify-center">
                                <form action="{{ route('admin.produksi.destroy', $d->id) }}" method="POST" onsubmit="return confirm('Batalkan sesi produksi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex flex-col items-center text-white gap-1 active:scale-90 transition-transform">
                                        <span class="material-symbols-rounded text-3xl font-light">delete_forever</span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter">Delete</span>
                                    </button>
                                </form>
                            </div>

                            {{-- FRONT LAYER: Swipeable Content --}}
                            <div class="relative bg-white flex items-center py-8 px-10 transition-transform duration-500 ease-[cubic-bezier(0.2,0.8,0.2,1)] swipe-row">
                                
                                {{-- 1. Date Info --}}
                                <div class="flex-1 flex items-center gap-6">
                                    <div class="w-16 h-16 rounded-[1.8rem] bg-indigo-50 flex flex-col items-center justify-center border border-indigo-100 shadow-inner group-hover:scale-110 transition-transform duration-500">
                                        <span class="text-xs font-black text-indigo-600 leading-none">{{ \Carbon\Carbon::parse($d->tanggal)->format('d') }}</span>
                                        <span class="text-[8px] font-black text-indigo-400 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($d->tanggal)->format('M') }}</span>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-gray-800 text-lg tracking-tight">{{ \Carbon\Carbon::parse($d->tanggal)->format('l') }}</h4>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Logged: {{ $d->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. Output --}}
                                <div class="flex-1 flex justify-center">
                                    <div class="relative inline-block">
                                        <span class="text-4xl font-[1000] text-gray-900 tracking-tighter leading-none">{{ $d->jumlah_produksi }}</span>
                                        <span class="text-[10px] font-black text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded-md ml-1 align-top shadow-sm">KG</span>
                                    </div>
                                </div>

                                {{-- 3. Raw Materials (Resources) --}}
                                <div class="flex-1 hidden md:flex flex-col gap-2 items-center justify-center px-10">
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center gap-2 bg-amber-50 px-3 py-1.5 rounded-xl border border-amber-100">
                                            <span class="material-symbols-rounded text-sm text-amber-600">nutrition</span>
                                            <span class="text-[11px] font-black text-amber-700 tracking-tight">{{ $d->kedelai_kg }}kg</span>
                                        </div>
                                        <div class="flex items-center gap-2 bg-blue-50 px-3 py-1.5 rounded-xl border border-blue-100">
                                            <span class="material-symbols-rounded text-sm text-blue-500">layers</span>
                                            <span class="text-[11px] font-black text-blue-700 tracking-tight">{{ $d->plastik_kg }}kg</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- 4. Hint/Aksi --}}
                                <div class="flex items-center gap-4">
                                    <div class="md:hidden text-gray-200 animate-pulse">
                                        <span class="material-symbols-rounded">swipe_left</span>
                                    </div>
                                    <div class="hidden md:block">
                                        <div class="w-2 h-10 bg-gray-50 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-32 text-center bg-white">
                            <div class="flex flex-col items-center">
                                <div class="w-32 h-32 bg-gray-50 rounded-[3rem] flex items-center justify-center mb-8 animate-pulse">
                                    <span class="material-symbols-rounded text-6xl text-gray-200">precision_manufacturing</span>
                                </div>
                                <h3 class="text-2xl font-[1000] text-gray-300 uppercase tracking-widest">No Production Data</h3>
                                <p class="text-gray-400 text-sm mt-3 font-medium">Sistem belum mendeteksi aktivitas produksi baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-12 flex items-center justify-center gap-4 bg-indigo-50/50 py-4 px-8 rounded-[2rem] border border-indigo-100/50 w-fit mx-auto shadow-sm">
        <span class="material-symbols-rounded text-indigo-500 text-sm">info</span>
        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em]">Data synced with central warehouse stock</p>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;700;800;1000&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .swipe-row { cursor: grab; user-select: none; }
    .swipe-row:active { cursor: grabbing; }

    @keyframes bounceIn {
        0% { opacity: 0; transform: scale(0.3) translateY(-100px); }
        50% { opacity: 1; transform: scale(1.05) translateY(10px); }
        70% { transform: scale(0.9) translateY(-5px); }
        100% { transform: scale(1) translateY(0); }
    }
    .animate-bounce-in { animation: bounceIn 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
</style>

<script>
    document.querySelectorAll('.swipe-row').forEach(row => {
        let startX = 0;
        let currentX = 0;
        let isDragging = false;
        const maxSlide = -120;
        const snapPoint = -60;

        row.addEventListener('touchstart', e => {
            startX = e.touches[0].clientX - currentX;
            row.style.transition = 'none';
            isDragging = true;
        });

        row.addEventListener('touchmove', e => {
            if (!isDragging) return;
            let x = e.touches[0].clientX - startX;
            if (x <= 0 && x >= maxSlide - 30) {
                currentX = x;
                row.style.transform = `translateX(${x}px)`;
            }
        });

        row.addEventListener('touchend', () => {
            isDragging = false;
            row.style.transition = 'transform 0.5s cubic-bezier(0.2, 0.8, 0.2, 1.2)';
            currentX = currentX < snapPoint ? maxSlide : 0;
            row.style.transform = `translateX(${currentX}px)`;
        });

        // Mouse Support
        row.addEventListener('mousedown', e => {
            startX = e.clientX - currentX;
            row.style.transition = 'none';
            isDragging = true;
        });

        window.addEventListener('mousemove', e => {
            if (!isDragging) return;
            let x = e.clientX - startX;
            if (x <= 0 && x >= maxSlide - 30) {
                currentX = x;
                row.style.transform = `translateX(${x}px)`;
            }
        });

        window.addEventListener('mouseup', () => {
            if (!isDragging) return;
            isDragging = false;
            row.style.transition = 'transform 0.5s cubic-bezier(0.2, 0.8, 0.2, 1.2)';
            currentX = currentX < snapPoint ? maxSlide : 0;
            row.style.transform = `translateX(${currentX}px)`;
        });
    });
</script>
@endsection