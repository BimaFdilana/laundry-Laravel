<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Services\WablasService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class PaketDikonfirmasiNotification extends Notification
{
    use Queueable;

    protected $purchase;
    protected $message;
    protected $url;

    public function __construct($purchase)
    {
        $this->purchase = $purchase;

        $this->message = 'Paket ' . $this->purchase->package_kg . ' kg '
            . '(Kategori: ' . $this->purchase->package_category . ', Harga: Rp'
            . number_format($this->purchase->package_price, 0, ',', '.') . ') Anda telah dikonfirmasi.';

        $this->url = route('home'); // Ganti jika ada halaman khusus paket
    }

    public function via($notifiable)
    {
        $this->sendWhatsapp($notifiable);
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Paket Dikonfirmasi',
            'message' => $this->message,
            'url' => $this->url,
        ];
    }

    protected function sendWhatsapp($notifiable)
    {
        try {
            $phone = $notifiable->no_telp;
            if (!$phone) return;

            $message = $this->message . "\n\nLihat detail: " . $this->url;

            $response = app(WablasService::class)->sendMessage($phone, $message);

            Log::info('Respons Wablas:', $response);
        } catch (\Exception $e) {
            Log::error('WA PaketDikonfirmasi gagal: ' . $e->getMessage());
        }
    }
}
