<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Gift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GiftController extends Controller
{
    public function index()
    {
        $gifts = Gift::where('user_id', Auth::id())
            ->where(function ($q) {
                $q->whereNull('expired_at')
                    ->orWhere('expired_at', '>=', now());
            })
            ->where('is_read', false)
            ->latest()
            ->get();

        return view('customer.gift', compact('gifts'));
    }

    public function markAsRead(Gift $gift)
    {
        if ($gift->user_id !== Auth::id()) {
            // Menambahkan pesan kesalahan jika user tidak bisa menandai gift
            return redirect()->route('gift-customer.index')
                ->withErrors('Anda tidak memiliki akses untuk menandai gift ini sebagai sudah dibaca.');
        }

        $gift->update(['is_read' => true]);

        return redirect()->route('gift-customer.index')->with('success', 'Gift ditandai sudah dibaca.');
    }
}
