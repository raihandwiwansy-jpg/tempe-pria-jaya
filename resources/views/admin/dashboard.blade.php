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
            <button class="flex items-center gap-4 bg-white p-2 pr-6 rounded-2xl shadow-sm border border-gray-100 hover:bg-gray-50 transition-all focus:outline-none">
                <div class="relative w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-green-100">
                    <span class="material-symbols-rounded">notifications</span>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 border-2 border-white rounded-full text-[10px] flex items-center justify-center font-bold animate-bounce">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </div>
                <div class="text-left">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pemberitahuan</p>
                    <p class="text-sm font-bold text-gray-700">
                        {{ auth()->user()->unreadNotifications->count() > 0 ? 'Ada Kabar Baru!' : 'Sistem Aman' }}
                    </p>
                </div>
            </button>

            {{-- Dropdown Notifikasi --}}
            <div class="absolute right-0 mt-3 w-80 bg-white rounded-[2rem] shadow-2xl border border-gray-100 overflow-hidden z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-right scale-95 group-hover:scale-100">
                <div class="p-6 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <h5 class="font-black text-gray-800 text-[10px] uppercase tracking-widest">Notifikasi Terbaru</h5>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <a href="{{ route('admin.notifications.markAllRead') }}" class="text-[9px] font-bold text-green-600 hover:text-green-700 transition-colors">Tandai Dibaca</a>
                    @endif
                </div>
                <div class="max-h-96 overflow-y-auto">
                    @forelse(auth()->user()->unreadNotifications as $notification)
                        <div class="p-5 border-b border-gray-50 hover:bg-green-50/50 transition-colors">
                            <div class="flex gap-4">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 shrink-0">
                                    <span class="material-symbols-rounded text-sm">info</span>
                                </div>
                                <div class="flex-grow">
                                    <p class="text-[11px] text-gray-600 font-bold leading-snug mb-1">
                                        {{ $notification->data['pesan'] ?? 'Ada aktivitas baru di sistem' }}
                                    </p>
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-tighter">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="material-symbols-rounded text-gray-300 text-3xl">notifications_off</span>
                            </div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Belum ada kabar baru</p>
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
                        <p class="font-black text-gray-900 text-[10px] tracking-[0.3em] uppercase mt-2">Pusat Jaya</p>
                    </div>
                </div>
            </div>
            <div class="flex-grow text-center lg:text-left text-white">
                <div class="inline-block px-4 py-1 rounded-full border border-green-500/30 bg-green-500/10 text-green-400 text-[10px] font-black uppercase tracking-[0.3em] mb-6">
                    Sistem Notifikasi Aktif
                </div>
                <h1 class="text-5xl lg:text-7xl font-black mb-6 tracking-tight">
                    Tempe <span class="text-green-500">Pria Jaya</span>
                </h1>
                <p class="text-gray-400 text-lg font-medium italic mb-10 max-w-xl">"Kualitas kedelai terbaik, dipantau langsung melalui sistem kendali admin."</p>
            </div>
        </div>
    </div>

    {{-- Log Aktivitas Sistem (History) --}}
    <div class="mb-12">
        <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-yellow-100">
                    <span class="material-symbols-rounded">history</span>
                </div>
                <div>
                    <h3 class="text-xl font-black text-gray-800 tracking-tight">Log Aktivitas</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Riwayat Pemberitahuan Sistem</p>
                </div>
            </div>
            
            {{-- Tombol Bersihkan Semua --}}
            @if(auth()->user()->notifications->count() > 0)
                <form action="{{ route('admin.notifications.clearAll') }}" method="POST" onsubmit="return confirm('Bree, yakin mau hapus SEMUA riwayat log?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-red-50 text-red-600 rounded-2xl hover:bg-red-100 transition-all group">
                        <span class="material-symbols-rounded text-sm group-hover:rotate-12 transition-transform">delete_sweep</span>
                        <span class="text-[10px] font-black uppercase tracking-widest">Bersihkan Semua Log</span>
                    </button>
                </form>
            @endif
        </div>
        
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-xl overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Waktu Kejadian</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Keterangan Aktivitas</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status Log</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse(auth()->user()->notifications()->take(20)->get() as $notification)
                        <tr class="hover:bg-gray-50/30 transition-colors group">
                            <td class="p-6">
                                <span class="text-[11px] font-black text-gray-500 uppercase">{{ $notification->created_at->translatedFormat('d M Y') }}</span>
                                <span class="block text-[10px] text-gray-400 font-medium">{{ $notification->created_at->format('H:i:s') }}</span>
                            </td>
                            <td class="p-6">
                                <p class="text-sm font-bold text-gray-700 leading-relaxed">{{ $notification->data['pesan'] ?? 'Aktivitas terekam' }}</p>
                            </td>
                            <td class="p-6">
                                @if($notification->read_at)
                                    <div class="flex items-center gap-2 text-gray-400">
                                        <span class="material-symbols-rounded text-sm text-blue-400">check_circle</span>
                                        <span class="text-[9px] font-black uppercase">Telah Dilihat</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 text-green-600">
                                        <span class="material-symbols-rounded text-sm animate-pulse">new_releases</span>
                                        <span class="text-[9px] font-black uppercase">Belum Dibaca</span>
                                    </div>
                                @endif
                            </td>
                            <td class="p-6 text-center">
                                <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Hapus log ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100">
                                        <span class="material-symbols-rounded text-lg">close</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-20 text-center">
                                <span class="material-symbols-rounded text-gray-200 text-6xl block mb-4">analytics</span>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Belum ada aktivitas terekam di database</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection