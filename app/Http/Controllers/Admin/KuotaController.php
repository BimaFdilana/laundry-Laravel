<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KuotaLaundry;
use App\Models\Paket;
use App\Models\Pemasukan;
use App\Models\User;

class KuotaController extends Controller
{
    public function index()
    {
        $customer = User::where('auth', 'Customer')
            ->with('kuotaLaundry', 'customer')
            ->orderBy('name') // Mengurutkan berdasarkan nama customer
            ->get();

        $flatKuota = [];

        foreach ($customer as $item) {
            foreach ($item->kuotaLaundry as $kuota) {
                $flatKuota[] = [
                    'customer' => $item,
                    'kuota' => $kuota
                ];
            }
        }

        return view('modul_admin.customer.kuota', compact('customer', 'flatKuota'));
    }

    public function create()
    {
        $customers = User::where('auth', 'Customer')->get();
        $pakets = Paket::all();
        return view('modul_admin.customer.create_kuota', compact('customers', 'pakets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'kuota' => 'required',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $existing = KuotaLaundry::where('user_id', $request->user_id)
            ->where('kategori', $request->kategori)
            ->first();

        if ($existing) {
            $existing->kuota += $request->kuota;
            $existing->save();
        } else {
            KuotaLaundry::create([
                'user_id' => $request->user_id,
                'kuota' => $request->kuota,
                'kategori' => $request->kategori,
            ]);
        }

        $user = User::find($request->user_id);

        // Simpan ke pemasukan
        Pemasukan::create([
            'pemasukan' => $user->name,
            'kategori' => 'Kuota (' . $request->kategori . ')',
            'harga' => $request->harga,
            'jumlah' => $request->kuota,
            'total' => $request->harga - ($request->diskon ?? 0),
            'keterangan' => 'Diskon: ' . ($request->diskon ?? 0) . '. ' . $request->keterangan,
        ]);

        return redirect('kuota')->with('success', 'Kuota berhasil diperbarui.');
    }

    public function edit($id)
    {
        $kuota = KuotaLaundry::findOrFail($id);
        $pakets = Paket::all(); // tambahkan ini
        return view('modul_admin.customer.edit_kuota', compact('kuota', 'pakets'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kuota' => 'required',
            'kategori' => 'required|string|max:255'
        ]);

        $kuota = KuotaLaundry::findOrFail($id);
        $kuota->kuota = $request->kuota;
        $kuota->kategori = $request->kategori;
        $kuota->save();

        return redirect('kuota')->with('success', 'Kuota dan kategori berhasil diperbarui.');
    }
}
