<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KuotaLaundryLog;
use App\Models\User;
use Illuminate\Http\Request;

class KuotaHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = KuotaLaundryLog::with(['user', 'creator', 'transaksi'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereDate('created_at', '>=', $request->dari)
                  ->whereDate('created_at', '<=', $request->sampai);
        }

        $logs = $query->paginate(50)->appends($request->query());

        $customers = User::where('auth', 'Customer')->orderBy('name')->get();
        $kategoris = KuotaLaundryLog::select('kategori')->distinct()->pluck('kategori');

        return view('modul_admin.customer.kuota_history', compact('logs', 'customers', 'kategoris'));
    }

    public function byCustomer($customerId, Request $request)
    {
        $customer = User::findOrFail($customerId);

        $query = KuotaLaundryLog::with(['creator', 'transaksi'])
            ->where('user_id', $customerId)
            ->orderBy('created_at', 'desc');

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereDate('created_at', '>=', $request->dari)
                  ->whereDate('created_at', '<=', $request->sampai);
        }

        $logs = $query->paginate(50)->appends($request->query());
        $kategoris = KuotaLaundryLog::where('user_id', $customerId)->select('kategori')->distinct()->pluck('kategori');

        return view('modul_admin.customer.kuota_history', compact('logs', 'customer', 'kategoris'));
    }
}
