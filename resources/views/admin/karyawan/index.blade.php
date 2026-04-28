@extends('layouts.admin')

@section('title', 'Human Capital Management')

@section('content')
<div class="container mx-auto pb-32 px-4 md:px-8">
    {{-- Top Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-8">
        <div class="relative">
            <div class="absolute -left-6 top-0 bottom-0 w-2 bg-gradient-to-b from-indigo-600 to-blue-400 rounded-full shadow-[0_0_15px_rgba(79,70,229,0.4)]"></div>
            <h2 class="text-5xl font-[1000] text-gray-900 tracking-tight ml-2 leading-none ">Personel <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-600">Elite</span></h2>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.4em] ml-2 mt-3 flex items-center gap-2">
                <span class="material-symbols-rounded text-xs text-indigo-500">engineering</span>
                Internal Workforce Management
            </p>
        </div>
        
        <a href="{{ route('admin.karyawan.create') }}" class="group bg-gray-900 hover:bg-indigo-600 text-white px-10 py-5 rounded-[2.5rem] shadow-2xl transition-all duration-500 flex items-center gap-4 font-black text-xs uppercase tracking-widest active:scale-95 border-b-4 border-black/20">
            <span class="material-symbols-rounded text-2xl group-hover:rotate-[360deg] transition-transform duration-700">add_moderator</span>
            Daftarkan Personel
        </a>
    </div>

    {{-- Stats Mini Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                <span class="material-symbols-rounded">groups</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Staff</p>
                <p class="text-xl font-black text-gray-800">{{ $data->count() }} Orang</p>
            </div>
        </div>
        {{-- Kamu bisa tambah card lain di sini (Misal: Karyawan Hadir Hari Ini) --}}
    </div>

    {{-- Main Table Container --}}
    <div class="bg-white/70 backdrop-blur-2xl rounded-[3.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.03)] border border-white overflow-hidden">
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Identity Profile</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-center">Payroll Base</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-center">Attendance Logs</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-right">Administrative Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @forelse($data as $k)
                    <tr class="group relative bg-white hover:bg-indigo-50/20 transition-all duration-500">
                        <td colspan="4" class="p-0 relative">
                            {{-- BACK LAYER: Danger Action --}}
                            <div class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-red-600 to-red-500 flex items-center justify-center">
                                <form action="{{ route('admin.karyawan.destroy', $k->id_karyawan) }}" method="POST" onsubmit="return confirm('Hapus permanen data karyawan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex flex-col items-center text-white gap-1 active:scale-90 transition-transform">
                                        <span class="material-symbols-rounded text-3xl">person_remove</span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter text-white/80">Terminate</span>
                                    </button>
                                </form>
                            </div>

                            {{-- FRONT LAYER: Content --}}
                            <div class="relative bg-white flex items-center py-8 px-10 transition-transform duration-500 ease-[cubic-bezier(0.2,0.8,0.2,1)] swipe-row">
                                
                                {{-- Profile Info --}}
                                <div class="flex-1 flex items-center gap-6">
                                    <div class="w-16 h-16 rounded-[2rem] bg-gradient-to-br from-indigo-50 to-blue-50 border border-indigo-100 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-500">
                                        <span class="text-2xl font-black text-indigo-600">
                                            {{ strtoupper(substr($k->nama, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="font-[1000] text-gray-800 text-xl tracking-tight leading-none mb-2">{{ $k->nama }}</h4>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] flex items-center gap-2">
                                            <span class="material-symbols-rounded text-[12px]">location_on</span>
                                            {{ Str::limit($k->alamat, 30) }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Payroll --}}
                                <div class="flex-1 flex justify-center">
                                    <div class="text-center px-6 py-3 bg-indigo-50/50 rounded-2xl border border-indigo-100/50">
                                        <p class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-1">Base Salary</p>
                                        <p class="font-black text-gray-800 tracking-tighter">Rp {{ number_format($k->gaji_perbulan, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                {{-- Attendance Quick Actions --}}
                                <div class="flex-1 flex justify-center items-center gap-2">
                                    <form action="{{ route('admin.karyawan.absensi') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_karyawan" value="{{ $k->id_karyawan }}">
                                        <input type="hidden" name="status" value="hadir">
                                        <button class="px-5 py-2.5 rounded-xl bg-white border border-indigo-100 text-indigo-600 text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all active:scale-95 shadow-sm">
                                            Present
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.karyawan.absensi') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_karyawan" value="{{ $k->id_karyawan }}">
                                        <input type="hidden" name="status" value="tidak">
                                        <button class="px-5 py-2.5 rounded-xl bg-white border border-red-100 text-red-500 text-[10px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all active:scale-95 shadow-sm">
                                            Absent
                                        </button>
                                    </form>
                                </div>

                                {{-- Meta Actions --}}
                                <div class="flex items-center gap-3 ml-10">
                                    <a href="{{ route('admin.karyawan.info', $k->id_karyawan) }}" class="w-12 h-12 rounded-2xl bg-gray-50 text-gray-400 hover:bg-gray-900 hover:text-white transition-all duration-300 flex items-center justify-center shadow-sm">
                                        <span class="material-symbols-rounded">analytics</span>
                                    </a>
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
                            <span class="material-symbols-rounded text-8xl text-gray-100">person_off</span>
                            <h3 class="text-xl font-black text-gray-300 uppercase tracking-[0.3em] mt-6">No Workforce Found</h3>
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
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .swipe-row { cursor: grab; user-select: none; }
</style>

<script>
    // Logic Swipe yang sama agar konsisten Bree
    document.querySelectorAll('.swipe-row').forEach(row => {
        let startX = 0; let currentX = 0; let isDragging = false;
        const maxSlide = -120; const snapPoint = -60;

        row.addEventListener('touchstart', e => { 
            if (e.target.closest('button') || e.target.closest('a')) return;
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

        // PC Support
        row.addEventListener('mousedown', e => {
            if (e.target.closest('button') || e.target.closest('a')) return;
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