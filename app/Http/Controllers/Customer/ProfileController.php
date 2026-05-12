<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    // Profile Customer Cabang
    public function customerProfile($id)
    {
        $user = User::with('customer')->findOrFail($id);
        return view('customer.profile.index', compact('user'));
    }

    // Profile Customer Cabang - Edit
    public function customerProfileEdit(Request $request, $id)
    {
        $edit = User::with('customer')->findOrFail($id);
        return view('customer.profile.edit', compact('edit'));
    }

    // Profile Customer Cabang - Save
    public function customerProfileSave(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'name'        => 'required|string',
            'email'       => 'required|email',
            'no_telp'     => 'required|string',
            'alamat'      => 'nullable|string',
            'kelamin'     => 'nullable|in:Laki-laki,Perempuan',
            'inisial'     => 'nullable|string',
            'tgl_lahir'   => 'nullable|date',
            'link_gmaps'  => 'nullable|url',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
        ]);

        // Temukan user
        $user = User::findOrFail($id);

        // Update data user
        $user->update([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'no_telp' => $validated['no_telp'],
            'alamat'  => $validated['alamat'] ?? null,
            'kelamin' => $validated['kelamin'] ?? null,
        ]);

        // Update atau buat data customer
        $user->customer()->updateOrCreate(
            ['user_id' => $user->id], // kolom pencocokan
            [
                'inisial'     => $validated['inisial'] ?? null,
                'tgl_lahir'   => $validated['tgl_lahir'] ?? null,
                'link_gmaps'  => $validated['link_gmaps'] ?? null,
                'latitude'    => $validated['latitude'] ?? null,
                'longitude'   => $validated['longitude'] ?? null,
            ]
        );

        alert()->success('Update Data Berhasil');
        return redirect('profile-customer/' . $user->id);
    }

    // Change Password Customer
    public function change_password(Request $request, $id)
    {
        $request->validate([
            'password'  => 'required|confirmed',
        ]);

        $change_password = User::findOrFail($id);
        $change_password->password = bcrypt($request->password);
        $change_password->save();

        Session::flash('success', 'Password Berhasil Diubah !');
        return \redirect()->back();
    }
}
