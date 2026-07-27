<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TargetFinance;
use Illuminate\Support\Facades\Session;

class TargetFinanceController extends Controller
{
    public function index()
    {
        $targetFinance = TargetFinance::first() ?? TargetFinance::create([
            'target_hari' => 0,
            'target_bulan' => 0,
            'target_tahun' => 0,
            'tahun' => now()->year
        ]);

        return view('superadmin.target_finance.index', compact('targetFinance'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'target_tahun' => 'required|numeric|min:0',
            'target_bulan' => 'required|numeric|min:0',
            'target_hari'  => 'required|numeric|min:0',
        ]);

        $target = TargetFinance::findOrFail($id);
        $target->target_tahun = $request->target_tahun;
        $target->target_bulan = $request->target_bulan;
        $target->target_hari = $request->target_hari;
        $target->save();

        Session::flash('success', 'Target Keuangan berhasil diperbarui!');
        return redirect()->route('superadmin.target-finance.index');
    }
}
