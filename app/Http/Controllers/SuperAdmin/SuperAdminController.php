<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\{User, Harga, Pemasukan, Pengeluaran, Satuan, TargetFinance, TransaksiSatuan};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SuperAdminController extends Controller
{
    // Profile
    public function profile()
    {
        $profile = User::where('id', Auth::id())->first();
        return view('superadmin.setting.profile', compact('profile'));
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

    // Data Finance
    public function finance(Request $request)
    {
        $today = Carbon::now();
        $hari = $request->hari;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Diskon Transaksi Kiloan (jumlahkan langsung dari field disc)
        $diskonKiloan = Transaksi::sum('disc');

        // Diskon Transaksi Satuan (jumlahkan langsung dari field disc)
        $diskonSatuan = TransaksiSatuan::sum('disc');

        // Diskon Kuota
        $diskonKuota = Pemasukan::where('kategori', 'LIKE', 'Kuota%')
            ->pluck('keterangan') // ambil hanya kolom keterangan
            ->map(function ($keterangan) {
                // cari angka setelah "Diskon:"
                preg_match('/Diskon:\s*(\d+)/i', $keterangan, $matches);
                return isset($matches[1]) ? (int) $matches[1] : 0;
            })
            ->sum();

        // Gabungan total diskon
        $totalDiskon = $diskonKiloan + $diskonSatuan + $diskonKuota;

        // Target pendapatan
        $targetFinance = TargetFinance::where('tahun', $today->year)->first();

        if ($targetFinance) {
            $targetTahun = $targetFinance->target_tahun;
            $targetBulan = $targetFinance->target_bulan;
            $targetHari = $targetFinance->target_hari;
        } else {
            // Default value jika data tidak ada
            $targetTahun = 0;
            $targetBulan = 0;
            $targetHari = 0;
        }

        // Transaksi Reguler (Sukses)
        $transaksiQuery = Transaksi::where('status_payment', 'Success');
        if ($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun')) {
            $transaksiQuery->whereDay('created_at', $hari)
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $transaksiQuery->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);
        } elseif ($request->filled('tahun')) {
            $transaksiQuery->whereYear('created_at', $tahun);
        }
        $transaksi = $transaksiQuery->get();
        $totalTransaksi = $transaksi->sum('harga_akhir');

        // Transaksi Satuan (Sukses)
        $satuanQuery = TransaksiSatuan::where('status_payment', 'Success');
        if ($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun')) {
            $satuanQuery->whereDay('created_at', $hari)
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $satuanQuery->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);
        } elseif ($request->filled('tahun')) {
            $satuanQuery->whereYear('created_at', $tahun);
        }
        $satuan = $satuanQuery->get();
        $totalSatuan = $satuan->sum('harga_akhir');

        // Purchase Kuota
        $purchaseKuotaList = \App\Models\PurchaseRequest::where('status', 'confirmed')
            ->orderByDesc('created_at')
            ->with('user')
            ->get();

        $totalPurchaseKuota = $purchaseKuotaList->sum('package_price');

        // Kuota manual
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

        $totalKuotaLunas = $kuotaList->filter(function ($item) {
            return !Str::contains(strtolower($item->keterangan ?? ''), 'nyusul');
        })->sum('total');

        // Jika ada data tambahan dari purchaseKuotaList (yang semua dianggap lunas):
        $totalKuotaLunas += $purchaseKuotaList->sum('package_price');

        // Total Pemasukan
        $totalPemasukanManualQuery = Pemasukan::query();

        if ($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun')) {
            $totalPemasukanManualQuery->whereDay('tanggal', $hari)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $totalPemasukanManualQuery->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        } elseif ($request->filled('tahun')) {
            $totalPemasukanManualQuery->whereYear('tanggal', $tahun);
        }

        // Hitung total pemasukan manual (selain kuota/paket)
        $totalPemasukanManual = $totalPemasukanManualQuery->get()
            ->filter(fn($item) => !Str::contains(strtolower($item->kategori ?? ''), 'kuota') && !Str::contains(strtolower($item->kategori ?? ''), 'paket'))
            ->sum(fn($item) => (int) $item->total);

        $totalPemasukanBersih = $totalTransaksi + $totalSatuan + $totalKuotaLunas + $totalPemasukanManual;

        // Pengeluaran
        $pengeluaranQuery = Pengeluaran::query();

        if ($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun')) {
            $pengeluaranQuery->whereDay('tanggal', $hari)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun);
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $pengeluaranQuery->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun);
        } elseif ($request->filled('tahun')) {
            $pengeluaranQuery->whereYear('tanggal', $tahun);
        }

        $pengeluaran = $pengeluaranQuery->sum('total');

        // Laba
        $labaBersih = $totalPemasukanBersih - $pengeluaran;

        // Hitung Utang Transaksi Reguler
        $utangTransaksi = Transaksi::where('status_payment', '!=', 'Success')->sum('harga_akhir');

        // Hitung Utang Transaksi Satuan
        $utangSatuan = TransaksiSatuan::where('status_payment', '!=', 'Success')->sum('harga_akhir');

        // Hitung Kuota yang belum lunas ("nyusul")
        $totalKuotaPending = $kuotaList->filter(function ($item) {
            return Str::contains(strtolower($item->keterangan ?? ''), 'nyusul');
        })->sum('total');

        // Gabungkan semua utang
        $totalUtang = $utangTransaksi + $utangSatuan + $totalKuotaPending;

        $utangRegulerQuery = Transaksi::where('status_payment', 'Pending')->orderByDesc('created_at');
        $utangSatuanQuery = TransaksiSatuan::where('status_payment', 'Pending')->orderByDesc('created_at');

        $utangReguler = $utangRegulerQuery->get();
        $utangSatuan = $utangSatuanQuery->get();
        $kuotaPending = $kuotaList->filter(function ($item) {
            return Str::contains(strtolower($item->keterangan ?? ''), 'nyusul');
        });

        // Diskon Transaksi Reguler
        $diskonTransaksiQuery = Transaksi::where('disc', '>', 0);
        if ($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun')) {
            $diskonTransaksiQuery->whereDay('created_at', $hari)
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $diskonTransaksiQuery->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);
        } elseif ($request->filled('tahun')) {
            $diskonTransaksiQuery->whereYear('created_at', $tahun);
        }
        $diskonTransaksiList = $diskonTransaksiQuery->get();

        // Diskon Transaksi Satuan
        $diskonSatuanQuery = TransaksiSatuan::where('disc', '>', 0);
        if ($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun')) {
            $diskonSatuanQuery->whereDay('created_at', $hari)
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $diskonSatuanQuery->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);
        } elseif ($request->filled('tahun')) {
            $diskonSatuanQuery->whereYear('created_at', $tahun);
        }
        $diskonSatuanList = $diskonSatuanQuery->get();

        // Diskon Kuota
        $diskonKuotaQuery = Pemasukan::where('kategori', 'LIKE', 'Kuota%')
            ->where('keterangan', 'LIKE', '%Diskon:%');

        if ($request->filled('hari') && $request->filled('bulan') && $request->filled('tahun')) {
            $diskonKuotaQuery->whereDay('created_at', $hari)
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);
        } elseif ($request->filled('bulan') && $request->filled('tahun')) {
            $diskonKuotaQuery->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun);
        } elseif ($request->filled('tahun')) {
            $diskonKuotaQuery->whereYear('created_at', $tahun);
        }
        $diskonKuotaList = $diskonKuotaQuery->get();

        return view('superadmin.finance.finance', compact(
            'targetHari',
            'targetBulan',
            'targetTahun',
            'totalTransaksi',
            'totalSatuan',
            'totalKuotaLunas',
            'totalPemasukanManual',
            'totalPemasukanBersih',
            'pengeluaran',
            'labaBersih',
            'totalUtang',
            'totalDiskon',
            'utangReguler',
            'utangSatuan',
            'kuotaPending',
            'diskonTransaksiList',
            'diskonSatuanList',
            'diskonKuotaList'
        ));
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

    // Tambah dan Data Harga Layanan
    public function dataharga()
    {
        $harga = Harga::orderBy('id', 'ASC')->get();

        return view('superadmin.laundri.harga', compact('harga'));
    }

    // Proses Simpan Harga
    public function hargastore(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jenis' => 'required',
            'harga' => 'required',
            'hari'  => 'required',
        ]);

        $addharga = new Harga();
        $addharga->nama = $request->nama;
        $addharga->jenis = $request->jenis;
        $addharga->kg = 1000; // satuan gram
        $addharga->harga = $request->harga;
        $addharga->hari = $request->hari;
        $addharga->status = 1; // aktif
        $addharga->save();

        Session::flash('success', 'Tambah Data Layanan Berhasil');
        return redirect('data-harga');
    }

    // Proses edit harga
    public function hargaedit(Request $request)
    {
        $request->validate([
            'id_harga' => 'required|exists:hargas,id', // Pastikan id valid
            'nama' => 'required',
            'jenis' => 'required',
            'harga' => 'required|numeric',
            'hari' => 'required|numeric',
            'status' => 'required|in:0,1',
        ]);

        $harga = Harga::findOrFail($request->id_harga);
        $harga->update([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'kg' => $request->kg,
            'harga' => $request->harga,
            'hari' => $request->hari,
            'status' => $request->status,
        ]);

        Session::flash('success', 'Edit Data Layanan Berhasil');
        return redirect('data-harga');
    }

    // Tambah dan Data Layanan Satuan
    public function datasatuan()
    {
        $satuan = Satuan::orderBy('id', 'ASC')->get();

        return view('superadmin.laundri.satuan', compact('satuan'));
    }

    // Proses Simpan Satuan
    public function satuanstore(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jenis' => 'required',
            'harga' => 'required',
            'hari'  => 'required',
        ]);

        $addsatuan = new Satuan();
        $addsatuan->nama = $request->nama;
        $addsatuan->jenis = $request->jenis;
        $addsatuan->pcs = 1;
        $addsatuan->harga = $request->harga;
        $addsatuan->hari = $request->hari;
        $addsatuan->status = 1; // aktif
        $addsatuan->save();

        Session::flash('success', 'Tambah Data Layanan Satuan Berhasil');
        return redirect('data-satuan');
    }

    // Proses edit Satuan
    public function satuanedit(Request $request)
    {
        $request->validate([
            'id_satuan' => 'required|exists:satuans,id', // Pastikan id valid
            'nama' => 'required',
            'jenis' => 'required',
            'harga' => 'required|numeric',
            'hari' => 'required|numeric',
            'status' => 'required|in:0,1',
        ]);

        $satuan = Satuan::findOrFail($request->id_satuan);
        $satuan->update([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'pcs' => $request->pcs,
            'harga' => $request->harga,
            'hari' => $request->hari,
            'status' => $request->status,
        ]);

        Session::flash('success', 'Edit Data Layanan Satuan Berhasil');
        return redirect('data-satuan');
    }
}
