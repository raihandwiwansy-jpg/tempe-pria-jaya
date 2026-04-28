@extends('layouts.admin')

@section('title', 'Financial Intelligence')

@section('content')
<div class="container mx-auto pb-32 px-4 md:px-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-8">
        <div class="relative">
            <div class="absolute -left-6 top-0 bottom-0 w-2 bg-gradient-to-b from-green-600 to-emerald-400 rounded-full shadow-[0_0_15px_rgba(16,185,129,0.4)]"></div>
            <h2 class="text-5xl font-[1000] text-gray-900 tracking-tight ml-2 leading-none">Keuangan <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-500">Master</span></h2>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.4em] ml-2 mt-3 flex items-center gap-2">
                <span class="material-symbols-rounded text-xs text-green-500">verified_user</span>
                Immutable Ledger & Cashflow Records
            </p>
        </div>
        
        <div class="flex flex-wrap items-center gap-4">
            {{-- Summary Card --}}
            <div class="bg-white/80 backdrop-blur-md px-8 py-5 rounded-[2rem] border border-white shadow-[0_20px_50px_rgba(0,0,0,0.04)] flex items-center gap-6">
                <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600">
                    <span class="material-symbols-rounded text-3xl">account_balance_wallet</span>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1 text-right">Total Net Balance</p>
                    <p class="text-2xl font-[1000] text-gray-900 tracking-tighter leading-none">Rp {{ number_format($total_kas, 0, ',', '.') }}</p>
                </div>
            </div>

            <a href="{{ route('admin.keuangan.create') }}" class="group bg-green-600 hover:bg-green-700 text-white p-5 rounded-2xl shadow-xl shadow-green-100 transition-all flex items-center gap-3 font-black text-xs uppercase tracking-widest active:scale-95">
                <span class="material-symbols-rounded group-hover:rotate-90 transition-transform">add_circle</span>
                New Record
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mx-auto mb-8 max-w-2xl bg-gray-900 text-white px-8 py-4 rounded-[2rem] shadow-2xl flex items-center gap-4 animate-bounce-subtle">
        <span class="material-symbols-rounded text-green-400">check_circle</span>
        <span class="text-xs font-bold uppercase tracking-widest">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Main Ledger Table --}}
    <div class="bg-white/70 backdrop-blur-2xl rounded-[3.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.03)] border border-white overflow-hidden">
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Transaction Timestamp</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Category & Classification</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Description Log</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-right">Settlement Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($data as $item)
                    <tr class="group hover:bg-white transition-all duration-300">
                        <td class="px-10 py-8">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 flex flex-col items-center justify-center text-gray-500 font-black leading-none">
                                    <span class="text-[10px]">{{ date('M', strtotime($item->tanggal)) }}</span>
                                    <span class="text-sm">{{ date('d', strtotime($item->tanggal)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-800 leading-none mb-1">{{ date('Y', strtotime($item->tanggal)) }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $item->created_at->format('H:i') }} Server Time</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-10 py-8">
                            <div class="flex flex-col gap-2">
                                @if($item->tipe == 'masuk')
                                    <div class="flex items-center gap-2 text-green-600">
                                        <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                                        <span class="text-[10px] font-black uppercase tracking-widest bg-green-50 px-3 py-1 rounded-lg border border-green-100/50">Credit In</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 text-red-500">
                                        <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                        <span class="text-[10px] font-black uppercase tracking-widest bg-red-50 px-3 py-1 rounded-lg border border-red-100/50">Debit Out</span>
                                    @endif
                                </div>
                                <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em] ml-4">
                                    Source: <span class="text-gray-600">{{ strtoupper($item->jenis) }}</span>
                                </p>
                            </div>
                        </td>
                        <td class="px-10 py-8">
                            <div class="max-w-xs group-hover:translate-x-2 transition-transform duration-300">
                                <p class="text-xs font-bold text-gray-600 leading-relaxed italic">
                                    "{{ $item->keterangan ?? 'System generated record' }}"
                                </p>
                            </div>
                        </td>
                        <td class="px-10 py-8 text-right">
                            <div class="inline-block relative">
                                <span class="text-xl font-[1000] tracking-tighter {{ $item->tipe == 'masuk' ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $item->tipe == 'masuk' ? '+' : '-' }} Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                </span>
                                <div class="absolute -bottom-1 right-0 w-full h-[2px] {{ $item->tipe == 'masuk' ? 'bg-green-100' : 'bg-red-100' }}"></div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-32 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-6">
                                    <span class="material-symbols-rounded text-5xl text-gray-200">receipt_long</span>
                                </div>
                                <h3 class="text-xl font-black text-gray-300 uppercase tracking-[0.3em]">No Transactions</h3>
                                <p class="text-[10px] text-gray-400 font-bold mt-2 uppercase tracking-widest">Database is currently silent</p>
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
    
    @keyframes bounce-subtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    .animate-bounce-subtle { animation: bounce-subtle 3s infinite ease-in-out; }
</style>
@endsection