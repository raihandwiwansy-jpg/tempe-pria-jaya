@extends('layouts.admin')

@section('title', 'Detail Kehadiran - ' . $karyawan->nama)

@section('content')
<div class="container mx-auto p-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Card Informasi Gaji --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center font-black text-xl mb-4">
                    {{ strtoupper(substr($karyawan->nama, 0, 2)) }}
                </div>
                <h3 class="text-xl font-black text-gray-800">{{ $karyawan->nama }}</h3>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $karyawan->alamat }}</p>
                
                <div class="mt-8 pt-8 border-t border-dashed border-gray-200 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-[10px] font-black text-gray-400 uppercase">Gaji Pokok</span>
                        <span class="text-sm font-bold text-gray-700">Rp {{ number_format($karyawan->gaji_perbulan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[10px] font-black text-red-400 uppercase">Potongan ({{ $totalTidakHadir }}x)</span>
                        <span class="text-sm font-bold text-red-600">- Rp {{ number_format($totalPotongan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between p-4 bg-green-50 rounded-2xl">
                        <span class="text-[10px] font-black text-green-700 uppercase">Gaji Bersih</span>
                        <span class="text-lg font-black text-green-700">Rp {{ number_format($gajiBersih, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Riwayat Absensi --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-black text-gray-800">Riwayat Kehadiran</h3>
                    <a href="{{ route('admin.karyawan.index') }}" class="text-[10px] font-black uppercase text-gray-400 hover:text-green-600 transition">Kembali</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-8 py-4 text-[10px] font-black uppercase text-gray-400">Tanggal</th>
                                <th class="px-8 py-4 text-[10px] font-black uppercase text-gray-400 text-center">Status</th>
                                <th class="px-8 py-4 text-[10px] font-black uppercase text-gray-400 text-right">Potongan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($absensi as $a)
                            <tr>
                                <td class="px-8 py-4 text-sm font-bold text-gray-700">
                                    {{ \Carbon\Carbon::parse($a->tanggal)->format('d F Y') }}
                                </td>
                                <td class="px-8 py-4 text-center">
                                    @if($a->status == 'hadir')
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-[9px] font-black uppercase">Hadir</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-[9px] font-black uppercase">Tidak Hadir</span>
                                    @endif
                                </td>
                                <td class="px-8 py-4 text-right font-bold text-sm {{ $a->status == 'tidak' ? 'text-red-500' : 'text-gray-300' }}">
                                    {{ $a->status == 'tidak' ? '- Rp 20.000' : 'Rp 0' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="p-12 text-center text-gray-400 text-xs font-bold uppercase tracking-widest">Belum ada data absensi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection