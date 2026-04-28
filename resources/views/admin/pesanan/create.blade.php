@extends('layouts.admin')

@section('title', 'Buat Pesanan Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <a href="{{ route('admin.pesanan.index') }}" class="flex items-center text-green-600 hover:text-green-700 font-semibold mb-6 transition">
        <span class="material-symbols-rounded mr-1">arrow_back</span>
        Kembali ke Daftar Pesanan
    </a>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-500 px-8 py-6">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-white/20 rounded-2xl text-white">
                    <span class="material-symbols-rounded">add_shopping_cart</span>
                </div>
                <div>
                    <h3 class="text-white font-bold text-xl">Tambah Pesanan Manual</h3>
                    <p class="text-green-100 text-xs">Gunakan form ini untuk pesanan yang masuk langsung ke Admin.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.pesanan.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Reseller / Sales</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <span class="material-symbols-rounded text-lg">person_search</span>
                        </span>
                        <select name="id_reseller_assign" class="w-full pl-10 border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500 shadow-sm transition" required>
                            <option value="">-- Pilih Penanggung Jawab --</option>
                            @foreach($resellers as $reseller)
                                <option value="{{ $reseller->id }}">{{ $reseller->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Pelanggan (End User)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <span class="material-symbols-rounded text-lg">badge</span>
                        </span>
                        <input type="text" name="nama_pemesan" class="w-full pl-10 border-gray-200 rounded-xl focus:ring-green-500 shadow-sm transition" placeholder="Contoh: Ibu Siti - Warung Berkah" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Pesanan (Pcs)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <span class="material-symbols-rounded text-lg">box_edit</span>
                        </span>
                        <input type="number" name="jumlah" class="w-full pl-10 border-gray-200 rounded-xl focus:ring-green-500 shadow-sm transition" placeholder="0" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Ukuran Tempe</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <span class="material-symbols-rounded text-lg">straighten</span>
                        </span>
                        <select name="ukuran" class="w-full pl-10 border-gray-200 rounded-xl focus:ring-green-500 shadow-sm transition">
                            <option value="Kecil">Kecil</option>
                            <option value="Sedang" selected>Sedang (Standar)</option>
                            <option value="Besar">Besar</option>
                        </select>
                    </div>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pengiriman</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <span class="material-symbols-rounded text-lg">local_shipping</span>
                        </span>
                        <input type="date" name="tanggal_kirim" value="{{ date('Y-m-d') }}" class="w-full pl-10 border-gray-200 rounded-xl focus:ring-green-500 shadow-sm transition" required>
                    </div>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap Tujuan</label>
                    <textarea name="alamat_pemesan" rows="3" class="w-full border-gray-200 rounded-xl focus:ring-green-500 shadow-sm transition" placeholder="Masukkan alamat lengkap pengiriman..."></textarea>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-50 flex items-center gap-4">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-green-100 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-rounded">save</span>
                    Simpan & Proses Pesanan
                </button>
                <button type="reset" class="px-8 py-4 bg-gray-50 text-gray-400 font-bold rounded-2xl hover:bg-gray-100 transition">
                    Reset
                </button>
            </div>
        </form>
    </div>

    <div class="mt-6 bg-blue-50 border border-blue-100 p-4 rounded-2xl flex gap-3 shadow-sm">
        <span class="material-symbols-rounded text-blue-500">info</span>
        <p class="text-xs text-blue-700 leading-relaxed font-medium">
            Pesanan yang diinput manual oleh Admin akan tetap muncul di dashboard Reseller yang bersangkutan agar mereka bisa melakukan pengantaran dan penagihan.
        </p>
    </div>
</div>
@endsection