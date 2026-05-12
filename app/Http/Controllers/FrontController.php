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
      $search = Transaksi::where('invoice', $request->search_status);
      if ($search->count() == 0) {
          $return = 0;
        }else{
          $return = $search->first();
        }
        return $return;
  }
}
