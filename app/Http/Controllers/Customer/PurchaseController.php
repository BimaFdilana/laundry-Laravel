<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PurchaseRequest;
use App\Models\User;
use App\Notifications\PaketDipesanNotification;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'package_kg' => 'required',
                'package_price' => 'required',
                'package_category' => 'required',
            ]);

            // Throttle: cegah double-submit dalam 2 menit
            $recent = PurchaseRequest::where('user_id', auth()->id())
                ->where('created_at', '>=', now()->subMinutes(2))
                ->latest()
                ->first();

            if ($recent) {
                return response()->json(['message' => 'Permintaan paket baru saja dikirim. Tunggu konfirmasi admin.'], 429);
            }

            $purchase = PurchaseRequest::create([
                'user_id' => auth()->id(),
                'package_kg' => $request->package_kg,
                'package_price' => $request->package_price,
                'package_category' => $request->package_category,
                'status' => 'pending',
            ]);

            // Kirim notifikasi ke semua admin
            $admins = User::where('auth', 'Admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new PaketDipesanNotification($purchase));
            }

            return response()->json(['message' => 'Request berhasil disimpan']);
        } catch (\Exception $e) {
            \Log::error('Error saat simpan request: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan request'], 500);
        }
    }
}
