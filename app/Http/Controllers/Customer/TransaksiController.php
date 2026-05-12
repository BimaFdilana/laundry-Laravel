<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiSatuan;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Pastikan pengguna sudah login
    }

    public function index()
    {
        $order = Transaksi::where('customer_id', Auth::user()->id)
            ->with('karyawan')
            ->get();

        $order_satuan = TransaksiSatuan::where('customer_id', Auth::user()->id)
            ->with(['karyawan', 'details'])
            ->get();

        return view('customer.transaksi.index', compact('order', 'order_satuan'));
    }

    public function hideTransaction($id)
    {
        $transaksi = Transaksi::where('id', $id)->where('customer_id', auth()->id())->firstOrFail();
        $transaksi->is_hidden_customer = true;
        $transaksi->save();

        return back()->with('success', 'Transaksi berhasil disembunyikan.');
    }
}
