<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function readAllNotif()
    {
        $user = auth()->user();

        if ($user->auth !== 'Customer') {
            abort(403, 'Anda tidak diizinkan mengakses ini.');
        }

        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi telah dibaca.');
    }
}
