<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KuotaLaundry;
use App\Models\Pemasukan;
use App\Models\PurchaseRequest;
use App\Notifications\PaketDikonfirmasiNotification;
use Illuminate\Http\Request;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        $pendingRequests = PurchaseRequest::with('user')->where('status', 'pending')->get();
        $confirmedRequests = PurchaseRequest::with(['user'])->where('status', 'confirmed')->get();

        // Group & sum manually per kategori & user
        $groupedTotals = [];

        foreach ($confirmedRequests as $item) {
            preg_match('/(\d+)/', $item->package_kg, $match);
            $kuota = isset($match[1]) ? (int) $match[1] : 0;

            $userId = $item->user_id;
            $userName = $item->user->name;
            $kategori = $item->package_category;

            // Kombinasi unik user + kategori
            $key = $userId . '_' . $kategori;

            if (!isset($groupedTotals[$key])) {
                $groupedTotals[$key] = [
                    'name' => $userName,
                    'kategori' => $kategori,
                    'total_kuota' => 0,
                    'total_harga' => 0
                ];
            }

            $groupedTotals[$key]['total_kuota'] += $kuota;
            $groupedTotals[$key]['total_harga'] += (int) $item->package_price;
        }

        return view('modul_admin.paket.konfirmasi_paket', compact('pendingRequests', 'confirmedRequests', 'groupedTotals'));
    }

    public function confirm($id)
    {
        $purchase = PurchaseRequest::findOrFail($id);

        if ($purchase->status !== 'pending') {
            return back()->with('error', 'Permintaan sudah dikonfirmasi sebelumnya.');
        }

        preg_match('/(\d+)/', $purchase->package_kg, $match);
        $kuotaTambah = isset($match[1]) ? (int) $match[1] : 0;

        $kategori = $purchase->package_category;

        $kuota = KuotaLaundry::firstOrNew([
            'user_id' => $purchase->user_id,
            'kategori' => $kategori,
        ]);

        $kuota->kuota = ($kuota->kuota ?? 0) + $kuotaTambah;
        $kuota->save();

        $purchase->status = 'confirmed';
        $purchase->save();

        // ✅ Tambahkan ke pemasukan
        Pemasukan::create([
            'pemasukan' => $purchase->user->name,
            'kategori' => 'Paket (' . $purchase->package_category . ')',
            'harga' => $purchase->package_price,
            'jumlah' => $kuotaTambah,
            'total' => $purchase->package_price,
        ]);

        // Kirim notifikasi ke customer
        $purchase->user->notify(new PaketDikonfirmasiNotification($purchase));

        return back()->with('success', 'Pembelian berhasil dikonfirmasi dan kuota (' . $kuotaTambah . ' kg) ditambahkan untuk kategori ' . $kategori . '.');
    }

    public function destroy($id)
    {
        $purchase = PurchaseRequest::findOrFail($id);

        if ($purchase->status !== 'pending') {
            return back()->with('error', 'Hanya permintaan yang menunggu konfirmasi yang bisa dihapus.');
        }

        $purchase->delete();

        return back()->with('success', 'Permintaan berhasil dihapus.');
    }
}
