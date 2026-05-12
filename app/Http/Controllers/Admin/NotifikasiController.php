<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function readAllNotif()
    {
        $user = auth()->user();

        if ($user->auth !== 'Admin') {
            abort(403, 'Anda tidak diizinkan mengakses ini.');
        }

        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi telah dibaca.');
    }
}
