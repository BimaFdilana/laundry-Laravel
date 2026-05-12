<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Harga, Transaksi, TransaksiSatuan};
use Rupiah;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaksiBiasa = Transaksi::with('price')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('modul_admin.transaksi.index', compact('transaksiBiasa'));
    }

    public function indexsatuan()
    {
        $transaksiSatuan = TransaksiSatuan::with('details')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('modul_admin.transaksi.index_satuan', compact('transaksiSatuan'));
    }

    /**
     * Menampilkan detail invoice untuk transaksi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        return view('modul_admin.transaksi.invoice', compact('invoice', 'dataInvoice'));
    }

    public function print($id)
    {
        $transaksi = Transaksi::with(['price', 'karyawan'])->findOrFail($id);

        $berat = $transaksi->kg;
        $hargaObj = Harga::findOrFail($transaksi->harga_id);
        $total_harga = $berat * $hargaObj->harga;

        return view('modul_admin.transaksi.print', compact('transaksi', 'total_harga'));
    }

    public function printInvoice($id)
    {
        $dataInvoice = Transaksi::with(['price', 'customers'])->findOrFail($id);

        $berat = $dataInvoice->kg;
        $hargaObj = Harga::findOrFail($dataInvoice->harga_id);
        $total_harga = $berat * $hargaObj->harga;

        return view('modul_admin.transaksi.print_invoice', compact('dataInvoice', 'total_harga'));
    }
}
