<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GudangController;
use App\Http\Controllers\Admin\ProduksiController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\KeuanganController;
use App\Http\Controllers\Admin\PesananAdminController;
use App\Http\Controllers\Admin\ResellerController;
use App\Http\Controllers\Reseller\PesananController;
use App\Http\Controllers\Reseller\SetoranController;
use App\Http\Controllers\Admin\SetoranAdminController;
use App\Http\Controllers\Reseller\InfoBarangController;
use App\Http\Controllers\Admin\BarangResellerController; // SUDAH DIPERBAIKI: App\Http (Huruf Besar)
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProduksiJadiController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ================= HALAMAN AWAL =================
Route::get('/', function () {
    return view('welcome');
});

// ================= REDIRECT DASHBOARD =================
Route::get('/dashboard', function () {
    $user = Auth::user();
    if (!$user) return redirect('/login');
    
    // Redirect berdasarkan role ke Route Name yang benar
    return ($user->role === 'admin') 
        ? redirect()->route('admin.dashboard') 
        : redirect()->route('reseller.dashboard');
})->middleware(['auth'])->name('dashboard');


// ================= GRUP ADMIN =================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin (Lewat Controller agar data pesanan reseller muncul)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // GUDANG
        Route::get('/gudang', [GudangController::class, 'index'])->name('gudang.index');
        Route::get('/gudang/create', [GudangController::class, 'create'])->name('gudang.create');
        Route::post('/gudang/store', [GudangController::class, 'store'])->name('gudang.store');
        Route::post('/gudang/transaksi', [GudangController::class, 'transaksi'])->name('gudang.transaksi');
        Route::delete('/gudang/{id}', [GudangController::class, 'destroy'])->name('gudang.destroy');

        // PRODUKSI
        Route::get('/produksi', [ProduksiController::class, 'index'])->name('produksi.index');
        Route::get('/produksi/create', [ProduksiController::class, 'create'])->name('produksi.create');
        Route::post('/produksi/store', [ProduksiController::class, 'store'])->name('produksi.store');
        Route::delete('/produksi/{id}', [ProduksiController::class, 'destroy'])->name('produksi.destroy');


        // KARYAWAN
        Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
        Route::get('/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('/karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::post('/karyawan/absensi', [KaryawanController::class, 'absensi'])->name('karyawan.absensi');
         Route::delete('/karyawan/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
        Route::get('/karyawan/{id}/info', [KaryawanController::class, 'info'])->name('karyawan.info');

        

        // KEUANGAN
        Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
        Route::get('/keuangan/create', [KeuanganController::class, 'create'])->name('keuangan.create');
        Route::post('/keuangan', [KeuanganController::class, 'store'])->name('keuangan.store');

        // PEMESANAN ADMIN (Bisa melihat & update pesanan dari Reseller)
        Route::get('/pesanan', [PesananAdminController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/create', [PesananAdminController::class, 'create'])->name('pesanan.create');
        Route::post('/pesanan', [PesananAdminController::class, 'store'])->name('pesanan.store');
        Route::patch('/pesanan/{id}/status', [PesananAdminController::class, 'updateStatus'])->name('pesanan.status');
        Route::delete('/pesanan/{id}', [PesananAdminController::class, 'destroy'])->name('pesanan.destroy');

        // MANAJEMEN RESELLER
        Route::get('/reseller', [ResellerController::class, 'index'])->name('reseller.index');
        Route::get('/reseller/create', [ResellerController::class, 'create'])->name('reseller.create');
        Route::post('/reseller', [ResellerController::class, 'store'])->name('reseller.store');
        Route::delete('/reseller/{id}', [ResellerController::class, 'destroy'])->name('reseller.destroy');

        // SETORAN RESELLER (Admin bisa melihat & update status laporan setoran dari Reseller)
        Route::get('/setoran', [SetoranAdminController::class, 'index'])->name('setoran.index');
        Route::patch('/setoran/{id}', [SetoranAdminController::class, 'updateStatus'])->name('setoran.update');
        Route::delete('/setoran/{id}', [SetoranAdminController::class, 'destroy'])->name('setoran.destroy');

        // BARANG RESELLER (Katalog)
        Route::get('/barang-reseller', [BarangResellerController::class, 'index'])->name('barang_reseller.index');
        Route::get('/barang-reseller/create', [BarangResellerController::class, 'create'])->name('barang_reseller.create');
        Route::post('/barang-reseller', [BarangResellerController::class, 'store'])->name('barang_reseller.store');
        Route::delete('/barang-reseller/{id}', [BarangResellerController::class, 'destroy'])->name('barang_reseller.destroy');

        // Route untuk menandai semua notifikasi sebagai "sudah dibaca"
        Route::get('/notifications/mark-all-read', function () {auth()->user()->unreadNotifications->markAsRead(); return redirect()->back()->with('success', 'Semua notifikasi telah dibaca.');})->name('notifications.markAllRead');

         // Route untuk menandai satu notifikasi saja sebagai "sudah dibaca"
        Route::get('/notifications/{id}/mark-read', function ($id) {$notification = auth()->user()->notifications()->findOrFail($id);$notification->markAsRead();return redirect()->back();})->name('notifications.markRead');
        
        Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
        Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('/notifications-clear', [NotificationController::class, 'clearAll'])->name('notifications.clearAll');

        // PRODUKSI JADI
        Route::get('/produksi-jadi', [ProduksiJadiController::class, 'index'])->name('produksi_jadi.index');
        Route::post('/produksi-jadi', [ProduksiJadiController::class, 'store'])->name('produksi_jadi.store');
        Route::delete('/produksi-jadi/{id}', [ProduksiJadiController::class, 'destroy'])->name('produksi_jadi.destroy');

    });
    

// ================= GRUP RESELLER =================
Route::middleware(['auth', 'role:reseller'])
    ->prefix('reseller')
    ->name('reseller.')
    ->group(function () {
        
        Route::get('/dashboard', fn() => view('reseller.dashboard'))->name('dashboard');
        
        // PESANAN RESELLER
        Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/create', [PesananController::class, 'create'])->name('pesanan.create');
        Route::post('/pesanan', [PesananController::class, 'store'])->name('pesanan.store');
        Route::patch('/pesanan/{id}/status', [PesananController::class, 'updateStatus'])->name('pesanan.status');
        Route::delete('/pesanan/{id}', [PesananController::class, 'destroy'])->name('pesanan.destroy');

        // SETORAN RESELLER
        Route::get('/setoran', [SetoranController::class, 'index'])->name('setoran.index');
        Route::get('/setoran/create', [SetoranController::class, 'create'])->name('setoran.create');
        Route::post('/setoran', [SetoranController::class, 'store'])->name('setoran.store');
        Route::delete('/setoran/{id}', [SetoranController::class, 'destroy'])->name('setoran.destroy');

        // INFO BARANG RESELLER
        Route::get('/info-barang', [InfoBarangController::class, 'index'])->name('info_barang.index');
    });

// ================= PROFILE & AUTH =================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';