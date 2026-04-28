@extends('layouts.admin')

@section('title', 'Tambah Reseller Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-green-100 overflow-hidden">
        <div class="bg-green-600 px-6 py-4 text-white font-bold flex items-center gap-2">
            <span class="material-symbols-rounded">person_add</span> Input Data Mitra
        </div>

        <form action="{{ route('admin.reseller.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" class="w-full border-gray-200 rounded-xl focus:ring-green-500 shadow-sm" placeholder="Nama Reseller" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Email Login</label>
                <input type="email" name="email" class="w-full border-gray-200 rounded-xl focus:ring-green-500 shadow-sm" placeholder="email@reseller.com" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Password Sementara</label>
                <input type="password" name="password" class="w-full border-gray-200 rounded-xl focus:ring-green-500 shadow-sm" placeholder="Minimal 8 karakter" required>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl shadow-lg transition">
                    Daftarkan Reseller
                </button>
                <a href="{{ route('admin.reseller.index') }}" class="bg-gray-100 px-6 py-3 rounded-xl text-gray-500 font-bold hover:bg-gray-200 transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection