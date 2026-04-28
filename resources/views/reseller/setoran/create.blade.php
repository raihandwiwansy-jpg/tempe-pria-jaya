@extends('layouts.reseller')

@section('title', 'Lapor Setoran Baru')

@section('content')
<div class="max-w-xl mx-auto p-4 md:p-6">
    <a href="{{ route('reseller.setoran.index') }}" class="flex items-center text-gray-500 hover:text-green-600 mb-6 transition-all group">
        <span class="material-symbols-rounded mr-2 group-hover:-translate-x-1 transition-transform">arrow_back</span>
        <span class="font-bold text-sm">Kembali</span>
    </a>

    {{-- SECTION QRIS PEMBAYARAN --}}
    <div class="mb-8 bg-white rounded-[2rem] shadow-xl shadow-gray-100 border border-gray-100 overflow-hidden relative group">
        <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:scale-110 transition-transform duration-700">
            <span class="material-symbols-rounded text-8xl text-green-600">qr_code_2</span>
        </div>
        
        <div class="p-6 md:p-8 flex flex-col md:flex-row items-center gap-6">
            {{-- Box QRIS --}}
            <div class="relative">
                <div class="bg-gradient-to-tr from-green-600 to-emerald-400 p-1.5 rounded-[1.5rem] shadow-lg shadow-green-100">
                    <div class="bg-white p-2 rounded-[1.2rem]">
                        {{-- Ganti URL dibawah dengan path foto QRIS asli kamu --}}
                        <img src="{{ asset('images/qris-admin.png') }}" id="qris-image" 
                             class="w-32 h-32 md:w-40 md:h-40 object-contain rounded-lg" alt="QRIS Pembayaran">
                    </div>
                </div>
                <div class="absolute -bottom-2 -right-2 bg-white p-1.5 rounded-full shadow-md border border-gray-100">
                    <span class="material-symbols-rounded text-green-600 text-sm">verified</span>
                </div>
            </div>

            {{-- Info QRIS --}}
            <div class="flex-1 text-center md:text-left space-y-3">
                <div>
                    <h3 class="text-lg font-black text-gray-800 tracking-tight">QRIS Pembayaran Official</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Scan & Bayar Otomatis</p>
                </div>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-2">
                    <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded-md text-[9px] font-black uppercase">Gopay</span>
                    <span class="px-2 py-1 bg-red-50 text-red-600 rounded-md text-[9px] font-black uppercase">OVO</span>
                    <span class="px-2 py-1 bg-emerald-50 text-emerald-600 rounded-md text-[9px] font-black uppercase">ShopeePay</span>
                    <span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-md text-[9px] font-black uppercase">All Bank</span>
                </div>

                <a href="{{ asset('images/qris-admin.png') }}" download="QRIS_PEMBAYARAN_ADMIN" 
                   class="inline-flex items-center gap-2 bg-gray-900 hover:bg-black text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all hover:shadow-lg active:scale-95">
                    <span class="material-symbols-rounded text-sm">download</span>
                    Simpan QRIS
                </a>
            </div>
        </div>
        
        <div class="bg-gray-50/80 px-8 py-3 border-t border-gray-50 flex items-center justify-between">
            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest italic">*Pastikan nama merchant: TEMPE APP</span>
            <span class="flex gap-1 text-green-500 italic text-[9px] font-black uppercase">Proses Instan</span>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-2xl mb-4 animate-fade-in">
            <div class="flex items-center gap-2 mb-1">
                <span class="material-symbols-rounded text-sm">error</span>
                <span class="font-bold text-xs uppercase tracking-widest">Ada Kendala:</span>
            </div>
            <ul class="list-disc pl-5 text-[11px] font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-100 border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-br from-green-600 to-emerald-500 p-6 md:p-8 text-white text-center md:text-left">
            <h2 class="text-xl md:text-2xl font-black">Konfirmasi Setoran</h2>
            <p class="text-green-100 text-[10px] md:text-xs mt-1 uppercase tracking-widest">Upload Bukti Transfer Disini</p>
        </div>

        <form action="{{ route('reseller.setoran.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-5">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 mb-1.5 uppercase tracking-widest">Jumlah Setoran</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-bold">Rp</span>
                        <input type="number" name="jumlah_setoran" value="{{ old('jumlah_setoran') }}"
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-green-500 font-black text-lg text-gray-800 placeholder-gray-300 transition-all" 
                            placeholder="0" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 mb-1.5 uppercase tracking-widest">Tanggal</label>
                        <input type="date" name="tanggal_setoran" value="{{ old('tanggal_setoran', date('Y-m-d')) }}"
                            class="w-full p-3.5 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-green-500 font-bold text-xs text-gray-700 transition-all" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 mb-1.5 uppercase tracking-widest">Foto Bukti</label>
                        <label class="flex flex-col items-center justify-center w-full h-[46px] bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 border border-dashed border-gray-300 transition-all">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-rounded text-gray-400 text-sm">add_a_photo</span>
                                <span class="text-[10px] font-bold text-gray-400 truncate max-w-[80px]" id="file-name">Upload...</span>
                            </div>
                            <input type="file" name="bukti_pembayaran" class="hidden" id="bukti_input" accept="image/*" onchange="previewFile()">
                        </label>
                    </div>
                </div>

                <div id="preview-container" class="hidden animate-fade-in flex justify-center bg-gray-50 p-2 rounded-2xl border border-gray-100">
                    <div class="relative group">
                        <img id="image-preview" class="w-24 h-24 md:w-32 md:h-32 object-cover rounded-xl shadow-md border-2 border-white">
                        <div class="absolute -top-2 -right-2 bg-green-500 text-white rounded-full p-1 shadow-lg">
                            <span class="material-symbols-rounded text-[14px]">check</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 mb-1.5 uppercase tracking-widest">Keterangan</label>
                    <textarea name="keterangan" rows="2" 
                        class="w-full p-3.5 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-green-500 font-medium text-xs text-gray-700 placeholder-gray-300 transition-all" 
                        placeholder="Contoh: Setoran minggu pertama">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-black py-4 rounded-xl shadow-lg shadow-green-100 transition-all flex items-center justify-center gap-2 group">
                <span class="text-sm">Kirim Laporan</span>
                <span class="material-symbols-rounded text-lg group-hover:translate-x-1 transition-transform">send</span>
            </button>
        </form>
    </div>

    <div class="mt-6 p-4 bg-white/50 rounded-2xl border border-gray-100 flex gap-3 items-center">
        <span class="material-symbols-rounded text-amber-500">verified_user</span>
        <p class="text-[10px] text-gray-500 leading-tight font-medium">
            Laporan Anda akan diproses manual oleh Admin. Pastikan nominal transfer sesuai dengan mutasi rekening.
        </p>
    </div>
</div>

<script>
    function previewFile() {
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('preview-container');
        const file = document.getElementById('bukti_input').files[0];
        const fileName = document.getElementById('file-name');
        const reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
            container.classList.remove('hidden');
            fileName.textContent = file.name;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
            container.classList.add('hidden');
            fileName.textContent = "Upload...";
        }
    }
</script>

<style>
    .animate-fade-in { animation: fadeIn 0.3s ease-in; }
    @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
</style>
@endsection