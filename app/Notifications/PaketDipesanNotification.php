<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaketDipesanNotification extends Notification
{
    use Queueable;

    protected $purchase;

    public function __construct($purchase)
    {
        $this->purchase = $purchase;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Paket Baru Dipesan',
            'message' => $this->purchase->user->name . ' memesan paket ' . $this->purchase->package_kg . ' kg (Kategori: ' . $this->purchase->package_category . ', Harga: Rp' . number_format($this->purchase->package_price, 0, ',', '.') . ')',
            'url' => route('konfirmasi.index'),
        ];
    }
}
