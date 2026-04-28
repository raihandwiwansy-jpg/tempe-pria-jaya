@extends('layouts.admin')

@section('title', 'Publish Katalog Reseller')

@section('content')
<div class="container mx-auto p-6 pb-20">
    <div class="flex flex-col lg:flex-row gap-10">
        
        <div class="flex-1">
            <div class="border-l-4 border-green-600 pl-4 mb-8">
                <h2 class="text-2xl font-black text-gray-800">Publish ke Reseller</h2>
                <p class="text-sm text-gray-500">Pilih barang dari stok pusat untuk ditampilkan di katalog.</p>
            </div>

            @if(session('success'))
                <div class="bg-green-600 text-white px-6 py-4 rounded-2xl mb-6 shadow-lg shadow-green-100 flex items-center gap-3">
                    <span class="material-symbols-rounded">check_circle</span>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.barang_reseller.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 md:p-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Pilih Stok Pusat</label>
                            <select name="barang_id" id="barang_id" required 
                                class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 font-bold text-gray-700">
                                <option value="">-- Pilih Barang dari Gudang --</option>
                                @foreach($masterBarang as $mb)
                                    <option value="{{ $mb->id }}" data-nama="{{ $mb->nama_barang }}" data-stok="{{ $mb->stok_pusat }}">
                                        {{ $mb->kode_barang }} - {{ $mb->nama_barang }} (Tersedia: {{ $mb->stok_pusat }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nama di Katalog Reseller</label>
                            <input type="text" name="nama_display" id="nama_display" required 
                                class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 font-bold text-gray-700"
                                placeholder="Misal: Tempe Murni Super Premium">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Kategori</label>
                            <select name="kategori" id="kategori" required class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 font-bold text-gray-700">
                                <option value="Makanan">Makanan</option>
                                <option value="Minuman">Minuman</option>
                                <option value="Snack">Snack</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Harga untuk Reseller</label>
                            <input type="number" name="harga_jual_reseller" id="harga_input" required 
                                class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 font-bold text-gray-700"
                                placeholder="0">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Foto Produk</label>
                            <input type="file" name="foto" id="foto_input" 
                                class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl text-xs font-bold text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-green-600 file:text-white">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Deskripsi Singkat</label>
                            <textarea name="deskripsi" id="deskripsi_input" rows="3" 
                                class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 font-medium text-gray-700"
                                placeholder="Ceritakan sedikit tentang produk ini..."></textarea>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-8 bg-gray-900 hover:bg-green-600 text-white py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-xl active:scale-95 flex items-center justify-center gap-3">
                        <span class="material-symbols-rounded">rocket_launch</span>
                        Publish ke Katalog
                    </button>
                </div>
            </form>
        </div>

        <div class="w-full lg:w-80">
            <div class="sticky top-6">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 text-center">Live Preview (Tampilan Reseller)</p>
                
                <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl overflow-hidden">
                    <div class="h-48 bg-gray-50 relative flex items-center justify-center overflow-hidden">
                        <img id="preview_img" src="#" class="hidden w-full h-full object-cover">
                        <span id="preview_icon" class="material-symbols-rounded text-6xl text-gray-200">image</span>
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1.5 rounded-xl text-[9px] font-black text-green-600 border border-green-100">
                            STOK: <span id="preview_stok">0</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <span id="preview_kategori" class="text-[9px] font-black text-green-600 uppercase tracking-widest bg-green-50 px-2 py-0.5 rounded-md">Kategori</span>
                        <h3 id="preview_nama" class="font-bold text-gray-800 text-base mt-2 mb-4 line-clamp-1">Nama Produk</h3>
                        <div class="bg-gray-50 p-3 rounded-xl">
                            <p class="text-[8px] font-black text-gray-400 uppercase mb-0.5">Harga Reseller</p>
                            <p class="text-lg font-black text-gray-800">Rp <span id="preview_harga">0</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Script untuk Live Preview
    const inputs = ['nama_display', 'kategori', 'harga_input'];
    inputs.forEach(id => {
        document.getElementById(id).addEventListener('input', function() {
            let val = this.value;
            if(id === 'harga_input') val = new Intl.NumberFormat('id-ID').format(val);
            document.getElementById('preview_' + id.replace('_input', '').replace('display', 'nama')).innerText = val || '...';
        });
    });

    document.getElementById('barang_id').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        document.getElementById('preview_stok').innerText = selected.dataset.stok || '0';
    });

    document.getElementById('foto_input').onchange = evt => {
        const [file] = evt.target.files;
        if (file) {
            document.getElementById('preview_img').src = URL.createObjectURL(file);
            document.getElementById('preview_img').classList.remove('hidden');
            document.getElementById('preview_icon').classList.add('hidden');
        }
    }
</script>
@endsection