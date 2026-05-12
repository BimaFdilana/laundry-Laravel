<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function index()
    {
        $admin = User::where('auth', 'Admin')->orderBy('name', 'asc')->get();
        return view('superadmin.admin.index', compact('admin'));
    }

    public function create()
    {
        return view('superadmin.admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email',
            'alamat'  => 'nullable|string',
            'no_telp' => 'nullable|string',
            'kelamin' => 'nullable|string',
        ]);

        $adduser = new User();
        $adduser->name     = $request->name;
        $adduser->email    = $request->email;
        $adduser->alamat   = $request->alamat;
        $adduser->no_telp  = $request->no_telp;
        $adduser->kelamin  = $request->kelamin;
        $adduser->status   = 'Active';
        $adduser->auth     = 'Admin';
        $adduser->password = bcrypt('123456');
        $adduser->save();

        $adduser->assignRole($adduser->auth);

        Session::flash('success', 'Tambah Admin Berhasil');
        return redirect()->route('kelola-admin.index');
    }

    public function show($id)
    {
        $admin = User::findOrFail($id);
        return view('superadmin.admin.show', compact('admin'));
    }

    public function edit($id)
    {
        $edit = User::findOrFail($id);
        return view('superadmin.admin.edit', compact('edit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'alamat'  => 'nullable|string',
            'no_telp' => 'nullable|string',
            'kelamin' => 'nullable|string',
            'status'  => 'required|string',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'alamat'   => $request->alamat,
            'no_telp'  => $request->no_telp,
            'kelamin'  => $request->kelamin,
            'status'   => $request->status,
        ]);

        Session::flash('success', 'Update Admin Berhasil');
        return redirect()->route('kelola-admin.index');
    }

    public function destroy($id)
    {
        $delete = User::findOrFail($id);
        if ($delete->status == 'Active') {
            Session::flash('error', 'Error, Status Admin masih aktif');
        } else {
            $delete->delete();
            Session::flash('success', 'Hapus Admin Berhasil');
        }
        return redirect()->route('kelola-admin.index');
    }
}
