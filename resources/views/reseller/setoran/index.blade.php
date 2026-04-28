@extends('layouts.reseller')

@section('title', 'Financial Deposit History')

@section('content')
<div class="container mx-auto pb-32 px-4 md:px-8">
    {{-- Top Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-8">
        <div class="relative">
            <div class="absolute -left-6 top-0 bottom-0 w-2 bg-gradient-to-b from-green-600 to-emerald-400 rounded-full shadow-[0_0_15px_rgba(16,185,129,0.4)]"></div>
            <h2 class="text-5xl font-[1000] text-gray-900 tracking-tight ml-2 leading-none ">Setoran <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-500">Saya</span></h2>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.4em] ml-2 mt-3 flex items-center gap-2">
                <span class="material-symbols-rounded text-xs text-green-500">history_edu</span>
                Financial Verification Records
            </p>
        </div>
        
        <a href="{{ route('reseller.setoran.create') }}" class="group bg-gray-900 hover:bg-green-600 text-white px-10 py-5 rounded-[2.5rem] shadow-2xl transition-all duration-500 flex items-center gap-4 font-black text-xs uppercase tracking-widest active:scale-95 border-b-4 border-black/20">
            <span class="material-symbols-rounded text-2xl group-hover:rotate-180 transition-transform duration-700">add_card</span>
            Lapor Setoran
        </a>
    </div>

    {{-- Deposit Table --}}
    <div class="bg-white/70 backdrop-blur-2xl rounded-[3.5rem] shadow-[0_40px_80px_rgba(0,0,0,0.03)] border border-white overflow-hidden">
        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em]">Date & Reference</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-right">Amount (IDR)</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-center">Verification Status</th>
                        <th class="px-10 py-8 text-[11px] font-black uppercase text-gray-400 tracking-[0.3em] text-right">Attachment</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @forelse($setorans as $s)
                    <tr class="group relative bg-white hover:bg-green-50/20 transition-all duration-500">
                        <td colspan="4" class="p-0 relative">
                            {{-- BACK LAYER: Danger Action --}}
                            <div class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-red-600 to-red-500 flex items-center justify-center">
                                @if($s->status == 'pending')
                                <form action="{{ route('reseller.setoran.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus laporan setoran ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="flex flex-col items-center text-white gap-1 active:scale-90 transition-transform">
                                        <span class="material-symbols-rounded text-3xl">delete_forever</span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter text-white/80">Remove</span>
                                    </button>
                                </form>
                                @else
                                <span class="text-[9px] font-black text-white/50 uppercase tracking-widest text-center px-4 italic">Locked by System</span>
                                @endif
                            </div>

                            {{-- FRONT LAYER: Content --}}
                            <div class="relative bg-white flex items-center py-8 px-10 transition-transform duration-500 ease-[cubic-bezier(0.2,0.8,0.2,1)] swipe-row">
                                
                                {{-- Date Info --}}
                                <div class="flex-1">
                                    <div class="flex items-center gap-5">
                                        <div class="w-14 h-14 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col items-center justify-center text-gray-400 group-hover:bg-green-50 group-hover:text-green-600 transition-colors">
                                            <span class="text-[10px] font-black uppercase tracking-tighter">{{ \Carbon\Carbon::parse($s->tanggal_setoran)->format('M') }}</span>
                                            <span class="text-xl font-black leading-none">{{ \Carbon\Carbon::parse($s->tanggal_setoran)->format('d') }}</span>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest leading-none mb-1">Ref: #STR-{{ $s->id }}</p>
                                            <h4 class="font-black text-gray-800 tracking-tight italic">{{ \Carbon\Carbon::parse($s->tanggal_setoran)->format('Y') }}</h4>
                                        </div>
                                    </div>
                                </div>

                                {{-- Amount --}}
                                <div class="flex-1 text-right">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-2">Total Setoran</p>
                                    <p class="text-2xl font-[1000] text-green-600 tracking-tighter leading-none italic">
                                        Rp {{ number_format($s->jumlah_setoran, 0, ',', '.') }}
                                    </p>
                                </div>

                                {{-- Status Indicator --}}
                                <div class="flex-1 flex justify-center">
                                    @php
                                        $colors = [
                                            'pending' => 'amber',
                                            'disetujui' => 'green',
                                            'ditolak' => 'red'
                                        ];
                                        $c = $colors[$s->status] ?? 'gray';
                                    @endphp
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="px-6 py-2 rounded-full text-[10px] font-[1000] uppercase tracking-[0.2em] bg-{{ $c }}-50 text-{{ $c }}-600 border border-{{ $c }}-100 shadow-sm">
                                            {{ $s->status }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Attachment Actions --}}
                                <div class="flex items-center gap-4 ml-10">
                                    @if($s->bukti_pembayaran)
                                    <a href="{{ asset('storage/' . $s->bukti_pembayaran) }}" target="_blank" class="w-12 h-12 rounded-2xl bg-gray-50 text-gray-400 hover:bg-gray-900 hover:text-white transition-all duration-300 flex items-center justify-center shadow-sm">
                                        <span class="material-symbols-rounded">visibility</span>
                                    </a>
                                    @endif
                                    
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
                            <span class="material-symbols-rounded text-8xl text-gray-100 italic">account_balance</span>
                            <h3 class="text-xl font-black text-gray-300 uppercase tracking-[0.3em] mt-6">No Deposit Records</h3>
                            <p class="text-[10px] text-gray-400 font-bold mt-2 uppercase tracking-widest">Database empty</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- System Notes --}}
    <div class="mt-12 max-w-2xl bg-blue-50/50 border border-blue-100 p-8 rounded-[2.5rem] flex items-start gap-6 backdrop-blur-sm">
        <div class="w-12 h-12 bg-blue-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
            <span class="material-symbols-rounded">verified_user</span>
        </div>
        <div>
            <h4 class="text-sm font-black text-blue-900 uppercase tracking-widest">Policy Note</h4>
            <p class="text-xs text-blue-700 mt-2 leading-relaxed font-medium">
                Setoran yang telah berstatus <span class="font-black italic">"disetujui"</span> atau <span class="font-black italic">"ditolak"</span> telah dikunci oleh sistem dan tidak dapat diubah/dihapus untuk menjaga validitas laporan keuangan perusahaan.
            </p>
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
    // Swipe Handler Logic (Universal)
    document.querySelectorAll('.swipe-row').forEach(row => {
        let startX = 0, currentX = 0, isDragging = false;
        const maxSlide = -120, snapPoint = -60;

        row.addEventListener('touchstart', e => { 
            if (e.target.closest('a') || e.target.closest('button')) return;
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

        row.addEventListener('mousedown', e => {
            if (e.target.closest('a') || e.target.closest('button')) return;
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