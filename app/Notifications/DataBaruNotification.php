<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DataTerbaruNotification extends Notification
{
    use Queueable;

    protected $pesan;

    public function __construct($pesan)
    {
        $this->pesan = $pesan;
    }

    public function via($notifiable)
    {
        return ['database']; // Simpan ke database
    }

    public function toArray($notifiable)
    {
        return [
            'pesan' => $this->pesan,
            'icon' => 'inventory_2',
            'waktu' => now()->format('H:i'),
        ];
    }
}