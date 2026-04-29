<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DataTerbaruNotification extends Notification
{
    use Queueable;

    private $pesan;

    public function __construct($pesan)
    {
        $this->pesan = $pesan;
    }

    public function via($notifiable)
    {
        // Kita simpan ke database saja
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'pesan' => $this->pesan,
        ];
    }
}