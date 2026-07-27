<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaundrySetting;
use Illuminate\Support\Facades\Session;

class TargetLaundryController extends Controller
{
    public function index()
    {
        $targetLaundry = LaundrySetting::first() ?? LaundrySetting::create([
            'target_day' => 0,
            'target_month' => 0,
            'target_year' => 0
        ]);

        return view('superadmin.target_laundry.index', compact('targetLaundry'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'target_day' => 'required|numeric|min:0',
            'target_month' => 'required|numeric|min:0',
            'target_year' => 'required|numeric|min:0',
        ]);

        $target = LaundrySetting::findOrFail($id);
        $target->target_day = $request->target_day;
        $target->target_month = $request->target_month;
        $target->target_year = $request->target_year;
        $target->save();

        Session::flash('success', 'Target Laundry berhasil diperbarui!');
        return redirect()->route('superadmin.target-laundry.index');
    }
}
