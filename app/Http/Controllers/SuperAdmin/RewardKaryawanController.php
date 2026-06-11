<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\RewardKaryawan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RewardKaryawanController extends Controller
{
    public function index(Request $request)
    {
        $karyawan_id = $request->karyawan_id;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $query = RewardKaryawan::with('karyawan')->orderByDesc('tanggal');

        if ($karyawan_id) {
            $query->where('karyawan_id', $karyawan_id);
        }
        if ($bulan) {
            $query->whereMonth('tanggal', $bulan);
        }
        if ($tahun) {
            $query->whereYear('tanggal', $tahun);
        }

        $rewards = $query->get();
        $karyawans = Karyawan::all();

        $totalReward = $rewards->sum('nominal');

        $rekapKaryawan = RewardKaryawan::selectRaw('karyawan_id, SUM(nominal) as total, COUNT(*) as jumlah')
            ->groupBy('karyawan_id')
            ->with('karyawan')
            ->orderByDesc('total')
            ->get();

        return view('superadmin.reward-karyawan.index', compact(
            'rewards',
            'karyawans',
            'totalReward',
            'rekapKaryawan',
            'karyawan_id',
            'bulan',
            'tahun'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'jenis_reward' => 'required|string|max:100',
            'nominal' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date',
        ]);

        RewardKaryawan::create($request->all());

        return redirect()->route('superadmin.reward-karyawan.index')->with('success', 'Reward karyawan berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        RewardKaryawan::findOrFail($id)->delete();
        return redirect()->route('superadmin.reward-karyawan.index')->with('success', 'Reward berhasil dihapus.');
    }
}
