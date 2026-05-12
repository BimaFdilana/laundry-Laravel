<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WablasService
{
    protected $token;
    protected $url;

    public function __construct()
    {
        $this->token = config('services.wablas.token');
        $this->url = config('services.wablas.url');
    }

    public function sendMessage($phone, $message)
    {
        \Log::info('Token yang digunakan:', [$this->token]); // Debug token

        $response = Http::withHeaders([
            'Authorization' => $this->token
        ])->post($this->url, [
            'phone' => $phone,
            'message' => $message,
            'secret' => "KkNeuaEz",
            'priority' => false
        ]);

        return $response->json();
    }
}
