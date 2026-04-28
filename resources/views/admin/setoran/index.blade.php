@extends('layouts.admin')

@section('title', 'Finance Verification Center')

@section('content')
<div class="container mx-auto pb-32 px-4 md:px-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-8">
        <div class="relative">
            <div class="absolute -left-6 top-0 bottom-0 w-2 bg-gradient-to-b from-emerald-600 via-green-400 to-transparent rounded-full shadow-[0_0_15px_rgba(16,185,129,0.4)]"></div>
            <h2 class="text-5xl font-[1000] text-gray-900 tracking-tight ml-2 leading-none ">Setoran <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-green-500">Verify</span></h2>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.4em] ml-2 mt-3 flex items-center gap-2">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
                Audit & Financial Confirmation
            </p>
        </div>
        
        <div class="flex items-center gap-4 bg-white/50 backdrop-blur-md p-2 rounded-[2rem] border border-gray-100 shadow-xl">
            <div class="px-6 py-3 text-right">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1 text-left">Waiting Action</p>
                <p class="text-2xl font-[1000] text-emerald-600 leading-none tracking-tighter">{{ $setorans->where('status', 'pending')->count() }} <span class="text-xs font-bold text-gray-300 uppercase">Files</span></p>
            </div>
            <div class="h-10 w-[1px] bg-gray-100"></div>
            <div class="pr-6 pl-2">
                <span class="material-symbols-rounded text-3xl text-gray-200">account_balance_wallet</span>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="bg-white/80 backdrop-blur-2xl rounded-[3.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.03)] border border-white overflow-hidden">
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Partner</th>
                        <th class="px-8 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Info</th>
                        <th class="px-8 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Amount</th>
                        <th class="px-8 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-center">Proof</th>
                        <th class="px-8 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-center">Status</th>
                        <th class="px-8 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @forelse($setorans as $s)
                    @php $cleanStatus = strtolower(trim($s->status)); @endphp
                    <tr class="group bg-white hover:bg-emerald-50/10 transition-all duration-300">
                        {{-- 1. Reseller --}}
                        <td class="px-8 py-8">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gray-900 flex items-center justify-center text-white font-black text-lg shadow-lg italic group-hover:scale-110 transition-transform">
                                    {{ strtoupper(substr($s->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-800 text-sm italic">{{ $s->user->name ?? 'Unknown' }}</h4>
                                    <span class="text-[8px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-50 px-2 py-0.5 rounded">Partner</span>
                                </div>
                            </div>
                        </td>

                        {{-- 2. Info --}}
                        <td class="px-8 py-8">
                            <span class="text-xs font-black text-gray-700 flex items-center gap-1 italic">
                                {{ \Carbon\Carbon::parse($s->tanggal_setoran)->format('d M y') }}
                            </span>
                            <p class="text-[9px] text-gray-400 italic">ID: #{{ $s->id }}</p>
                        </td>

                        {{-- 3. Amount --}}
                        <td class="px-8 py-8">
                            <span class="text-lg font-[1000] text-emerald-600 italic tracking-tighter">
                                Rp{{ number_format($s->jumlah_setoran, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- 4. Proof --}}
                        <td class="px-8 py-8 text-center">
                            @if($s->bukti_pembayaran)
                            <button onclick="openPreview('{{ asset('storage/' . $s->bukti_pembayaran) }}', '{{ $s->user->name }}')" 
                                class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all inline-flex items-center justify-center">
                                <span class="material-symbols-rounded text-xl">image</span>
                            </button>
                            @else
                            <span class="material-symbols-rounded text-gray-200">hide_image</span>
                            @endif
                        </td>

                        {{-- 5. Status --}}
                        <td class="px-8 py-8 text-center">
                            @php
                                $badge = [
                                    'pending' => 'from-amber-400 to-orange-500',
                                    'disetujui' => 'from-emerald-500 to-teal-600',
                                    'ditolak' => 'from-red-500 to-rose-600'
                                ];
                            @endphp
                            <span class="bg-gradient-to-br {{ $badge[$cleanStatus] ?? 'from-gray-400 to-gray-500' }} text-white px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest shadow-md italic">
                                {{ $s->status }}
                            </span>
                        </td>

                        {{-- 6. Action (Fixed & Visible) --}}
                        <td class="px-8 py-8">
                            <div class="flex justify-end items-center gap-2">
                                @if($cleanStatus == 'pending')
                                    <form action="{{ route('admin.setoran.update', $s->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="disetujui">
                                        <button type="submit" class="p-2.5 rounded-xl bg-emerald-600 text-white hover:scale-110 active:scale-95 transition-all shadow-lg">
                                            <span class="material-symbols-rounded text-lg">check</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.setoran.update', $s->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="ditolak">
                                        <button type="submit" class="p-2.5 rounded-xl bg-white border border-red-100 text-red-500 hover:bg-red-500 hover:text-white hover:scale-110 active:scale-95 transition-all shadow-sm">
                                            <span class="material-symbols-rounded text-lg">block</span>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.setoran.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus record?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2.5 text-gray-300 hover:text-red-500 transition-colors">
                                            <span class="material-symbols-rounded text-lg">delete</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-32 text-center text-gray-300 font-black italic uppercase tracking-[0.5em]">No Data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Preview --}}
<div id="modalPreview" class="fixed inset-0 z-[100] hidden bg-gray-950/60 backdrop-blur-xl flex items-center justify-center p-6">
    <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-lg overflow-hidden border border-white/20 animate-zoom-in">
        <div class="p-8 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-[1000] text-gray-900 text-xl italic" id="modalUser">Receipt</h3>
            <button onclick="closePreview()" class="w-10 h-10 rounded-xl bg-white text-gray-400 hover:text-red-500 transition-all flex items-center justify-center shadow-sm">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <div class="p-8 flex justify-center bg-white">
            <img id="imgSource" src="" class="max-h-[50vh] rounded-2xl shadow-lg object-contain border">
        </div>
        <div class="p-8 bg-gray-50">
            <a id="downloadBtn" href="" download class="w-full bg-gray-900 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-emerald-600 transition-all">
                <span class="material-symbols-rounded text-sm">download</span> Download
            </a>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;700;800;1000&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfc; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    @keyframes zoom-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-zoom-in { animation: zoom-in 0.3s ease-out; }
</style>

<script>
    function openPreview(src, userName) {
        const modal = document.getElementById('modalPreview');
        document.getElementById('modalUser').innerText = userName + "'s Receipt";
        document.getElementById('imgSource').src = src;
        document.getElementById('downloadBtn').href = src;
        modal.classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    function closePreview() {
        const modal = document.getElementById('modalPreview');
        modal.classList.replace('flex', 'hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection