<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Transaksi;
use App\Models\TransaksiSatuan;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['price', 'karyawan'])->orderBy('id', 'DESC');

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereDate('created_at', '>=', $request->dari)
                  ->whereDate('created_at', '<=', $request->sampai);
        }

        if ($request->filled('status_payment')) {
            $query->where('status_payment', $request->status_payment);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice', 'LIKE', "%{$search}%")
                  ->orWhere('customer', 'LIKE', "%{$search}%");
            });
        }

        $order = $query->paginate(50)->appends($request->query());
        $karyawans = Karyawan::all();

        return view('superadmin.transaksi.order', compact('order', 'karyawans'));
    }

    public function satuan(Request $request)
    {
        $query = TransaksiSatuan::with(['karyawan', 'details.satuan'])->orderBy('id', 'DESC');

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereDate('created_at', '>=', $request->dari)
                  ->whereDate('created_at', '<=', $request->sampai);
        }

        if ($request->filled('status_payment')) {
            $query->where('status_payment', $request->status_payment);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice', 'LIKE', "%{$search}%")
                  ->orWhere('customer', 'LIKE', "%{$search}%");
            });
        }

        $ordersatuan = $query->paginate(50)->appends($request->query());
        $karyawans = Karyawan::all();

        return view('superadmin.transaksi.ordersatuan', compact('ordersatuan', 'karyawans'));
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $transaksi->delete();

        return redirect()->route('superadmin.transaksi')->with('success', 'Transaksi berhasil dihapus.');
    }

    public function destroysatuan($id)
    {
        $transaksisatuan = TransaksiSatuan::findOrFail($id);

        // Hapus semua detail terkait terlebih dahulu
        $transaksisatuan->details()->delete(); // Asumsikan relasi bernama 'details'

        // Hapus transaksi utamanya
        $transaksisatuan->delete();

        return redirect()->route('superadmin.transaksisatuan')->with('success', 'Transaksi satuan beserta detailnya berhasil dihapus.');
    }

    public function ubahstatusbayar(Request $request)
    {
        $transaksi = Transaksi::find($request->id);
        $transaksi->update([
            'status_payment' => $request->status_payment,
        ]);

        return redirect()->route('superadmin.transaksi')->with('success', 'Status Pembayaran Berhasil Diubah!');
    }

    public function ubahstatusbayarsatuan(Request $request)
    {
        $transaksi = TransaksiSatuan::find($request->id);
        $transaksi->update([
            'status_payment' => $request->status_payment,
        ]);

        return redirect()->route('superadmin.transaksisatuan')->with('success', 'Status Pembayaran Berhasil Diubah!');
    }
}
