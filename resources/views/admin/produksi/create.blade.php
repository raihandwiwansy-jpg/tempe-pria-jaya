@extends('layouts.admin')

@section('title', 'Input Hasil Produksi')

@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('admin.produksi.index') }}" class="flex items-center text-green-600 hover:text-green-700 font-semibold mb-6 transition">
        <span class="material-symbols-rounded mr-1">arrow_back</span>
        Kembali ke Riwayat Produksi
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-green-100 overflow-hidden">
        <div class="bg-green-600 px-8 py-5">
            <h3 class="text-white font-bold text-lg flex items-center gap-2">
                <span class="material-symbols-rounded">precision_manufacturing</span>
                Form Input Produksi Harian
            </h3>
            <p class="text-green-100 text-xs">Catat hasil produksi tempe dan penggunaan bahan baku secara akurat.</p>
        </div>

        <form action="{{ route('admin.produksi.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Produksi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <span class="material-symbols-rounded text-lg">calendar_today</span>
                        </span>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                            class="w-full pl-10 border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500 shadow-sm transition" required>
                    </div>
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Hasil Produksi (kg)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <span class="material-symbols-rounded text-lg">inventory_2</span>
                        </span>
                        <input type="number" step="0.01" name="jumlah_produksi" 
                            class="w-full pl-10 border-gray-200 rounded-xl focus:ring-green-500 shadow-sm transition" 
                            placeholder="Total berat tempe jadi" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kedelai Digunakan (kg)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-amber-600">
                            <span class="material-symbols-rounded text-lg">nutrition</span>
                        </span>
                        <input type="number" step="0.01" name="kedelai_kg" 
                            class="w-full pl-10 border-gray-200 rounded-xl focus:ring-green-500 shadow-sm transition" 
                            placeholder="Berat kedelai kering" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Plastik Digunakan (kg)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-blue-500">
                            <span class="material-symbols-rounded text-lg">layers</span>
                        </span>
                        <input type="number" step="0.01" name="plastik_kg" 
                            class="w-full pl-10 border-gray-200 rounded-xl focus:ring-green-500 shadow-sm transition" 
                            placeholder="Berat plastik pembungkus" required>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" rows="3" 
                    class="w-full border-gray-200 rounded-xl focus:ring-green-500 shadow-sm transition" 
                    placeholder="Contoh: Kualitas kedelai sangat baik, atau kendala mesin."></textarea>
            </div>

            <div class="pt-6 border-t border-gray-100 flex gap-4">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-green-100 transition duration-200 flex items-center justify-center gap-2">
                    <span class="material-symbols-rounded">save</span>
                    Simpan Laporan Produksi
                </button>
                <button type="reset" class="bg-gray-100 hover:bg-gray-200 text-gray-500 font-bold py-4 px-8 rounded-xl transition duration-200">
                    Reset
                </button>
            </div>
        </form>
    </div>

    <div class="mt-6 bg-amber-50 border border-amber-100 rounded-xl p-4 flex gap-3">
        <span class="material-symbols-rounded text-amber-600">warning</span>
        <p class="text-xs text-amber-800 leading-relaxed">
            <strong>Perhatian:</strong> Menyimpan data ini akan otomatis memotong stok <strong>Kedelai</strong> dan <strong>Plastik</strong> di menu Gudang sesuai dengan jumlah yang Anda masukkan.
        </p>
    </div>
</div>
@endsection