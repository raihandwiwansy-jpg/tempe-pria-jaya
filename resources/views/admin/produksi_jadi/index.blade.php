@extends('layouts.admin')

@section('title', 'Finished Goods Analytics')

@section('content')
<div class="container mx-auto pb-32 px-4 sm:px-6 lg:px-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
        <div class="relative group">
            <div class="absolute -left-6 top-0 bottom-0 w-2 bg-gradient-to-b from-emerald-600 to-teal-400 rounded-full shadow-[0_0_15px_rgba(16,185,129,0.4)]"></div>
            <h2 class="text-5xl font-[1000] text-gray-900 tracking-tight ml-2 leading-none">Produksi <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Jadi</span></h2>
            <div class="flex items-center gap-3 ml-2 mt-3">
                <span class="flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.3em]">Stock Inventory Intelligence</p>
            </div>
        </div>
        
        <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="w-full md:w-auto bg-gray-900 hover:bg-emerald-600 text-white px-10 py-5 rounded-[2rem] shadow-[0_20px_40px_rgba(0,0,0,0.1)] transition-all duration-500 flex items-center justify-center gap-4 font-black text-xs uppercase tracking-widest active:scale-95 group border-b-4 border-black/20">
            <span class="material-symbols-rounded text-2xl group-hover:rotate-90 transition-transform duration-500">inventory_2</span>
            Input Barang Jadi
        </button>
    </div>

    {{-- ANALYTICS CARDS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        @php
            $stats = [
                ['label' => 'Hari Ini', 'data' => $statHari, 'color' => 'emerald', 'icon' => 'today'],
                ['label' => 'Minggu Ini', 'data' => $statMinggu, 'color' => 'blue', 'icon' => 'date_range'],
                ['label' => 'Bulan Ini', 'data' => $statBulan, 'color' => 'indigo', 'icon' => 'calendar_month'],
                ['label' => 'Tahun Ini', 'data' => $statTahun, 'color' => 'rose', 'icon' => 'analytics']
            ];
        @endphp

        @foreach($stats as $s)
        <div class="bg-white/80 backdrop-blur-xl p-7 rounded-[3rem] border border-white shadow-sm hover:shadow-xl transition-all group overflow-hidden relative">
            <div class="relative z-10">
                <div class="flex justify-between items-center mb-6">
                    <p class="text-[10px] font-black text-{{ $s['color'] }}-500 uppercase tracking-[0.2em]">{{ $s['label'] }}</p>
                    <div class="w-8 h-8 rounded-full bg-{{ $s['color'] }}-50 flex items-center justify-center text-{{ $s['color'] }}-500">
                        <span class="material-symbols-rounded text-sm">{{ $s['icon'] }}</span>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-4xl font-[1000] text-gray-900 tracking-tighter">{{ number_format($s['data']['total']) }} <span class="text-xs font-bold text-gray-400 uppercase">Pcs</span></h3>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Total Produksi Tempe</p>
                </div>

                <div class="space-y-3 pt-4 border-t border-gray-50">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Size 9x30</span>
                        <span class="text-xs font-black text-gray-700">{{ number_format($s['data']['s9x30']) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Size 8x30</span>
                        <span class="text-xs font-black text-gray-700">{{ number_format($s['data']['s8x30']) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Size 10x35</span>
                        <span class="text-xs font-black text-gray-700">{{ number_format($s['data']['s10x35']) }}</span>
                    </div>
                </div>
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-{{ $s['color'] }}-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        </div>
        @endforeach
    </div>

    {{-- MAIN TABLE CARD --}}
    <div class="bg-white/70 backdrop-blur-2xl rounded-[3.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.03)] border border-white overflow-hidden">
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-10 py-10 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Timeline</th>
                        <th class="px-10 py-10 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-center">Batch Result</th>
                        <th class="px-10 py-10 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-center hidden md:table-cell">Variant Distribution</th>
                        <th class="px-10 py-10 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @forelse($data as $d)
                    <tr class="group bg-white hover:bg-emerald-50/20 transition-all duration-500">
                        {{-- Date --}}
                        <td class="px-10 py-8">
                            <div class="flex items-center gap-6">
                                <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex flex-col items-center justify-center border border-emerald-100 shadow-inner">
                                    <span class="text-sm font-black text-emerald-600 leading-none">{{ \Carbon\Carbon::parse($d->tanggal)->format('d') }}</span>
                                    <span class="text-[8px] font-black text-emerald-400 uppercase">{{ \Carbon\Carbon::parse($d->tanggal)->format('M') }}</span>
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-800 text-base tracking-tight">{{ \Carbon\Carbon::parse($d->tanggal)->format('l') }}</h4>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Year {{ \Carbon\Carbon::parse($d->tanggal)->format('Y') }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Total --}}
                        <td class="px-10 py-8 text-center">
                            <span class="text-3xl font-[1000] text-gray-900 tracking-tighter">{{ $d->total_produksi }}</span>
                            <span class="text-[9px] font-black text-emerald-500 block uppercase tracking-widest">Total Pcs</span>
                        </td>

                        {{-- Distribution --}}
                        <td class="px-10 py-8 hidden md:table-cell">
                            <div class="flex items-center justify-center gap-3">
                                <div class="px-3 py-2 bg-gray-50 rounded-xl border border-gray-100 text-center min-w-[70px]">
                                    <p class="text-[8px] font-black text-gray-400 uppercase mb-0.5">9x30</p>
                                    <p class="text-xs font-black text-gray-700">{{ $d->size_9x30 }}</p>
                                </div>
                                <div class="px-3 py-2 bg-gray-50 rounded-xl border border-gray-100 text-center min-w-[70px]">
                                    <p class="text-[8px] font-black text-gray-400 uppercase mb-0.5">8x30</p>
                                    <p class="text-xs font-black text-gray-700">{{ $d->size_8x30 }}</p>
                                </div>
                                <div class="px-3 py-2 bg-gray-50 rounded-xl border border-gray-100 text-center min-w-[70px]">
                                    <p class="text-[8px] font-black text-gray-400 uppercase mb-0.5">10x35</p>
                                    <p class="text-xs font-black text-gray-700">{{ $d->size_10x35 }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Action --}}
                        <td class="px-10 py-8 text-right">
                            <form action="{{ route('admin.produksi-jadi.destroy', $d->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-10 h-10 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all duration-300">
                                    <span class="material-symbols-rounded text-xl">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-32 text-center">
                            <div class="flex flex-col items-center">
                                <span class="material-symbols-rounded text-6xl text-gray-200 mb-4 tracking-widest">inventory</span>
                                <h3 class="text-xl font-black text-gray-300 uppercase tracking-[0.3em]">No Finished Goods</h3>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH (FUTURISTIC) --}}
<div id="modalTambah" class="fixed inset-0 z-[150] hidden">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-4">
        <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-white animate-bounce-in">
            <div class="bg-emerald-600 p-8 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-2xl font-[1000] tracking-tighter">Input Batch Produksi</h3>
                    <p class="text-emerald-100 text-[10px] font-bold uppercase tracking-widest">Catat tempe siap edar hari ini</p>
                </div>
                <span class="material-symbols-rounded absolute -right-4 -bottom-4 text-8xl text-emerald-500/30">task_alt</span>
            </div>

            <form action="{{ route('admin.produksi-jadi.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Tanggal Produksi</label>
                    <input type="date" name="tanggal" required class="w-full mt-2 bg-gray-50 border-none rounded-2xl p-4 font-bold text-gray-700 focus:ring-2 focus:ring-emerald-500 transition-all" value="{{ date('Y-m-d') }}">
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-tighter ml-1 text-center block">9x30 (Pcs)</label>
                        <input type="number" name="size_9x30" value="0" min="0" class="w-full mt-2 bg-gray-50 border-none rounded-2xl p-4 font-bold text-gray-700 text-center focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-tighter ml-1 text-center block">8x30 (Pcs)</label>
                        <input type="number" name="size_8x30" value="0" min="0" class="w-full mt-2 bg-gray-50 border-none rounded-2xl p-4 font-bold text-gray-700 text-center focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-tighter ml-1 text-center block">10x35 (Pcs)</label>
                        <input type="number" name="size_10x35" value="0" min="0" class="w-full mt-2 bg-gray-50 border-none rounded-2xl p-4 font-bold text-gray-700 text-center focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                <div class="pt-4 flex gap-4">
                    <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="flex-1 py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">Cancel</button>
                    <button type="submit" class="flex-[2] bg-emerald-600 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all active:scale-95">Save Batch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;700;800;1000&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    @keyframes bounceIn {
        0% { opacity: 0; transform: scale(0.3) translateY(-100px); }
        50% { opacity: 1; transform: scale(1.05) translateY(10px); }
        100% { transform: scale(1) translateY(0); }
    }
    .animate-bounce-in { animation: bounceIn 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
</style>
@endsection