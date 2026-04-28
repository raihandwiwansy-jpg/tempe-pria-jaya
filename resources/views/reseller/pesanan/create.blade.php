@extends('layouts.reseller')

@section('content')
<div class="max-w-xl mx-auto p-6">
    <div class="bg-white rounded-2xl shadow-lg border border-green-100 overflow-hidden">
        <div class="bg-green-600 p-4 text-white font-bold flex items-center gap-2">
            <span class="material-symbols-rounded">add_shopping_cart</span>
            Input Pesanan Pelanggan
        </div>
        
        <form action="{{ route('reseller.pesanan.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Pelanggan</label>
                <input type="text" name="nama_pemesan" class="w-full border-gray-200 rounded-xl focus:ring-green-500" placeholder="Nama pembeli..." required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Pengiriman</label>
                <textarea name="alamat_pemesan" class="w-full border-gray-200 rounded-xl focus:ring-green-500" rows="2" placeholder="Alamat lengkap..."></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Jumlah (Pcs)</label>
                    <input type="number" name="jumlah" class="w-full border-gray-200 rounded-xl focus:ring-green-500" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Ukuran</label>
                    <select name="ukuran" class="w-full border-gray-200 rounded-xl focus:ring-green-500">
                        <option value="Kecil">Kecil</option>
                        <option value="Sedang">Sedang</option>
                        <option value="Besar">Besar</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Kirim</label>
                <input type="date" name="tanggal_kirim" class="w-full border-gray-200 rounded-xl focus:ring-green-500" required>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-green-700 transition">
                Kirim Pesanan ke Pabrik
            </button>
        </form>
    </div>
</div>
@endsection