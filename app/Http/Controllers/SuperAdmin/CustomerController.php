<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Requests\AddCustomerRequest;
use App\Http\Controllers\Controller;
use App\Models\KuotaLaundry;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = User::where('auth', 'Customer')
            ->with('kuotaLaundry', 'customer')
            ->orderBy('name', 'asc')
            ->get();

        return view('superadmin.pengguna.customer', compact('customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('superadmin.pengguna.addcus');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCustomerRequest $request)
    {
        $adduser = new User();
        $adduser->name     = $request->name;
        $adduser->email    = $request->email;
        $adduser->alamat   = $request->alamat;
        $adduser->no_telp  = $request->no_telp;
        $adduser->kelamin  = $request->kelamin;
        $adduser->status   = 'Active';
        $adduser->auth     = 'Customer';
        $adduser->password = bcrypt('123456');
        $adduser->save();

        $adduser->assignRole($adduser->auth);

        // Tambahkan kuota jika checkbox dicentang
        if ($request->has('dapat_kuota')) {
            KuotaLaundry::create([
                'user_id' => $adduser->id,
                'kategori' => $request->kategori_kuota,
                'kuota' => 10
            ]);
        }

        Session::flash('success', 'Tambah Customer Berhasil');
        return redirect('supercustomer');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = User::with('customer')->findOrFail($id);
        return view('superadmin.pengguna.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit = User::with('customer')->findOrFail($id);
        return view('superadmin.pengguna.editcus', compact('edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'alamat'  => 'nullable|string',
            'no_telp' => 'nullable|string',
            'kelamin' => 'nullable|string',
            'status'  => 'required|string',
            // customer table validation
            'tgl_lahir' => 'nullable|date',
            'inisial'   => 'nullable|string|max:10',
            'link_gmaps' => 'nullable|string',
        ]);

        // Update tabel users
        $user = User::findOrFail($id);
        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'alamat'   => $request->alamat,
            'no_telp'  => $request->no_telp,
            'kelamin'  => $request->kelamin,
            'status'   => $request->status,
        ]);

        // Update tabel customer
        $customerData = [
            'tgl_lahir'  => $request->tgl_lahir,
            'inisial'    => $request->inisial,
            'link_gmaps' => $request->link_gmaps,
        ];

        if ($user->customer) {
            $user->customer->update($customerData);
        } else {
            $user->customer()->create($customerData);
        }

        Session::flash('success', 'Update Customer Berhasil');
        return redirect()->route('supercustomer.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = User::find($id);
        if ($delete->status == 'Active') {
            Session::flash('error', 'Error, Status Customer masih aktif');
        } else {
            $delete->delete();
            Session::flash('success', 'Hapus Customer Berhasil');
        }
        return redirect('supercustomer');
    }
}
