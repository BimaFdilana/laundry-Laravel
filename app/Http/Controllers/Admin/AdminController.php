<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    // Halaman admin
    public function adm()
    {
        $adm = User::where('auth', 'Admin')->get();
        return view('modul_admin.pengguna.admin', compact('adm'));
    }

    // Laporan
    public function jmlTransaksi(Request $request)
    {
        $jml = User::where('auth', 'Customer')->select(DB::raw('t.id, t.nama, t.alamat, t.kelamin, t.no_telp, a.kg'))
            ->from(DB::raw('(SELECT * from customers order by created_at DESC) t'))
            ->leftJoin('transaksis as a', 'a.customer_id', '=', 't.id')
            ->groupBy('t.id')
            ->get();

        return view('modul_admin.customer.jmltransaksi', compact('jml'));
    }

    // Profile
    public function profile()
    {
        $profile = User::where('id', Auth::id())->first();
        return view('modul_admin.setting.profile', compact('profile'));
    }

    // Proses edit profile
    public function edit_profile(Request $request)
    {
        $profile = User::find($request->id_profile);
        $profile->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        Session::flash('success', 'Update Profile Berhasil');
        return $profile;
    }

    public function changePassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user(); // Ambil data user yang sedang login

        // Cek apakah password lama yang dimasukkan sesuai
        if (!Hash::check($request->old_password, $user->password)) {
            throw ValidationException::withMessages([
                'old_password' => 'The provided password does not match our records.',
            ]);
        }

        // Update password dengan yang baru
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully!']);
    }
}
