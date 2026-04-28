@extends('layouts.admin')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header Form --}}
        <div class="p-8 bg-gray-50/50 border-b border-gray-100">
            <h3 class="text-xl font-black text-gray-800">Input Karyawan Baru</h3>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Pastikan data yang dimasukkan sudah sesuai</p>
        </div>

        <div class="p-8">
            <form action="{{ route('admin.karyawan.store') }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- Field Nama --}}
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" 
                        class="w-full mt-2 px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 font-bold text-gray-800 placeholder:text-gray-300" 
                        placeholder="Masukkan nama karyawan..." required>
                    @error('nama')
                        <p class="text-red-500 text-[10px] font-bold mt-1 ml-2 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field Alamat --}}
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Alamat Domisili</label>
                    <textarea name="alamat" rows="3" 
                        class="w-full mt-2 px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 font-bold text-gray-800 placeholder:text-gray-300" 
                        placeholder="Masukkan alamat lengkap..." required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-[10px] font-bold mt-1 ml-2 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field Gaji --}}
                <div>
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Gaji Per Bulan (Rp)</label>
                    <input type="number" name="gaji_perbulan" value="{{ old('gaji_perbulan') }}" 
                        class="w-full mt-2 px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-green-500 font-bold text-gray-800 placeholder:text-gray-300" 
                        placeholder="Contoh: 3500000" required>
                    @error('gaji_perbulan')
                        <p class="text-red-500 text-[10px] font-bold mt-1 ml-2 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 bg-green-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-green-700 transition shadow-lg shadow-green-100">
                        Simpan Data Karyawan
                    </button>
                    <a href="{{ route('admin.karyawan.index') }}" class="px-8 py-4 bg-gray-100 text-gray-400 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection