@extends('layouts.admin')

@section('title', 'Reseller Management Intelligence')

@section('content')
<div class="container mx-auto pb-32 px-4 sm:px-6 lg:px-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
        <div class="relative">
            <div class="absolute -left-6 top-0 bottom-0 w-2 bg-gradient-to-b from-cyan-600 to-blue-500 rounded-full shadow-[0_0_15px_rgba(6,182,212,0.5)]"></div>
            <h2 class="text-5xl font-[1000] text-gray-900 tracking-tight ml-2 leading-none ">Mitra <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-blue-600">Reseller</span></h2>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.4em] ml-2 mt-3 flex items-center gap-2">
                <span class="material-symbols-rounded text-xs text-cyan-500">hub</span>
                Authorized Distribution Partners
            </p>
        </div>
        
        <a href="{{ route('admin.reseller.create') }}" class="w-full md:w-auto bg-gray-900 hover:bg-cyan-600 text-white px-10 py-5 rounded-[2rem] shadow-2xl transition-all duration-500 flex items-center justify-center gap-4 font-black text-xs uppercase tracking-widest active:scale-95 group border-b-4 border-black/20">
            <span class="material-symbols-rounded text-2xl group-hover:rotate-12 transition-transform">person_add</span>
            Tambah Mitra Baru
        </a>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
    <div class="fixed bottom-10 right-10 z-[100] animate-bounce-in">
        <div class="bg-white/90 backdrop-blur-2xl border-2 border-cyan-500/20 shadow-[0_20px_50px_rgba(0,0,0,0.1)] p-6 rounded-[2.5rem] flex items-center gap-5 pr-12">
            <div class="bg-gradient-to-br from-cyan-500 to-blue-600 text-white p-3 rounded-2xl shadow-lg">
                <span class="material-symbols-rounded">check_circle</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-cyan-600 uppercase tracking-tighter">System Updated</p>
                <p class="text-gray-800 font-bold text-sm">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Reseller Table Container --}}
    <div class="bg-white/70 backdrop-blur-2xl rounded-[3.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.03)] border border-white overflow-hidden">
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Partner Profile</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] hidden md:table-cell">Contact Access</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-center">Account Status</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-right">Operations</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @foreach($data as $r)
                    <tr class="group relative overflow-hidden bg-white hover:bg-cyan-50/20 transition-all duration-500">
                        <td colspan="4" class="p-0 relative">
                            {{-- BACK LAYER: Danger Action --}}
                            <div class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-red-600 to-red-500 flex items-center justify-center">
                                <form action="{{ route('admin.reseller.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Putus kemitraan dengan reseller ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex flex-col items-center text-white gap-1 active:scale-90 transition-transform">
                                        <span class="material-symbols-rounded text-3xl">person_remove</span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter">Delete</span>
                                    </button>
                                </form>
                            </div>

                            {{-- FRONT LAYER: Swipeable Content --}}
                            <div class="relative bg-white flex items-center py-8 px-10 transition-transform duration-500 ease-[cubic-bezier(0.2,0.8,0.2,1)] swipe-row">
                                
                                {{-- 1. Profile Info --}}
                                <div class="flex-1 flex items-center gap-6">
                                    <div class="relative">
                                        <div class="w-16 h-16 rounded-[2rem] bg-gradient-to-br from-cyan-50 to-blue-50 border border-cyan-100 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-500">
                                            <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-br from-cyan-600 to-blue-600">
                                                {{ substr($r->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100">
                                            <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-[1000] text-gray-800 text-xl tracking-tight leading-none mb-2">{{ $r->name }}</h4>
                                        <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">ID Partner: RES-{{ str_pad($r->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>

                                {{-- 2. Contact --}}
                                <div class="flex-1 hidden md:flex flex-col justify-center px-10">
                                    <div class="flex items-center gap-2 text-gray-600 font-bold mb-1">
                                        <span class="material-symbols-rounded text-sm text-cyan-500">alternate_email</span>
                                        <span class="text-sm tracking-tight italic text-gray-500">{{ $r->email }}</span>
                                    </div>
                                    <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Email Verified</span>
                                </div>

                                {{-- 3. Status --}}
                                <div class="flex-1 flex justify-center">
                                    <span class="px-5 py-2 bg-gradient-to-r from-emerald-50 to-cyan-50 text-emerald-600 text-[10px] font-black rounded-xl border border-emerald-100 uppercase tracking-widest shadow-sm">
                                        Active Partner
                                    </span>
                                </div>

                                {{-- 4. Actions --}}
                                <div class="flex items-center gap-4">
                                    <button class="w-12 h-12 rounded-2xl bg-gray-50 hover:bg-cyan-600 hover:text-white text-gray-400 transition-all duration-300 flex items-center justify-center shadow-sm active:scale-90 group/edit">
                                        <span class="material-symbols-rounded text-xl group-hover/edit:rotate-12">edit_square</span>
                                    </button>
                                    <div class="md:hidden text-gray-200">
                                        <span class="material-symbols-rounded animate-pulse">swipe_left</span>
                                    </div>
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
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;700;800;1000&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fafbfc; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .swipe-row { cursor: grab; user-select: none; }
    .swipe-row:active { cursor: grabbing; }

    @keyframes bounceIn {
        0% { opacity: 0; transform: scale(0.3) translateY(100px); }
        50% { opacity: 1; transform: scale(1.05) translateY(-10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-bounce-in { animation: bounceIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
</style>

<script>
    // Logic Swipe yang sama agar konsisten Bree
    document.querySelectorAll('.swipe-row').forEach(row => {
        let startX = 0; let currentX = 0; let isDragging = false;
        const maxSlide = -120; const snapPoint = -60;

        row.addEventListener('touchstart', e => { 
            if (e.target.closest('button')) return;
            startX = e.touches[0].clientX - currentX; 
            row.style.transition = 'none'; 
            isDragging = true; 
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

        row.addEventListener('mousedown', e => {
            if (e.target.closest('button')) return;
            startX = e.clientX - currentX;
            row.style.transition = 'none';
            isDragging = true;
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