<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, LaundrySetting, TargetFinance};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
    public function setting()
    {
        $settarget  = LaundrySetting::first();
        $targetFinance = TargetFinance::first();

        return view('superadmin.setting.index', compact('settarget', 'targetFinance'));
    }

    // Check Setting Theme
    public function set_theme(Request $request)
    {
        $id = Auth::id();
        $user = User::all();

        $set_theme = User::findOrFail($id);
        if ($request->theme == NULL) {
            $set_theme->theme = '0';
        } else {
            $set_theme->theme = $request->theme;
        }

        $set_theme->save();

        Session::flash('success', 'Setting Berhasil Disimpan !');
        return back();
    }

    // Setting Laundry Target
    public function set_target_laundry(Request $request, $id)
    {
        $set_target = LaundrySetting::findOrFail($id);
        $set_target->target_day = $request->target_day;
        $set_target->target_month = $request->target_month;
        $set_target->target_year = $request->target_year;
        $set_target->save();

        Session::flash('success', 'Target Berhasil Diupdate!');
        return back();
    }

    public function update_target_finance(Request $request, $id)
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

        Session::flash('success', 'Target Finance berhasil diperbarui!');
        return back();
    }
}
