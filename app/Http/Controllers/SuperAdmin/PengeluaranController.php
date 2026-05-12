<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    // Tampilkan semua pengeluaran
    public function index(Request $request)
    {
        $query = Pengeluaran::query();

        // Filter berdasarkan tanggal jika lengkap (hari, bulan, tahun)
        if ($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun')) {
            $query->whereDay('tanggal', $request->hari)
                ->whereMonth('tanggal', $request->bulan)
                ->whereYear('tanggal', $request->tahun);
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal', $request->bulan)
                ->whereYear('tanggal', $request->tahun);
        }

        $pengeluaran = $query->orderBy('tanggal', 'desc')->get();
        $total = $pengeluaran->sum('total');

        return view('superadmin.pengeluaran.index', compact('pengeluaran', 'total'));
    }

    // Tampilkan form tambah pengeluaran
    public function create()
    {
        return view('superadmin.pengeluaran.create');
    }

    // Simpan data pengeluaran
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'     => 'required|string',
            'pengeluaran' => 'required|string|max:255',
            'kategori'    => 'required|string|max:100',
            'harga'       => 'required|numeric|min:0',
            'jumlah'      => 'required|integer|min:1',
            'keterangan'  => 'nullable|string',
        ]);

        Pengeluaran::create($request->all());

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    // Tampilkan form edit
    public function edit(Pengeluaran $pengeluaran)
    {
        return view('superadmin.pengeluaran.edit', compact('pengeluaran'));
    }

    // Update data pengeluaran
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $request->validate([
            'tanggal'     => 'required|string',
            'pengeluaran' => 'required|string|max:255',
            'kategori'    => 'required|string|max:100',
            'harga'       => 'required|numeric|min:0',
            'jumlah'      => 'required|integer|min:1',
            'keterangan'  => 'nullable|string',
        ]);

        $pengeluaran->update($request->all());

        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil diupdate.');
    }

    // Hapus data pengeluaran
    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();
        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil dihapus.');
    }
}
