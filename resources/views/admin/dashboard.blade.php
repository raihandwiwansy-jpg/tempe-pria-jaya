@extends('layouts.admin')

@section('title', 'Admin Control Center - Tempe Pria Jaya')

@section('content')
<div class="container mx-auto px-4 pb-12">
    
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Admin Control Center</h2>
            <p class="text-gray-500 font-medium uppercase tracking-[0.2em] text-[10px] mt-1">Sistem Manajemen Terintegrasi Pria Jaya</p>
        </div>
        
        {{-- Notification Trigger (Dropdown) --}}
        <div class="relative group">
            <button class="flex items-center gap-4 bg-white p-2 pr-6 rounded-2xl shadow-sm border border-gray-100 hover:bg-gray-50 transition-all">
                <div class="relative w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-green-100">
                    <span class="material-symbols-rounded">notifications</span>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 border-2 border-white rounded-full text-[10px] flex items-center justify-center font-bold">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </div>
                <div class="text-left">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pemberitahuan</p>
                    <p class="text-sm font-bold text-gray-700">Notifikasi Baru</p>
                </div>
            </button>

            {{-- Dropdown Notifikasi --}}
            <div class="absolute right-0 mt-3 w-80 bg-white rounded-[2rem] shadow-2xl border border-gray-100 overflow-hidden z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                <div class="p-6 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <h5 class="font-black text-gray-800 text-xs uppercase tracking-widest">Notifikasi Terbaru</h5>
                    <a href="{{ route('admin.notifications.markAllRead') }}" class="text-[9px] font-bold text-green-600 hover:underline">Tandai Dibaca Semua</a>
                </div>
                <div class="max-h-96 overflow-y-auto">
                    @forelse(auth()->user()->unreadNotifications as $notification)
                        <div class="p-5 border-b border-gray-50 hover:bg-green-50/30 transition-colors">
                            <div class="flex gap-4">
                                <span class="material-symbols-rounded text-green-600">database</span>
                                <div>
                                    <p class="text-xs text-gray-600 font-medium leading-relaxed">{{ $notification->data['pesan'] }}</p>
                                    <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center">
                            <span class="material-symbols-rounded text-gray-200 text-5xl">notifications_off</span>
                            <p class="text-xs text-gray-400 font-bold mt-2 uppercase tracking-widest">Tidak ada notifikasi baru</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Banner Utama --}}
    <div class="relative bg-gray-900 rounded-[3rem] shadow-2xl overflow-hidden mb-12">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-green-600 skew-x-[-15deg] translate-x-20 opacity-20"></div>
        <div class="relative z-10 flex flex-col lg:flex-row items-center p-12 lg:p-20 gap-12">
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-green-500 to-yellow-500 rounded-[2.5rem] blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                <div class="relative w-56 h-56 bg-white rounded-[2.5rem] flex items-center justify-center overflow-hidden">
                    <div class="text-center">
                        <span class="material-symbols-rounded text-8xl text-green-700">warehouse</span>
                        <p class="font-black text-gray-900 text-xs tracking-[0.3em] uppercase mt-2">Pusat</p>
                    </div>
                </div>
            </div>
            <div class="flex-grow text-center lg:text-left text-white">
                <h1 class="text-5xl lg:text-7xl font-black mb-6 tracking-tight">
                    Tempe <span class="text-green-500">Pria Jaya</span>
                </h1>
                <p class="text-gray-400 text-lg font-medium italic mb-10">"Menjaga kualitas dari setiap butir kedelai."</p>
            </div>
        </div>
    </div>

    {{-- History Notifications Section (Section Baru) --}}
    <div class="mb-12">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center text-white shadow-lg shadow-yellow-100">
                <span class="material-symbols-rounded text-sm">history</span>
            </div>
            <h3 class="text-xl font-black text-gray-800 tracking-tight">Log Aktivitas Sistem</h3>
        </div>
        
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Waktu</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Aktivitas</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-6 text-xs font-bold text-gray-500 uppercase">{{ $notification->created_at->format('d M Y, H:i') }}</td>
                            <td class="p-6 text-sm font-bold text-gray-700">{{ $notification->data['pesan'] }}</td>
                            <td class="p-6">
                                @if($notification->read_at)
                                    <span class="px-3 py-1 bg-gray-100 text-gray-400 text-[9px] font-black uppercase rounded-full">Selesai</span>
                                @else
                                    <span class="px-3 py-1 bg-green-100 text-green-600 text-[9px] font-black uppercase rounded-full tracking-wider">Baru</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-10 text-center text-gray-400 text-xs font-bold uppercase tracking-widest">Belum ada riwayat aktivitas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection