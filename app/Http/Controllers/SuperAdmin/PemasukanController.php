<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Pemasukan;
use App\Models\Transaksi;
use App\Models\TransaksiSatuan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    // Tampilkan semua pemasukan
    public function index(Request $request)
    {
        $hari = $request->hari;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $pemasukanQuery = Pemasukan::query()->orderByDesc('created_at');
        $regulerQuery = Transaksi::query()
            ->whereIn('status_payment', ['Success', 'Pending'])
            ->orderByDesc('created_at');

        $satuanQuery = TransaksiSatuan::query()
            ->whereIn('status_payment', ['Success', 'Pending'])
            ->orderByDesc('created_at');

        // Filter data
        if ($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun')) {
            $pemasukanQuery->where(function ($query) use ($hari, $bulan, $tahun) {
                $query->where(function ($q) use ($hari, $bulan, $tahun) {
                    $q->whereDay('tanggal', $hari)
                        ->whereMonth('tanggal', $bulan)
                        ->whereYear('tanggal', $tahun);
                })->orWhere(function ($q) use ($hari, $bulan, $tahun) {
                    $q->whereNull('tanggal')
                        ->whereDay('created_at', $hari)
                        ->whereMonth('created_at', $bulan)
                        ->whereYear('created_at', $tahun);
                });
            });
            $regulerQuery->whereDay('created_at', $hari)->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
            $satuanQuery->whereDay('created_at', $hari)->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $pemasukanQuery->where(function ($query) use ($bulan, $tahun) {
                $query->where(function ($q) use ($bulan, $tahun) {
                    $q->whereMonth('tanggal', $bulan)
                        ->whereYear('tanggal', $tahun);
                })->orWhere(function ($q) use ($bulan, $tahun) {
                    $q->whereNull('tanggal')
                        ->whereMonth('created_at', $bulan)
                        ->whereYear('created_at', $tahun);
                });
            });
            $regulerQuery->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
            $satuanQuery->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
        } elseif ($request->filled('tahun')) {
            $pemasukanQuery->where(function ($query) use ($tahun) {
                $query->whereYear('tanggal', $tahun)
                    ->orWhere(function ($q) use ($tahun) {
                        $q->whereNull('tanggal')
                            ->whereYear('created_at', $tahun);
                    });
            });
            $regulerQuery->whereYear('created_at', $tahun);
            $satuanQuery->whereYear('created_at', $tahun);
        }

        // Data pemasukan manual
        $pemasukanManual = $pemasukanQuery->get()->map(function ($item) {
            $kategori = 'Manual';
            if (Str::contains(strtolower($item->kategori), 'kuota') || Str::contains(strtolower($item->kategori), 'paket')) {
                $kategori = 'Kuota/Paket';
            }

            return [
                'id' => $item->id,
                'tipe' => 'manual',
                'sumber' => $kategori . ' = ' . $item->pemasukan,
                'keterangan' => $item->keterangan,
                'jumlah' => (int) $item->jumlah . ' kg',
                'total' => (int) $item->total,
                'tanggal' => $item->tanggal ?? $item->created_at,
            ];
        });

        // Transaksi reguler
        $pemasukanReguler = $regulerQuery->get()->map(function ($item) {
            return [
                'sumber' => 'Transaksi Reguler',
                'keterangan' => 'Order #' . $item->id,
                'jumlah' => (int) $item->kg . ' kg',
                'total' => (int) $item->harga_akhir,
                'status_payment' => $item->status_payment,
                'tanggal' => $item->created_at,
            ];
        });

        // Transaksi satuan
        $pemasukanSatuan = $satuanQuery->get()->map(function ($item) {
            return [
                'sumber' => 'Transaksi Satuan',
                'keterangan' => 'Order #' . $item->id,
                'jumlah' => $item->details->sum('pcs') . ' pcs',
                'total' => (int) $item->harga_akhir,
                'status_payment' => $item->status_payment,
                'tanggal' => $item->created_at,
            ];
        });

        $pemasukan = collect()
            ->merge($pemasukanManual)
            ->merge($pemasukanReguler)
            ->merge($pemasukanSatuan)
            ->sortByDesc('tanggal')
            ->values();

        $total = $pemasukan->sum('total');

        // Pembelian Kuota dari PurchaseRequest
        $purchaseKuotaQuery = \App\Models\PurchaseRequest::where('status', 'confirmed')->with('user')->orderByDesc('created_at');

        if ($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun')) {
            $purchaseKuotaQuery->whereDay('created_at', $hari)->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $purchaseKuotaQuery->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
        } elseif ($request->filled('tahun')) {
            $purchaseKuotaQuery->whereYear('created_at', $tahun);
        }

        $purchaseKuotaList = $purchaseKuotaQuery->get();

        // Kuota manual (pakai fallback jika tanggal null)
        $kuotaListQuery = Pemasukan::where(function ($q) {
            $q->where('kategori', 'LIKE', '%kuota%')->orWhere('kategori', 'LIKE', '%paket%');
        })->orderByDesc('created_at');

        if ($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun')) {
            $kuotaListQuery->where(function ($query) use ($hari, $bulan, $tahun) {
                $query->where(function ($q) use ($hari, $bulan, $tahun) {
                    $q->whereDay('tanggal', $hari)
                        ->whereMonth('tanggal', $bulan)
                        ->whereYear('tanggal', $tahun);
                })->orWhere(function ($q) use ($hari, $bulan, $tahun) {
                    $q->whereNull('tanggal')
                        ->whereDay('created_at', $hari)
                        ->whereMonth('created_at', $bulan)
                        ->whereYear('created_at', $tahun);
                });
            });
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $kuotaListQuery->where(function ($query) use ($bulan, $tahun) {
                $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
                    ->orWhere(function ($q) use ($bulan, $tahun) {
                        $q->whereNull('tanggal')->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
                    });
            });
        } elseif ($request->filled('tahun')) {
            $kuotaListQuery->where(function ($query) use ($tahun) {
                $query->whereYear('tanggal', $tahun)
                    ->orWhere(function ($q) use ($tahun) {
                        $q->whereNull('tanggal')->whereYear('created_at', $tahun);
                    });
            });
        }

        $kuotaList = $kuotaListQuery->get();

        // Total Pemasukan Manual
        $totalPemasukanManualQuery = Pemasukan::query();

        if ($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun')) {
            $totalPemasukanManualQuery->where(function ($query) use ($hari, $bulan, $tahun) {
                $query->whereDay('tanggal', $hari)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
                    ->orWhere(function ($q) use ($hari, $bulan, $tahun) {
                        $q->whereNull('tanggal')->whereDay('created_at', $hari)->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
                    });
            });
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $totalPemasukanManualQuery->where(function ($query) use ($bulan, $tahun) {
                $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
                    ->orWhere(function ($q) use ($bulan, $tahun) {
                        $q->whereNull('tanggal')->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
                    });
            });
        } elseif ($request->filled('tahun')) {
            $totalPemasukanManualQuery->where(function ($query) use ($tahun) {
                $query->whereYear('tanggal', $tahun)
                    ->orWhere(function ($q) use ($tahun) {
                        $q->whereNull('tanggal')->whereYear('created_at', $tahun);
                    });
            });
        }

        // Filter yang bukan kuota/paket
        $totalPemasukanManual = $totalPemasukanManualQuery->get()
            ->filter(fn($item) => !Str::contains(strtolower($item->kategori ?? ''), 'kuota') && !Str::contains(strtolower($item->kategori ?? ''), 'paket'))
            ->sum(fn($item) => (int) $item->total);

        $totalKuotaLunas = $kuotaList->filter(function ($item) {
            return !Str::contains(strtolower($item->keterangan ?? ''), 'nyusul');
        })->sum('total');

        $totalKuotaPending = $kuotaList->filter(function ($item) {
            return Str::contains(strtolower($item->keterangan ?? ''), 'nyusul');
        })->sum('total');

        // Jika ada data tambahan dari purchaseKuotaList (yang semua dianggap lunas):
        $totalKuotaLunas += $purchaseKuotaList->sum('package_price');

        // Total Transaksi & Utang (FILTERED)
        $totalTransaksi = $regulerQuery->clone()->where('status_payment', 'Success')->sum('harga_akhir');
        $totalSatuan = $satuanQuery->clone()->where('status_payment', 'Success')->sum('harga_akhir');

        // Filter untuk utang
        $utangRegulerQuery = Transaksi::where('status_payment', 'Pending')->orderByDesc('created_at');
        $utangSatuanQuery = TransaksiSatuan::where('status_payment', 'Pending')->orderByDesc('created_at');

        if ($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun')) {
            $utangRegulerQuery->whereDay('created_at', $hari)->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
            $utangSatuanQuery->whereDay('created_at', $hari)->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $utangRegulerQuery->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
            $utangSatuanQuery->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun);
        } elseif ($request->filled('tahun')) {
            $utangRegulerQuery->whereYear('created_at', $tahun);
            $utangSatuanQuery->whereYear('created_at', $tahun);
        }

        $utangTransaksi = $utangRegulerQuery->sum('harga_akhir');
        $utangSatuan = $utangSatuanQuery->sum('harga_akhir');

        // Ambil list transaksi filtered untuk tabel
        $transaksiList = $regulerQuery->get(); // sudah terurut desc
        $satuanList = $satuanQuery->get();     // sudah terurut desc

        // Pisah Cash & Transfer (dari transaksi reguler + satuan)
        $metode = $request->metode;
        $allTrx = $transaksiList->map(function ($i) {
            return (object) [
                'invoice' => $i->invoice,
                'tanggal' => $i->created_at,
                'customer' => $i->customer,
                'total' => (int) $i->harga_akhir,
                'status_payment' => $i->status_payment,
                'jenis_pembayaran' => $i->jenis_pembayaran,
                'tipe' => 'Reguler',
            ];
        })->merge($satuanList->map(function ($i) {
            return (object) [
                'invoice' => $i->invoice,
                'tanggal' => $i->created_at,
                'customer' => $i->customer,
                'total' => (int) $i->harga_akhir,
                'status_payment' => $i->status_payment,
                'jenis_pembayaran' => $i->jenis_pembayaran,
                'tipe' => 'Satuan',
            ];
        }))->sortByDesc('tanggal')->values();

        $cashList = $allTrx->where('jenis_pembayaran', 'Tunai')->values();
        $transferList = $allTrx->where('jenis_pembayaran', 'Transfer')->values();

        // Filter berdasarkan metode pembayaran
        $showCash = $metode !== 'transfer';
        $showTransfer = $metode !== 'cash';

        $totalCashLunas = $cashList->where('status_payment', 'Success')->sum('total');
        $totalCashBelum = $cashList->where('status_payment', 'Pending')->sum('total');
        $totalTransferLunas = $transferList->where('status_payment', 'Success')->sum('total');
        $totalTransferBelum = $transferList->where('status_payment', 'Pending')->sum('total');

        $totalPemasukanBersih = $totalTransaksi + $totalSatuan + $totalKuotaLunas + $totalPemasukanManual;

        return view('superadmin.pemasukan.index', compact(
            'pemasukan',
            'total',
            'kuotaList',
            'purchaseKuotaList',
            'transaksiList',
            'satuanList',
            'cashList',
            'transferList',
            'totalCashLunas',
            'totalCashBelum',
            'totalTransferLunas',
            'totalTransferBelum',
            'showCash',
            'showTransfer',
            'metode',
            'utangTransaksi',
            'utangSatuan',
            'totalTransaksi',
            'totalSatuan',
            'totalKuotaLunas',
            'totalKuotaPending',
            'totalPemasukanBersih',
            'totalPemasukanManual',
        ));
    }

    // Tampilkan form tambah pemasukan
    public function create()
    {
        return view('superadmin.pemasukan.create');
    }

    // Simpan data pemasukan
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'     => 'required|string',
            'pemasukan'   => 'required|string|max:255',
            'kategori'    => 'required|string|max:100',
            'harga'       => 'required|numeric|min:0',
            'jumlah'      => 'required|integer|min:1',
            'keterangan'  => 'nullable|string',
        ]);

        Pemasukan::create($request->all());

        return redirect()->route('pemasukan.index')->with('success', 'Data pemasukan berhasil ditambahkan.');
    }

    // Tampilkan form edit
    public function edit(Pemasukan $pemasukan)
    {
        return view('superadmin.pemasukan.edit', compact('pemasukan'));
    }

    // Update data pemasukan
    public function update(Request $request, Pemasukan $pemasukan)
    {
        $request->validate([
            'tanggal'     => 'required|string',
            'pemasukan'   => 'required|string|max:255',
            'kategori'    => 'required|string|max:100',
            'harga'       => 'required|numeric|min:0',
            'jumlah'      => 'required|integer|min:1',
            'keterangan'  => 'nullable|string',
        ]);

        $pemasukan->update($request->all());

        return redirect()->route('pemasukan.index')->with('success', 'Data pemasukan berhasil diupdate.');
    }

    // Hapus data pemasukan
    public function destroy(Pemasukan $pemasukan)
    {
        $pemasukan->delete();
        return redirect()->route('pemasukan.index')->with('success', 'Data pemasukan berhasil dihapus.');
    }
}
