<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiSatuan;

class InvoiceController extends Controller
{
    public function invoice(Request $request)
    {
        // Ambil semua transaksi dengan invoice tertentu
        $invoice = Transaksi::with('price')
            ->where('invoice', $request->invoice)
            ->orderBy('id', 'DESC')
            ->get();

        // Ambil transaksi utama untuk data invoice
        $dataInvoice = Transaksi::with('customers')
            ->where('invoice', $request->invoice)
            ->first();

        // Debug untuk memeriksa data
        if (!$dataInvoice) {
            return back()->with('error', 'Data invoice tidak ditemukan.');
        }

        return view('customer.invoice', compact('invoice', 'dataInvoice'));
    }

    public function invoicesatuan(Request $request)
    {
        $dataInvoice = TransaksiSatuan::with('details', 'customers')
            ->where('invoice', $request->invoice)
            ->first();

        // Debug untuk memeriksa data
        if (!$dataInvoice) {
            return back()->with('error', 'Data invoice tidak ditemukan.');
        }

        return view('customer.invoicesatuan', compact('dataInvoice'));
    }
}
