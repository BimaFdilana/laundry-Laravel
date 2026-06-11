<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Transaksi,PageSettings,Harga,Paket,Satuan};

class FrontController extends Controller
{

  //Index
  public function index()
  {
    $setpage = PageSettings::first();
    $hargas = Harga::all();
    $pakets = Paket::all();
    $satuans = Satuan::all();

    return view('frontend.index', compact('setpage', 'hargas', 'pakets', 'satuans'));
  }

  //Search
  public function search(Request $request)
  {
      $invoice = trim($request->search_status);

      if (empty($invoice)) {
          return 0;
      }

      $search = Transaksi::whereRaw('LOWER(invoice) = ?', [strtolower($invoice)])->first();

      if ($search) {
          return $search;
      }

      $searchSatuan = \App\Models\TransaksiSatuan::whereRaw('LOWER(invoice) = ?', [strtolower($invoice)])->first();

      if ($searchSatuan) {
          return $searchSatuan;
      }

      return 0;
  }
}
