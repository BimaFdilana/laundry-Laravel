<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WablasService
{
    protected $token;
    protected $url;
    protected $secret;

    public function __construct()
    {
        $this->token = config('services.wablas.token');
        $this->url = config('services.wablas.url');
        $this->secret = config('services.wablas.secret', 'KkNeuaEz');
    }

    public function sendMessage($phone, $message)
    {
        $formattedPhone = $this->formatPhone($phone);
        \Log::info('Mengirim WA via Wablas ke: ' . $formattedPhone);

        $response = Http::timeout(5)->withHeaders([
            'Authorization' => $this->token
        ])->post($this->url, [
            'phone' => $formattedPhone,
            'message' => $message,
            'secret' => $this->secret,
            'priority' => false
        ]);

        $result = $response->json();
        \Log::info('Respons Wablas:', [$result]);

        return $result;
    }

    private function formatPhone($phone)
    {
        // Hapus semua karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Jika diawali dengan '0', ganti dengan '62'
        if (strpos($phone, '0') === 0) {
            return '62' . substr($phone, 1);
        }

        // Jika diawali dengan '8', ganti dengan '628'
        if (strpos($phone, '8') === 0) {
            return '62' . $phone;
        }

        return $phone;
    }
}
