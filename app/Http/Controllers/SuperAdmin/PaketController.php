<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Harga;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaketController extends Controller
{
    public function index()
    {
        $hargas = Harga::get();
        $paket = Paket::get();
        return view('superadmin.paket.paket', compact('hargas', 'paket'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kg' => 'required',
            'harga' => 'required|numeric',
            'kategori' => 'required',
        ]);

        $addpaket = new Paket();
        $addpaket->kg = $request->kg;
        $addpaket->harga = $request->harga;
        $addpaket->kategori = $request->kategori;
        $addpaket->save();

        Session::flash('success', 'Tambah Data Paket Berhasil');
        return redirect()->route('paket.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kg' => 'required',
            'harga' => 'required|numeric',
            'kategori' => 'required',
        ]);

        $paket = Paket::findOrFail($id);
        $paket->update([
            'kg' => $request->kg,
            'harga' => $request->harga,
            'kategori' => $request->kategori,
        ]);

        Session::flash('success', 'Edit Data Paket Berhasil');
        return redirect()->route('paket.index');
    }

    public function destroy($id)
    {
        $paket = Paket::findOrFail($id);
        $paket->delete();

        return redirect()->back()->with('success', 'Data paket berhasil dihapus!');
    }
}
