<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gift;
use App\Models\User;
use App\Notifications\GiftNotification;
use Illuminate\Http\Request;

class GiftController extends Controller
{
    public function index()
    {
        $gifts = Gift::with('user')->latest()->get();
        return view('modul_admin.gift.index', compact('gifts'));
    }

    public function create()
    {
        $users = User::where('auth', 'Customer')->get();
        return view('modul_admin.gift.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'gift' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255',
            'expired_at' => 'nullable|date',
        ]);

        $gift = Gift::create($request->all());

        // Kirim notifikasi ke customer yang dipilih
        $user = User::find($request->user_id);
        $user->notify(new GiftNotification($gift));

        return redirect()->route('gift.index')->with('success', 'Gift berhasil ditambahkan.');
    }

    public function edit(Gift $gift)
    {
        $users = User::where('auth', 'Customer')->get();
        return view('modul_admin.gift.edit', compact('gift', 'users'));
    }

    public function update(Request $request, Gift $gift)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'gift' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255',
            'expired_at' => 'nullable|date',
        ]);

        $gift->update($request->all());

        // Kirim notifikasi ke customer terkait update gift
        $user = User::find($request->user_id);
        $user->notify(new GiftNotification($gift, true));

        return redirect()->route('gift.index')->with('success', 'Gift berhasil diperbarui.');
    }

    public function destroy(Gift $gift)
    {
        $gift->delete();
        return redirect()->route('gift.index')->with('success', 'Gift berhasil dihapus.');
    }
}
