<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\BintangKaryawan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BintangKaryawanController extends Controller
{
    public function index()
    {
        $bintangKaryawan = BintangKaryawan::with('karyawan')->get();
        $karyawan = Karyawan::all();

        // Rata-rata bintang per bulan per karyawan
        $rataBintangPerBulanPerKaryawan = BintangKaryawan::select(
            'karyawan_id',
            DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as bulan"),
            DB::raw('AVG(bintang) as rata_rata')
        )
            ->groupBy('karyawan_id', 'bulan')
            ->orderBy('bulan', 'desc')
            ->with('karyawan')
            ->get();

        return view('superadmin.karyawan.bintang', compact('bintangKaryawan', 'karyawan', 'rataBintangPerBulanPerKaryawan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'bintang' => 'required|integer|min:1|max:5',
            'tanggal' => 'required|date'
        ]);

        BintangKaryawan::create([
            'karyawan_id' => $request->karyawan_id,
            'bintang' => $request->bintang,
            'tanggal' => $request->tanggal
        ]);

        return redirect()->back()->with('success', 'Bintang karyawan berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $item = BintangKaryawan::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Data bintang karyawan berhasil dihapus.');
    }
}
