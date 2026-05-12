<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Services\WablasService;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class StatusUpdateNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $url;

    public function __construct($message, $url = '#')
    {
        $this->message = $message;
        $this->url = $url;
    }

    public function via($notifiable)
    {
        $this->sendWhatsapp($notifiable);
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Update Laundry',
            'message' => $this->message,
            'url' => $this->url,
        ];
    }

    protected function sendWhatsapp($notifiable)
    {
        try {
            $phone = $notifiable->no_telp;
            if (!$phone) return;

            $message = $this->message;

            // Tambahkan link transaksi jika tersedia dan valid
            if ($this->url !== '#' && filter_var($this->url, FILTER_VALIDATE_URL)) {
                $message .= "\n\nDetail transaksi bisa dilihat di link berikut:\n" . $this->url;
            }

            // Ambil semua kuota laundry user
            $kuotaItems = $notifiable->kuotaLaundry;

            if ($kuotaItems->count() > 0) {
                $message .= "\n\nSisa kuota laundry Anda saat ini:";

                foreach ($kuotaItems as $item) {
                    $message .= "\n- " . ucfirst($item->kategori) . ": *" . $item->kuota . "*";
                }
            }

            // Tambahkan informasi detail akun
            $message .= "\n\nUntuk melihat detail akun Anda, silakan kunjungi:\n" . url('/home');

            $message .= "\n\nTerima kasih telah menggunakan layanan kami!";

            // Kirim via Wablas
            app(WablasService::class)->sendMessage($phone, $message);
        } catch (\Exception $e) {
            \Log::error('WA StatusUpdate gagal: ' . $e->getMessage());
        }
    }
}
