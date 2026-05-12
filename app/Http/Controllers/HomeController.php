<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Karyawan, LaundrySetting, Pemasukan, PurchaseRequest, TargetFinance, Transaksi, TransaksiSatuan, User};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->auth === "Admin") {
                // Target laundry
                $targetLaundry = LaundrySetting::first();

                if ($targetLaundry) {
                    $targetTahun = $targetLaundry->target_year;
                    $targetBulan = $targetLaundry->target_month;
                    $targetHari = $targetLaundry->target_day;
                } else {
                    $targetTahun = 0;
                    $targetBulan = 0;
                    $targetHari = 0;
                }

                $tanggal = request('tanggal', now()->toDateString());

                $kgHariIni = Transaksi::whereDate('created_at', $tanggal)
                    ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->sum('kg');

                $kgBulanIni = Transaksi::whereMonth('created_at', date('m', strtotime($tanggal)))
                    ->whereYear('created_at', date('Y', strtotime($tanggal)))
                    ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->sum('kg');

                $kgTahunIni = Transaksi::whereYear('created_at', date('Y', strtotime($tanggal)))
                    ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->sum('kg');

                $customer = user::where('auth', 'Customer')->get();
                $masuk = Transaksi::whereIN('status_order', ['Process', 'Antrian', 'Process', 'Done', 'Delivery'])->count();
                $selesai = Transaksi::where('status_order', 'Done')->count();
                $diambil = Transaksi::where('status_order', 'Delivery')->count();
                $sudahbayar = Transaksi::where('status_payment', 'Success')->count();
                $belumbayar = Transaksi::where('status_payment', 'Pending')->count();
                $data = DB::table("transaksis")
                    ->select("id", DB::raw("(COUNT(*)) as customer"))
                    ->orderBy('created_at')
                    ->groupBy(DB::raw("MONTH(created_at)"))
                    ->count();

                // Statistik Harian
                $hari = DB::table('transaksis')
                    ->select('tgl', DB::raw('count(id) AS jml'))
                    ->whereYear('created_at', '=', date("Y", strtotime(now())))
                    ->whereMonth('created_at', '=', date("m", strtotime(now())))
                    ->groupBy('tgl')
                    ->get();

                $tanggal = '';
                $batas =  31;
                $nilai = '';
                for ($_i = 1; $_i <= $batas; $_i++) {
                    $tanggal = $tanggal . (string)$_i . ',';
                    $_check = false;
                    foreach ($hari as $_data) {
                        if ((int)@$_data->tgl === $_i) {
                            $nilai = $nilai . (string)$_data->jml . ',';
                            $_check = true;
                        }
                    }
                    if (!$_check) {
                        $nilai = $nilai . '0,';
                    }
                }

                // Statistik Bulanan
                $jan = Transaksi::where('bulan', 1)->where('tahun', Carbon::now()->format('Y'))->count();
                $feb = Transaksi::where('bulan', 2)->where('tahun', Carbon::now()->format('Y'))->count();
                $mar = Transaksi::where('bulan', 3)->where('tahun', Carbon::now()->format('Y'))->count();
                $apr = Transaksi::where('bulan', 4)->where('tahun', Carbon::now()->format('Y'))->count();
                $mey = Transaksi::where('bulan', 5)->where('tahun', Carbon::now()->format('Y'))->count();
                $juni = Transaksi::where('bulan', 6)->where('tahun', Carbon::now()->format('Y'))->count();
                $july = Transaksi::where('bulan', 7)->where('tahun', Carbon::now()->format('Y'))->count();
                $aug = Transaksi::where('bulan', 8)->where('tahun', Carbon::now()->format('Y'))->count();
                $sep = Transaksi::where('bulan', 9)->where('tahun', Carbon::now()->format('Y'))->count();
                $oct = Transaksi::where('bulan', 10)->where('tahun', Carbon::now()->format('Y'))->count();
                $nov = Transaksi::where('bulan', 11)->where('tahun', Carbon::now()->format('Y'))->count();
                $dec = Transaksi::where('bulan', 12)->where('tahun', Carbon::now()->format('Y'))->count();

                return view('modul_admin.index', compact(
                    'tanggal',
                    'targetHari',
                    'targetBulan',
                    'targetTahun',
                    'kgHariIni',
                    'kgBulanIni',
                    'kgTahunIni'
                ))->with('data', $data)
                    ->with('customer', value: $customer)
                    ->with('masuk', $masuk)
                    ->with('selesai', $selesai)
                    ->with('sudahbayar', $sudahbayar)
                    ->with('belumbayar', $belumbayar)
                    ->with('_tanggal', substr($tanggal, 0, -1))
                    ->with('_nilai', substr($nilai, 0, -1))
                    ->with('diambil', $diambil)
                    ->with('jan', $jan)
                    ->with('feb', $feb)
                    ->with('mar', $mar)
                    ->with('apr', $apr)
                    ->with('mey', $mey)
                    ->with('juni', $juni)
                    ->with('july', $july)
                    ->with('aug', $aug)
                    ->with('sep', $sep)
                    ->with('oct', $oct)
                    ->with('nov', $nov)
                    ->with('dec', $dec);
            } elseif (Auth::user()->auth === "SuperAdmin") {
                $today = Carbon::now();

                // Transaksi Reguler (Sukses)
                $transaksi = Transaksi::where('status_payment', 'Success')->get();
                $transaksiHari = Transaksi::where('status_payment', 'Success')
                    ->where('tgl', $today->day)
                    ->where('bulan', $today->month)
                    ->where('tahun', $today->year)
                    ->sum('harga_akhir');

                $transaksiBulan = Transaksi::where('status_payment', 'Success')
                    ->where('bulan', $today->month)
                    ->where('tahun', $today->year)
                    ->sum('harga_akhir');

                $transaksiTahun = Transaksi::where('status_payment', 'Success')
                    ->where('tahun', $today->year)
                    ->sum('harga_akhir');

                $totalTransaksi = $transaksi->sum('harga_akhir');

                // Transaksi Satuan (Sukses)
                $satuan = TransaksiSatuan::where('status_payment', 'Success')->get();
                $satuanHari = TransaksiSatuan::where('status_payment', 'Success')
                    ->where('tgl', $today->day)
                    ->where('bulan', $today->month)
                    ->where('tahun', $today->year)
                    ->sum('harga_akhir');

                $satuanBulan = TransaksiSatuan::where('status_payment', 'Success')
                    ->where('bulan', $today->month)
                    ->where('tahun', $today->year)
                    ->sum('harga_akhir');

                $satuanTahun = TransaksiSatuan::where('status_payment', 'Success')
                    ->where('tahun', $today->year)
                    ->sum('harga_akhir');

                $totalSatuan = $satuan->sum('harga_akhir');

                // Purchase Kuota
                $purchaseKuotaList = \App\Models\PurchaseRequest::where('status', 'confirmed')
                    ->orderByDesc('created_at')
                    ->with('user')
                    ->get();
                $totalPurchaseKuota = $purchaseKuotaList->sum('package_price');

                $purchaseKuotaHari = $purchaseKuotaList->where('created_at', '>=', $today->copy()->startOfDay())
                    ->where('created_at', '<=', $today->copy()->endOfDay())
                    ->sum('package_price');

                $purchaseKuotaBulan = $purchaseKuotaList->where('created_at', '>=', $today->copy()->startOfMonth())
                    ->where('created_at', '<=', $today->copy()->endOfMonth())
                    ->sum('package_price');

                $purchaseKuotaTahun = $purchaseKuotaList->where('created_at', '>=', $today->copy()->startOfYear())
                    ->where('created_at', '<=', $today->copy()->endOfYear())
                    ->sum('package_price');

                // Kuota Manual
                $kuotaList = Pemasukan::where('kategori', 'LIKE', 'Kuota%')
                    ->where(function ($q) {
                        $q->whereNull('keterangan')->orWhere('keterangan', 'not like', '%nyusul%');
                    })->orderByDesc('created_at')->get();

                $pemasukanManualKuota = $kuotaList->sum('total');

                $manualKuotaHari = $kuotaList->where('created_at', '>=', $today->copy()->startOfDay())
                    ->where('created_at', '<=', $today->copy()->endOfDay())
                    ->sum('total');

                $manualKuotaBulan = $kuotaList->where('created_at', '>=', $today->copy()->startOfMonth())
                    ->where('created_at', '<=', $today->copy()->endOfMonth())
                    ->sum('total');

                $manualKuotaTahun = $kuotaList->where('created_at', '>=', $today->copy()->startOfYear())
                    ->where('created_at', '<=', $today->copy()->endOfYear())
                    ->sum('total');

                // Total kuota = manual + request
                $totalKuota = $pemasukanManualKuota + $totalPurchaseKuota;
                $kuotaHari = $manualKuotaHari + $purchaseKuotaHari;
                $kuotaBulan = $manualKuotaBulan + $purchaseKuotaBulan;
                $kuotaTahun = $manualKuotaTahun + $purchaseKuotaTahun;

                // Pemasukan Lain
                $pemasukanLainList = Pemasukan::where('kategori', 'NOT LIKE', 'Kuota%')->get();
                $pemasukanManualLain = $pemasukanLainList->sum('total');

                $lainHari = $pemasukanLainList->where('created_at', '>=', $today->copy()->startOfDay())
                    ->where('created_at', '<=', $today->copy()->endOfDay())
                    ->sum('total');

                $lainBulan = $pemasukanLainList->where('created_at', '>=', $today->copy()->startOfMonth())
                    ->where('created_at', '<=', $today->copy()->endOfMonth())
                    ->sum('total');

                $lainTahun = $pemasukanLainList->where('created_at', '>=', $today->copy()->startOfYear())
                    ->where('created_at', '<=', $today->copy()->endOfYear())
                    ->sum('total');

                $totalPemasukan = $totalTransaksi + $totalSatuan + $totalKuota + $pemasukanManualLain;

                // Total harian, bulanan, tahunan gabungan
                $hari = $transaksiHari + $satuanHari + $kuotaHari + $lainHari;
                $bulan = $transaksiBulan + $satuanBulan + $kuotaBulan + $lainBulan;
                $tahun = $transaksiTahun + $satuanTahun + $kuotaTahun + $lainTahun;

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

                // Grafik: Pencapaian terhadap target
                $ny = $targetTahun > 0 ? round(($tahun / $targetTahun) * 100, 2) : 0;
                $nm = $targetBulan > 0 ? round(($bulan / $targetBulan) * 100, 2) : 0;
                $nd = $targetHari > 0 ? round(($hari / $targetHari) * 100, 2) : 0;

                // Target laundry
                $targetLaundry = LaundrySetting::first();

                if ($targetLaundry) {
                    $targetTahun = $targetLaundry->target_year;
                    $targetBulan = $targetLaundry->target_month;
                    $targetHari = $targetLaundry->target_day;
                } else {
                    $targetTahun = 0;
                    $targetBulan = 0;
                    $targetHari = 0;
                }

                $tanggal = request('tanggal', now()->toDateString());

                $kgHariIni = Transaksi::whereDate('created_at', $tanggal)
                    ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->sum('kg');

                $kgBulanIni = Transaksi::whereMonth('created_at', date('m', strtotime($tanggal)))
                    ->whereYear('created_at', date('Y', strtotime($tanggal)))
                    ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->sum('kg');

                $kgTahunIni = Transaksi::whereYear('created_at', date('Y', strtotime($tanggal)))
                    ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->sum('kg');

                $jumlahAdmin = User::where('auth', 'Admin')->count();
                $jumlahKaryawan = Karyawan::count();
                $jumlahCustomer = User::where('auth', 'Customer')->count();

                // Jumlah Transaksi
                $transaksiReguler = Transaksi::count();
                $transaksiSatuan = TransaksiSatuan::count();

                $now = Carbon::now();

                // Statistik Harian
                $harianReg = Transaksi::selectRaw('DAY(created_at) as tgl, SUM(harga_akhir) as total')
                    ->whereYear('created_at', $now->year)
                    ->whereMonth('created_at', $now->month)
                    ->where('status_payment', 'Success')
                    ->groupByRaw('DAY(created_at)')
                    ->pluck('total', 'tgl');

                $harianSat = TransaksiSatuan::selectRaw('DAY(created_at) as tgl, SUM(harga_akhir) as total')
                    ->whereYear('created_at', $now->year)
                    ->whereMonth('created_at', $now->month)
                    ->where('status_payment', 'Success')
                    ->groupByRaw('DAY(created_at)')
                    ->pluck('total', 'tgl');

                // Harian - Pemasukan Kuota (dari tabel Pemasukan)
                $harianPemKuota = Pemasukan::selectRaw('DAY(created_at) as tgl, SUM(total) as total')
                    ->whereYear('created_at', $now->year)
                    ->whereMonth('created_at', $now->month)
                    ->where('kategori', 'like', 'Kuota%')
                    ->groupByRaw('DAY(created_at)')
                    ->pluck('total', 'tgl');

                // Harian - Tambahan dari PurchaseRequest
                $harianPRKuota = PurchaseRequest::selectRaw('DAY(created_at) as tgl, SUM(package_price) as total')
                    ->whereYear('created_at', $now->year)
                    ->whereMonth('created_at', $now->month)
                    ->where('status', 'confirmed')
                    ->groupByRaw('DAY(created_at)')
                    ->pluck('total', 'tgl');

                foreach ($harianPRKuota as $day => $value) {
                    $harianPemKuota[$day] = ($harianPemKuota[$day] ?? 0) + $value;
                }

                // Harian - Pemasukan Non-Kuota
                $harianPem = Pemasukan::selectRaw('DAY(created_at) as tgl, SUM(total) as total')
                    ->whereYear('created_at', $now->year)
                    ->whereMonth('created_at', $now->month)
                    ->where(function ($q) {
                        $q->whereNull('kategori')
                            ->orWhere('kategori', 'not like', 'Kuota%');
                    })
                    ->groupByRaw('DAY(created_at)')
                    ->pluck('total', 'tgl');

                // Loop harian
                $tanggal = '';
                $_nilai_reg = '';
                $_nilai_satuan = '';
                $_nilai_pem_kuota = '';
                $_nilai_pem_nonkuota = '';
                for ($i = 1; $i <= 31; $i++) {
                    $tanggal .= "$i,";
                    $_nilai_reg .= $harianReg[$i] ?? 0;
                    $_nilai_reg .= ',';
                    $_nilai_satuan .= $harianSat[$i] ?? 0;
                    $_nilai_satuan .= ',';
                    $_nilai_pem_kuota .= $harianPemKuota[$i] ?? 0;
                    $_nilai_pem_kuota .= ',';
                    $_nilai_pem_nonkuota .= $harianPem[$i] ?? 0;
                    $_nilai_pem_nonkuota .= ',';
                }

                // Statistik Bulanan
                $bulananReg = [];
                $bulananSat = [];
                $bulananPemKuota = array_fill(0, 12, 0); // <- pastikan diisi awal 0
                $bulananPem = [];

                for ($i = 1; $i <= 12; $i++) {
                    $bulananReg[] = Transaksi::whereMonth('created_at', $i)
                        ->whereYear('created_at', $now->year)
                        ->where('status_payment', 'Success')
                        ->sum('harga_akhir');

                    $bulananSat[] = TransaksiSatuan::whereMonth('created_at', $i)
                        ->whereYear('created_at', $now->year)
                        ->where('status_payment', 'Success')
                        ->sum('harga_akhir');

                    $kuota = Pemasukan::whereMonth('created_at', $i)
                        ->whereYear('created_at', $now->year)
                        ->where('kategori', 'like', 'Kuota%')
                        ->sum('total');

                    $pr = PurchaseRequest::whereMonth('created_at', $i)
                        ->whereYear('created_at', $now->year)
                        ->where('status', 'confirmed')
                        ->sum('package_price');

                    $bulananPemKuota[$i - 1] = $kuota + $pr;

                    $bulananPem[] = Pemasukan::whereMonth('created_at', $i)
                        ->whereYear('created_at', $now->year)
                        ->where(function ($q) {
                            $q->whereNull('kategori')
                                ->orWhere('kategori', 'not like', 'Kuota%');
                        })
                        ->sum('total');
                }

                // Statistik Tahunan
                $tahunanReg = [];
                $tahunanSat = [];
                $tahunanPemKuota = [];
                $tahunanPem = [];

                $startYear = now()->year - 4; // 5 tahun ke belakang
                $currentYear = now()->year;

                for ($i = $startYear; $i <= $currentYear; $i++) {
                    $tahunanReg[] = Transaksi::whereYear('created_at', $i)
                        ->where('status_payment', 'Success')
                        ->sum('harga_akhir');

                    $tahunanSat[] = TransaksiSatuan::whereYear('created_at', $i)
                        ->where('status_payment', 'Success')
                        ->sum('harga_akhir');

                    $kuota = Pemasukan::whereYear('created_at', $i)
                        ->where('kategori', 'like', 'Kuota%')
                        ->sum('total');

                    $pr = PurchaseRequest::whereYear('created_at', $i)
                        ->where('status', 'confirmed')
                        ->sum('package_price');

                    $tahunanPemKuota[] = $kuota + $pr;

                    $tahunanPem[] = Pemasukan::whereYear('created_at', $i)
                        ->where(function ($q) {
                            $q->whereNull('kategori')
                                ->orWhere('kategori', 'not like', 'Kuota%');
                        })
                        ->sum('total');
                }

                return view('superadmin.index', compact(
                    'jumlahAdmin',
                    'jumlahKaryawan',
                    'jumlahCustomer',
                    'bulananReg',
                    'bulananSat',
                    'bulananPemKuota',
                    'bulananPem',
                    'startYear',
                    'currentYear',
                    'tahunanReg',
                    'tahunanSat',
                    'tahunanPemKuota',
                    'tahunanPem',
                    'tanggal',
                    'targetHari',
                    'targetBulan',
                    'targetTahun',
                    'kgHariIni',
                    'kgBulanIni',
                    'kgTahunIni',
                    'tahun',
                    'bulan',
                    'hari',
                    'transaksi',
                    'ny',
                    'nm',
                    'nd',
                    'totalPemasukan'
                ))->with('_tanggal', rtrim($tanggal, ','))
                    ->with('_nilai_reg', rtrim($_nilai_reg, ','))
                    ->with('_nilai_satuan', rtrim($_nilai_satuan, ','))
                    ->with('_nilai_pem_kuota', rtrim($_nilai_pem_kuota, ','))
                    ->with('_nilai_pem_nonkuota', rtrim($_nilai_pem_nonkuota, ','));
            } elseif (Auth::user()->auth === "Customer") {
                $user = Auth::user();

                // Ambil transaksi biasa
                $transaksis_biasa = Transaksi::where('customer_id', $user->id)
                    ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->where('is_hidden_customer', false)
                    ->get();

                // Ambil transaksi satuan
                $transaksis_satuan = TransaksiSatuan::where('customer_id', $user->id)
                    ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->where('is_hidden_customer', false)
                    ->with('details')
                    ->get();

                // Estimasi selesai untuk transaksi satuan
                foreach ($transaksis_satuan as $transaksi) {
                    $tglTransaksi = \Carbon\Carbon::parse($transaksi->tgl_transaksi);
                    $maxHari = $transaksi->details->max('hari');
                    $transaksi->estimasi_selesai = $tglTransaksi->copy()
                        ->addDays((int) $maxHari)
                        ->format('d M Y');
                    $transaksi->is_satuan = true;
                }

                // Estimasi selesai untuk transaksi biasa
                foreach ($transaksis_biasa as $transaksi) {
                    $tglTransaksi = \Carbon\Carbon::parse($transaksi->tgl_transaksi);
                    $transaksi->estimasi_selesai = is_numeric($transaksi->hari)
                        ? $tglTransaksi->copy()->addDays($transaksi->hari)->format('d M Y')
                        : $tglTransaksi->format('d M Y');
                    $transaksi->is_satuan = false;
                }

                // Gabungkan dua jenis transaksi
                $transaksis = $transaksis_biasa->merge($transaksis_satuan)->sortByDesc('created_at');

                // Hitung total masuk, selesai, dan diambil dari kedua tabel
                $masuk = Transaksi::where('customer_id', $user->id)
                    ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->count()
                    +
                    TransaksiSatuan::where('customer_id', $user->id)
                    ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->count();
                $selesai = Transaksi::where('customer_id', $user->id)
                    ->where('status_order', 'Done')
                    ->count()
                    +
                    TransaksiSatuan::where('customer_id', $user->id)
                    ->where('status_order', 'Done')
                    ->count();

                $diambil = Transaksi::where('customer_id', $user->id)
                    ->where('status_order', 'Delivery')
                    ->count()
                    +
                    TransaksiSatuan::where('customer_id', $user->id)
                    ->where('status_order', 'Delivery')
                    ->count();

                // Ambil kuota laundry
                $kuotaPerKategori = $user->kuotaLaundry()
                    ->get()
                    ->groupBy('kategori');

                // Kirim ke view
                return view('customer.index', compact(
                    'masuk',
                    'diambil',
                    'selesai',
                    'kuotaPerKategori',
                    'transaksis',
                    'transaksis_satuan'
                ));
            } else {
                Auth::logout();
            }
        }
    }
}
