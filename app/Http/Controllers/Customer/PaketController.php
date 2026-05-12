<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        // Kelompokkan berdasarkan kategori
        $paketGrouped = Paket::all()->groupBy('kategori');

        return view('customer.paket', compact('paketGrouped'));
    }
}
