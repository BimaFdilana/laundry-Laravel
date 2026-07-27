<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KuotaLaundry;
use App\Models\KuotaLaundryLog;
use App\Models\Paket;
use App\Models\Pemasukan;
use App\Models\User;
use App\Models\Diskon;
use Carbon\Carbon;

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
        $diskons = Diskon::where('status', 1)->get();
        return view('modul_admin.customer.create_kuota', compact('customers', 'pakets', 'diskons'));
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

        // Catat log penambahan kuota
        KuotaLaundryLog::create([
            'user_id' => $request->user_id,
            'tipe' => 'penambahan',
            'jumlah' => $request->kuota,
            'kategori' => $request->kategori,
            'keterangan' => 'Pembelian/penambahan kuota baru. ' . $request->keterangan,
        ]);

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
        $selisih = $request->kuota - $kuota->kuota;

        if ($selisih != 0) {
            KuotaLaundryLog::create([
                'user_id' => $kuota->user_id,
                'tipe' => $selisih > 0 ? 'penambahan' : 'penggunaan',
                'jumlah' => abs($selisih),
                'kategori' => $request->kategori,
                'keterangan' => 'Pembaruan kuota manual oleh Admin.',
            ]);
        }

        $kuota->kuota = $request->kuota;
        $kuota->kategori = $request->kategori;
        $kuota->save();

        return redirect('kuota')->with('success', 'Kuota dan kategori berhasil diperbarui.');
    }

    public function history($userId)
    {
        $logs = KuotaLaundryLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($log) {
                return [
                    'tipe' => ucfirst($log->tipe),
                    'jumlah' => (float)$log->jumlah . ' kg',
                    'kategori' => $log->kategori,
                    'invoice' => $log->invoice,
                    'invoice_url' => $log->invoice ? route('customer.invoice', $log->invoice) : null,
                    'keterangan' => $log->keterangan,
                    'waktu' => Carbon::parse($log->created_at)->translatedFormat('l, d F Y H:i')
                ];
            });

        return response()->json($logs);
    }
}
