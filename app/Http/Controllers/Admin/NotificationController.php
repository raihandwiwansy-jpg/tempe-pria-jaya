<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Perbaikan: 'App' harus huruf besar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Menandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllRead()
    {
        $user = Auth::user();
        
        if ($user) {
            $user->unreadNotifications->markAsRead();
            return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
        }

        return back()->with('error', 'Gagal memproses permintaan.');
    }

    /**
     * Menghapus satu log notifikasi berdasarkan ID
     */
    public function destroy($id)
    {
        try {
            $notification = Auth::user()->notifications()->findOrFail($id);
            $notification->delete();

            return back()->with('success', 'Log aktivitas berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus log: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus seluruh riwayat notifikasi (Log History)
     */
    public function clearAll()
    {
        try {
            $user = Auth::user();
            
            // Menghapus semua record di tabel notifications milik user ini
            $user->notifications()->delete();

            return back()->with('success', 'Semua log aktivitas telah dibersihkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membersihkan log: ' . $e->getMessage());
        }
    }
}