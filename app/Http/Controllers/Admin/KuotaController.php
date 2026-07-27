<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KuotaLaundry;
use App\Models\KuotaLaundryLog;
use App\Models\Paket;
use App\Models\Pemasukan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(function () use ($request) {
            $existing = KuotaLaundry::where('user_id', $request->user_id)
                ->where('kategori', $request->kategori)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                $sebelum = (float) $existing->kuota;
                $existing->kuota += $request->kuota;
                $existing->save();

                // Log penambahan dari admin
                KuotaLaundryLog::create([
                    'user_id'       => $request->user_id,
                    'kategori'      => $request->kategori,
                    'tipe'          => 'penambahan_admin',
                    'kuota_sebelum' => $sebelum,
                    'perubahan'     => (float) $request->kuota,
                    'kuota_sesudah' => (float) $existing->kuota,
                    'keterangan'    => 'Penambahan kuota oleh admin',
                    'created_by'    => Auth::id(),
                ]);
            } else {
                $kuota = KuotaLaundry::create([
                    'user_id' => $request->user_id,
                    'kuota' => $request->kuota,
                    'kategori' => $request->kategori,
                ]);

                KuotaLaundryLog::create([
                    'user_id'       => $request->user_id,
                    'kategori'      => $request->kategori,
                    'tipe'          => 'kuota_awal',
                    'kuota_sebelum' => 0,
                    'perubahan'     => (float) $request->kuota,
                    'kuota_sesudah' => (float) $kuota->kuota,
                    'keterangan'    => 'Kuota baru dari admin',
                    'created_by'    => Auth::id(),
                ]);
            }

            // Catat pemasukan tetap di luar log kuota
            $user = User::find($request->user_id);
            Pemasukan::create([
                'pemasukan' => $user->name,
                'kategori' => 'Kuota (' . $request->kategori . ')',
                'harga' => $request->harga,
                'jumlah' => $request->kuota,
                'total' => $request->harga - ($request->diskon ?? 0),
                'keterangan' => 'Diskon: ' . ($request->diskon ?? 0) . '. ' . $request->keterangan,
                'tanggal' => date('d-m-Y'),
            ]);
        });

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

        DB::transaction(function () use ($request, $id) {
            $kuota = KuotaLaundry::lockForUpdate()->findOrFail($id);

            $sebelum = (float) $kuota->kuota;
            $sesudah = (float) $request->kuota;

            $kuota->kuota = $request->kuota;
            $kuota->kategori = $request->kategori;
            $kuota->save();

            if ($sebelum != $sesudah) {
                KuotaLaundryLog::create([
                    'user_id'       => $kuota->user_id,
                    'kategori'      => $kuota->kategori,
                    'tipe'          => 'koreksi_admin',
                    'kuota_sebelum' => $sebelum,
                    'perubahan'     => $sesudah - $sebelum,
                    'kuota_sesudah' => $sesudah,
                    'keterangan'    => 'Koreksi kuota oleh admin',
                    'created_by'    => Auth::id(),
                ]);
            }
        });

        return redirect('kuota')->with('success', 'Kuota dan kategori berhasil diperbarui.');
    }
}
