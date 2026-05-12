<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Transaksi;
use App\Models\TransaksiSatuan;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        // Ambil data transaksi
        $order = Transaksi::with(['price', 'karyawan'])->orderBy('id', 'DESC')->get();

        // Ambil semua karyawan
        $karyawans = Karyawan::all();

        return view('superadmin.transaksi.order', compact('order', 'karyawans'));
    }

    public function satuan()
    {
        // Ambil data transaksi satuan
        $ordersatuan = TransaksiSatuan::with(['karyawan'])->orderBy('id', 'DESC')->get();

        // Ambil semua karyawan
        $karyawans = Karyawan::all();

        return view('superadmin.transaksi.ordersatuan', compact('ordersatuan', 'karyawans'));
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $transaksi->delete();

        return redirect()->route('superadmin.transaksi')->with('success', 'Transaksi berhasil dihapus.');
    }

    public function destroysatuan($id)
    {
        $transaksisatuan = TransaksiSatuan::findOrFail($id);

        // Hapus semua detail terkait terlebih dahulu
        $transaksisatuan->details()->delete(); // Asumsikan relasi bernama 'details'

        // Hapus transaksi utamanya
        $transaksisatuan->delete();

        return redirect()->route('superadmin.transaksisatuan')->with('success', 'Transaksi satuan beserta detailnya berhasil dihapus.');
    }
}
