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
use App\Http\Controllers\Admin\BarangResellerController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProduksiJadiController;
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
    
    return ($user->role === 'admin') 
        ? redirect()->route('admin.dashboard') 
        : redirect()->route('reseller.dashboard');
})->middleware(['auth'])->name('dashboard');


// ================= GRUP ADMIN =================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.') // Semua nama route di sini otomatis diawali 'admin.'
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // GUDANG
        Route::controller(GudangController::class)->group(function () {
            Route::get('/gudang', 'index')->name('gudang.index');
            Route::get('/gudang/create', 'create')->name('gudang.create');
            Route::post('/gudang/store', 'store')->name('gudang.store');
            Route::post('/gudang/transaksi', 'transaksi')->name('gudang.transaksi');
            Route::delete('/gudang/{id}', 'destroy')->name('gudang.destroy');
        });

        // PRODUKSI (BAHAN BAKU)
        Route::resource('produksi', ProduksiController::class)->except(['show', 'edit', 'update']);

        // PRODUKSI JADI (SULUSI MENTAL: Menggunakan URL Unik)
        // Kita pakai nama resource 'produksi-jadi' tapi name tetap 'produksi_jadi' agar sinkron dengan view
        Route::resource('produksi-jadi', ProduksiJadiController::class)
            ->names('produksi_jadi') 
            ->only(['index', 'store', 'destroy']);

        // KARYAWAN
        Route::get('/karyawan/{id}/info', [KaryawanController::class, 'info'])->name('karyawan.info');
        Route::post('/karyawan/absensi', [KaryawanController::class, 'absensi'])->name('karyawan.absensi');
        Route::resource('karyawan', KaryawanController::class);

        // KEUANGAN
        Route::resource('keuangan', KeuanganController::class)->only(['index', 'create', 'store']);

        // PEMESANAN ADMIN
        Route::patch('/pesanan/{id}/status', [PesananAdminController::class, 'updateStatus'])->name('pesanan.status');
        Route::resource('pesanan', PesananAdminController::class);

        // MANAJEMEN RESELLER
        Route::resource('reseller', ResellerController::class);

        // SETORAN RESELLER
        Route::patch('/setoran/{id}', [SetoranAdminController::class, 'updateStatus'])->name('setoran.update');
        Route::resource('setoran', SetoranAdminController::class)->only(['index', 'destroy']);

        // BARANG RESELLER (Katalog)
        Route::resource('barang-reseller', BarangResellerController::class)->names('barang_reseller');

        // NOTIFIKASI
        Route::controller(NotificationController::class)->group(function () {
            Route::get('/notifications/mark-all-read', 'markAllRead')->name('notifications.markAllRead');
            Route::get('/notifications/{id}/mark-read', 'markRead')->name('notifications.markRead');
            Route::delete('/notifications/{id}', 'destroy')->name('notifications.destroy');
            Route::delete('/notifications-clear', 'clearAll')->name('notifications.clearAll');
        });
    });

// ================= GRUP RESELLER =================
Route::middleware(['auth', 'role:reseller'])
    ->prefix('reseller')
    ->name('reseller.')
    ->group(function () {
        
        Route::get('/dashboard', fn() => view('reseller.dashboard'))->name('dashboard');
        
        Route::patch('/pesanan/{id}/status', [PesananController::class, 'updateStatus'])->name('pesanan.status');
        Route::resource('pesanan', PesananController::class);
        
        Route::resource('setoran', SetoranController::class);
        
        Route::get('/info-barang', [InfoBarangController::class, 'index'])->name('info_barang.index');
    });

// ================= PROFILE & AUTH =================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';