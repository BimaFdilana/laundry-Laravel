<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Services\WablasService;
use Illuminate\Support\Facades\Log;

class GiftNotification extends Notification
{
    use Queueable;

    protected $gift;
    protected $isUpdate;
    protected $message;
    protected $url;

    public function __construct($gift, $isUpdate = false)
    {
        $this->gift = $gift;
        $this->isUpdate = $isUpdate;

        $this->message = $this->isUpdate
            ? 'Gift "' . $this->gift->gift . '" telah diperbarui oleh admin.'
            : 'Selamat! Anda mendapatkan gift "' . $this->gift->gift . '" dari Laundry Camp.';

        $this->url = route('gift-customer.index');
    }

    public function via($notifiable)
    {
        $this->sendWhatsapp($notifiable);
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->isUpdate ? 'Gift Anda Diperbarui' : 'Anda mendapatkan Gift!',
            'message' => $this->message,
            'url' => $this->url,
        ];
    }

    protected function sendWhatsapp($notifiable)
    {
        try {
            $phone = $notifiable->no_telp;

            // Logging untuk debugging
            \Log::info('Kirim WA ke:', ['phone' => $phone]);

            if (!$phone) {
                \Log::warning('Nomor WA kosong!');
                return;
            }

            // Tambahkan URL di akhir pesan
            $message = $this->isUpdate
                ? "Gift '{$this->gift->gift}' telah diperbarui oleh admin."
                : "Selamat! Anda mendapatkan gift '{$this->gift->gift}' dari Laundry Camp.";

            // Gabungkan dengan URL
            $message .= "\n\nLihat detail: " . $this->url;

            $response = app(WablasService::class)->sendMessage($phone, $message);

            \Log::info('Respons Wablas:', $response);
        } catch (\Exception $e) {
            \Log::error('Gagal kirim WA: ' . $e->getMessage());
        }
    }
}
