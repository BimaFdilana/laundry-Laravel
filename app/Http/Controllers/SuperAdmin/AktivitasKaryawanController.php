<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AktivitasKaryawan;
use App\Models\Karyawan;
use App\Models\Transaksi;
use App\Models\TransaksiSatuan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AktivitasKaryawanController extends Controller
{
    public function index(Request $request)
    {
        $karyawan_id = $request->karyawan_id;
        $tanggal = $request->tanggal;

        $query = AktivitasKaryawan::with(['karyawan', 'transaksi', 'transaksiSatuan'])
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_mulai');

        if ($karyawan_id) {
            $query->where('karyawan_id', $karyawan_id);
        }
        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        $aktivitas = $query->get();
        $karyawans = Karyawan::all();

        $transaksiList = Transaksi::whereIn('status_order', ['Antrian', 'Process'])
            ->orderByDesc('created_at')
            ->get();
        $satuanList = TransaksiSatuan::whereIn('status_order', ['Antrian', 'Process'])
            ->orderByDesc('created_at')
            ->get();

        return view('superadmin.aktivitas-karyawan.index', compact(
            'aktivitas',
            'karyawans',
            'transaksiList',
            'satuanList',
            'karyawan_id',
            'tanggal'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'jenis_aktivitas' => 'required|in:cuci,gosok,packing',
            'transaksi_type' => 'required|in:reguler,satuan',
            'transaksi_id' => 'required|integer',
            'jumlah_item' => 'nullable|integer|min:1',
        ]);

        $data = [
            'karyawan_id' => $request->karyawan_id,
            'jenis_aktivitas' => $request->jenis_aktivitas,
            'jumlah_item' => $request->jumlah_item,
            'tanggal' => Carbon::now()->toDateString(),
            'jam_mulai' => Carbon::now()->toTimeString(),
        ];

        if ($request->transaksi_type === 'reguler') {
            $data['transaksi_id'] = $request->transaksi_id;
        } else {
            $data['transaksi_satuan_id'] = $request->transaksi_id;
        }

        AktivitasKaryawan::create($data);

        return redirect()->route('superadmin.aktivitas-karyawan.index')->with('success', 'Aktivitas karyawan berhasil dicatat.');
    }

    public function selesai($id)
    {
        $aktivitas = AktivitasKaryawan::findOrFail($id);
        $aktivitas->jam_selesai = Carbon::now()->toTimeString();
        $aktivitas->save();

        return redirect()->route('superadmin.aktivitas-karyawan.index')->with('success', 'Aktivitas ditandai selesai.');
    }

    public function destroy($id)
    {
        AktivitasKaryawan::findOrFail($id)->delete();
        return redirect()->route('superadmin.aktivitas-karyawan.index')->with('success', 'Aktivitas berhasil dihapus.');
    }

    public function getByTransaksi(Request $request)
    {
        $transaksi_id = $request->transaksi_id;
        $type = $request->type;

        $query = AktivitasKaryawan::with('karyawan');

        if ($type === 'reguler') {
            $query->where('transaksi_id', $transaksi_id);
        } else {
            $query->where('transaksi_satuan_id', $transaksi_id);
        }

        return response()->json($query->get());
    }
}
