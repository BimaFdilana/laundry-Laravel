<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Pemasukan;
use App\Models\Transaksi;
use App\Models\TransaksiSatuan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PiutangController extends Controller
{
    public function index(Request $request)
    {
        $customer = $request->customer;

        $regulerQuery = Transaksi::where('status_payment', 'Pending')
            ->orderByDesc('created_at');
        $satuanQuery = TransaksiSatuan::where('status_payment', 'Pending')
            ->orderByDesc('created_at');
        $paketQuery = Pemasukan::where(function ($q) {
                $q->where('kategori', 'LIKE', '%kuota%')
                    ->orWhere('kategori', 'LIKE', '%paket%');
            })
            ->where('keterangan', 'LIKE', '%nyusul%')
            ->orderByDesc('created_at');

        if ($customer) {
            $regulerQuery->where('customer', 'LIKE', '%' . $customer . '%');
            $satuanQuery->where('customer', 'LIKE', '%' . $customer . '%');
            $paketQuery->where('pemasukan', 'LIKE', '%' . $customer . '%');
        }

        $reguler = $regulerQuery->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'tipe' => 'reguler',
                'invoice' => $item->invoice,
                'customer' => $item->customer,
                'tanggal' => $item->created_at,
                'total' => (int) $item->harga_akhir,
                'jenis_pembayaran' => $item->jenis_pembayaran,
            ];
        });

        $satuan = $satuanQuery->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'tipe' => 'satuan',
                'invoice' => $item->invoice,
                'customer' => $item->customer,
                'tanggal' => $item->created_at,
                'total' => (int) $item->harga_akhir,
                'jenis_pembayaran' => $item->jenis_pembayaran,
            ];
        });

        $paket = $paketQuery->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'tipe' => 'paket',
                'invoice' => '-',
                'customer' => $item->pemasukan,
                'tanggal' => $item->tanggal ?? $item->created_at,
                'total' => (int) $item->total,
                'jenis_pembayaran' => '-',
            ];
        });

        $piutang = collect()
            ->merge($reguler)
            ->merge($satuan)
            ->merge($paket)
            ->sortByDesc('tanggal')
            ->values();

        $totalPiutang = $piutang->sum('total');

        $listCustomer = collect()
            ->merge(Transaksi::where('status_payment', 'Pending')->pluck('customer'))
            ->merge(TransaksiSatuan::where('status_payment', 'Pending')->pluck('customer'))
            ->merge(Pemasukan::where('keterangan', 'LIKE', '%nyusul%')->pluck('pemasukan'))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('superadmin.piutang.index', compact('piutang', 'totalPiutang', 'listCustomer', 'customer'));
    }

    public function bayar(Request $request, $tipe, $id)
    {
        if ($tipe === 'reguler') {
            $transaksi = Transaksi::findOrFail($id);
            $transaksi->status_payment = 'Success';
            $transaksi->info_pembayaran = 'Lunas (Bayar Full)';
            $transaksi->save();
        } elseif ($tipe === 'satuan') {
            $transaksi = TransaksiSatuan::findOrFail($id);
            $transaksi->status_payment = 'Success';
            $transaksi->info_pembayaran = 'Lunas (Bayar Full)';
            $transaksi->save();
        } elseif ($tipe === 'paket') {
            $pemasukan = Pemasukan::findOrFail($id);
            $keterangan = preg_replace('/nyusul/i', '', $pemasukan->keterangan ?? '');
            $pemasukan->keterangan = trim($keterangan, ' ,;-');
            if (empty($pemasukan->keterangan)) {
                $pemasukan->keterangan = 'Lunas (Bayar Full)';
            } else {
                $pemasukan->keterangan .= ' | Lunas (Bayar Full)';
            }
            $pemasukan->save();
        } else {
            return redirect()->back()->with('error', 'Tipe piutang tidak valid.');
        }

        return redirect()->route('superadmin.piutang.index')->with('success', 'Piutang berhasil dilunasi.');
    }
}
