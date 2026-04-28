@extends('layouts.reseller')

@section('title', 'Exclusive Product Gallery')

@section('content')
<div class="container mx-auto pb-32 px-4 md:px-8">
    {{-- Header & Search Section --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-16 gap-8">
        <div class="relative">
            <div class="absolute -left-6 top-0 bottom-0 w-2 bg-gradient-to-b from-green-600 to-emerald-400 rounded-full shadow-[0_0_15px_rgba(16,185,129,0.4)]"></div>
            <h2 class="text-5xl font-[1000] text-gray-900 tracking-tight ml-2 leading-none">Market <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-500 ">Gallery</span></h2>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.4em] ml-2 mt-3 flex items-center gap-2">
                <span class="material-symbols-rounded text-xs text-green-500">verified</span>
                Authorized Central Inventory
            </p>
        </div>

        <div class="w-full lg:w-[450px] relative group">
            <div class="absolute inset-y-0 left-6 flex items-center text-gray-400 group-focus-within:text-green-600 transition-colors">
                <span class="material-symbols-rounded text-2xl">search_insights</span>
            </div>
            <input type="text" id="searchInput" 
                class="w-full pl-16 pr-8 py-5 bg-white border-2 border-transparent rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] focus:border-green-500/20 focus:ring-0 text-sm font-bold transition-all placeholder:text-gray-300 placeholder:font-black placeholder:uppercase placeholder:tracking-widest" 
                placeholder="Find Product Name or SKU...">
        </div>
    </div>

    {{-- Product Grid --}}
    <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-10">
        @forelse($katalog as $item)
        <div class="product-card group bg-white rounded-[3.5rem] border border-white shadow-[0_30px_60px_rgba(0,0,0,0.02)] overflow-hidden hover:shadow-[0_40px_90px_rgba(0,0,0,0.08)] transition-all duration-700 relative"
             data-name="{{ strtolower($item->nama_display) }}" 
             data-code="{{ strtolower($item->barang->kode_barang) }}">
            
            {{-- Image Zone --}}
            <div class="h-72 bg-gray-50 relative overflow-hidden cursor-zoom-in" onclick="openLightbox('{{ $item->foto ? asset('storage/' . $item->foto) : '' }}', '{{ $item->nama_display }}')">
                @if($item->foto)
                    <img src="{{ asset('storage/' . $item->foto) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000 ease-out">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-500 flex items-center justify-center">
                        <span class="material-symbols-rounded text-white opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all text-4xl">zoom_in</span>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-full text-gray-200">
                        <span class="material-symbols-rounded text-7xl">image_not_supported</span>
                    </div>
                @endif

                {{-- Stock Badge --}}
                <div class="absolute top-6 left-6">
                    @if($item->barang->stok_pusat > 10)
                        <div class="bg-white/90 backdrop-blur-md px-4 py-2 rounded-2xl text-[10px] font-black text-gray-900 uppercase border border-white/50 shadow-xl">
                            <span class="text-green-500 mr-1">●</span> Available: {{ $item->barang->stok_pusat }}
                        </div>
                    @elseif($item->barang->stok_pusat > 0)
                        <div class="bg-amber-500 px-4 py-2 rounded-2xl text-[10px] font-black text-white uppercase shadow-lg shadow-amber-200">
                            Low Stock: {{ $item->barang->stok_pusat }}
                        </div>
                    @else
                        <div class="bg-red-500 px-4 py-2 rounded-2xl text-[10px] font-black text-white uppercase shadow-lg shadow-red-200">
                            Sold Out
                        </div>
                    @endif
                </div>
            </div>

            {{-- Content Zone --}}
            <div class="p-8">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-[9px] font-[1000] text-green-600 uppercase tracking-[0.2em] bg-green-50 px-3 py-1 rounded-full">
                        {{ $item->kategori }}
                    </span>
                    <span class="text-[9px] font-bold text-gray-300 uppercase tracking-tighter">SKU: {{ $item->barang->kode_barang }}</span>
                </div>

                <h3 class="font-black text-gray-900 text-xl mb-6 line-clamp-1 group-hover:text-green-600 transition-colors italic">
                    {{ $item->nama_display }}
                </h3>

                <div class="flex items-center justify-between gap-4 mb-8">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Reseller Price</span>
                        <span class="text-2xl font-[1000] text-gray-900 tracking-tighter">
                            <span class="text-xs font-bold text-green-600 mr-0.5">Rp</span>{{ number_format($item->harga_jual_reseller, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                @if($item->barang->stok_pusat > 0)
                    <a href="{{ route('reseller.pesanan.create', ['barang_id' => $item->barang_id]) }}" 
                       class="w-full bg-gray-900 hover:bg-green-600 text-white py-5 rounded-[2rem] font-black text-[10px] uppercase tracking-[0.2em] flex items-center justify-center gap-3 transition-all duration-500 shadow-xl shadow-gray-200 active:scale-95 group/btn">
                        <span class="material-symbols-rounded text-lg group-hover/btn:translate-x-1 transition-transform">potted_plant</span>
                        Order Unit
                    </a>
                @else
                    <button disabled class="w-full bg-gray-100 text-gray-300 py-5 rounded-[2rem] font-black text-[10px] uppercase tracking-[0.2em] flex items-center justify-center gap-3 cursor-not-allowed">
                        <span class="material-symbols-rounded text-lg">block</span>
                        Out of Stock
                    </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full py-40 text-center">
            <span class="material-symbols-rounded text-9xl text-gray-100 italic">inventory_2</span>
            <h3 class="text-2xl font-[1000] text-gray-300 uppercase tracking-[0.3em] mt-8">Gallery Empty</h3>
        </div>
        @endforelse
    </div>

    {{-- Not Found State --}}
    <div id="notFound" class="hidden col-span-full py-40 text-center animate-pulse">
        <span class="material-symbols-rounded text-8xl text-gray-200">manage_search</span>
        <h3 class="font-black text-gray-400 uppercase tracking-[0.4em] mt-6">Product Not Found</h3>
    </div>
</div>

{{-- LIGHTBOX MODAL --}}
<div id="imageLightbox" class="fixed inset-0 z-[999] hidden bg-gray-950/95 backdrop-blur-xl flex items-center justify-center p-4 md:p-12 transition-all duration-500 opacity-0">
    <button onclick="closeLightbox()" class="absolute top-8 right-8 text-white/50 hover:text-white transition-colors">
        <span class="material-symbols-rounded text-5xl">close</span>
    </button>
    
    <div class="max-w-5xl w-full h-full flex flex-col items-center justify-center gap-6">
        <img id="lightboxImg" src="" class="max-w-full max-h-[80vh] object-contain rounded-3xl shadow-2xl border border-white/10">
        <h3 id="lightboxTitle" class="text-white text-2xl font-black italic tracking-tight uppercase"></h3>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800;1000&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfc; }

    .product-card { animation: cardReveal 0.8s cubic-bezier(0.2, 0.8, 0.2, 1); }
    @keyframes cardReveal {
        from { opacity: 0; transform: translateY(30px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    // Search Logic
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const productCards = document.querySelectorAll('.product-card');
        const notFound = document.getElementById('notFound');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let hasResults = false;

            productCards.forEach(card => {
                const name = card.getAttribute('data-name');
                const code = card.getAttribute('data-code');

                if (name.includes(searchTerm) || code.includes(searchTerm)) {
                    card.style.display = 'block';
                    hasResults = true;
                } else {
                    card.style.display = 'none';
                }
            });

            notFound.style.display = hasResults ? 'none' : 'block';
        });
    });

    // Lightbox Logic
    function openLightbox(src, title) {
        if(!src) return;
        const lightbox = document.getElementById('imageLightbox');
        const img = document.getElementById('lightboxImg');
        const titleEl = document.getElementById('lightboxTitle');

        img.src = src;
        titleEl.innerText = title;
        lightbox.classList.remove('hidden');
        setTimeout(() => {
            lightbox.classList.add('flex');
            lightbox.style.opacity = '1';
        }, 10);
    }

    function closeLightbox() {
        const lightbox = document.getElementById('imageLightbox');
        lightbox.style.opacity = '0';
        setTimeout(() => {
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
        }, 500);
    }
</script>
@endsection