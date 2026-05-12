<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use App\Models\Kategori;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventaris = Inventaris::with('kategori')
            ->orderBy('nama_barang', 'asc')
            ->get();
        return view('superadmin.inventaris.index', compact('inventaris'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kategori = Kategori::all();
        return view('superadmin.inventaris.create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jenis'       => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan'      => 'required|string|max:50',
            'stok'        => 'required|integer|min:0',
            'harga'       => 'required|numeric|min:0',
            'kondisi'     => 'required|string|max:100',
        ]);

        Inventaris::create($request->all());

        return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        $kategori = Kategori::all();
        return view('superadmin.inventaris.edit', compact('inventaris', 'kategori'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $inventaris = Inventaris::findOrFail($id);
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jenis'       => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan'      => 'required|string|max:50',
            'stok'        => 'required|integer|min:0',
            'harga'       => 'required|numeric|min:0',
            'kondisi'     => 'required|string|max:100',
        ]);

        $inventaris->update($request->all());
        return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        $inventaris->delete();
        return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil dihapus.');
    }
}
