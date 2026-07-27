<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Diskon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DiskonController extends Controller
{
    public function index()
    {
        $diskons = Diskon::orderByDesc('created_at')->get();
        return view('superadmin.diskon.index', compact('diskons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:diskons,kode',
            'nama' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|boolean'
        ]);

        Diskon::create([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'nominal' => $request->nominal,
            'status' => $request->status
        ]);

        Session::flash('success', 'Tambah Voucher Diskon Berhasil');
        return redirect()->route('diskon.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|unique:diskons,kode,' . $id,
            'nama' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'status' => 'required|boolean'
        ]);

        $diskon = Diskon::findOrFail($id);
        $diskon->update([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'nominal' => $request->nominal,
            'status' => $request->status
        ]);

        Session::flash('success', 'Update Voucher Diskon Berhasil');
        return redirect()->route('diskon.index');
    }

    public function destroy($id)
    {
        $diskon = Diskon::findOrFail($id);
        $diskon->delete();

        Session::flash('success', 'Voucher Diskon berhasil dihapus!');
        return redirect()->back();
    }
}
