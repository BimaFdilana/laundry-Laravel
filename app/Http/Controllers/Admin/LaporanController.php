<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Transaksi, TransaksiSatuan};
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function harian()
    {
        $tanggal = request('tanggal', now()->toDateString());

        // Total Kg di tanggal terpilih
        $jumlahKg = Transaksi::whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->whereDate('created_at', $tanggal)
            ->sum('kg');

        $detailKgPerJenis = DB::table('hargas')
            ->leftJoin('transaksis', function ($join) use ($tanggal) {
                $join->on('transaksis.harga_id', '=', 'hargas.id')
                    ->whereIn('transaksis.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->whereDate('transaksis.created_at', $tanggal);
            })
            ->selectRaw("
                CASE
                    WHEN hargas.jenis LIKE '%BAYI%' THEN 'LAUNDRY BAYI'
                    WHEN hargas.jenis LIKE '%SYARIAH%' THEN 'LAUNDRY SYARIAH'
                    ELSE hargas.jenis
                END as jenis_grouped,
                COALESCE(SUM(transaksis.kg), 0) as total_kg
            ")
            ->groupBy('jenis_grouped')
            ->orderBy('jenis_grouped')
            ->get();

        $detailPcsPerJenis = DB::table('hargas')
            ->leftJoin('transaksis', function ($join) use ($tanggal) {
                $join->on('transaksis.harga_id', '=', 'hargas.id')
                    ->whereIn('transaksis.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->whereDate('transaksis.created_at', $tanggal);
            })
            ->selectRaw("
                CASE
                    WHEN hargas.jenis LIKE '%BAYI%' THEN 'LAUNDRY BAYI'
                    WHEN hargas.jenis LIKE '%SYARIAH%' THEN 'LAUNDRY SYARIAH'
                    ELSE hargas.jenis
                END as jenis_grouped,
                COALESCE(SUM(transaksis.jumlah_lembar_baju), 0) as total_pcs
            ")
            ->groupBy('jenis_grouped')
            ->orderBy('jenis_grouped')
            ->get();

        // Laporan Laundry Biasa per customer di tanggal terpilih
        $laporanKgCustomer = Transaksi::selectRaw('
            customer_id,
            SUM(kg) as total_kg,
            SUM(jumlah_lembar_baju) as total_pcs,
            SUM(harga_akhir) as total_harga
        ')
            ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->whereDate('created_at', $tanggal)
            ->groupBy('customer_id')
            ->with('customers')
            ->get();

        // Laporan Transaksi Satuan per customer di tanggal terpilih
        $laporanSatuanCustomer = TransaksiSatuan::selectRaw('
            customer_id,
            SUM(transaksi_satuan_details.pcs) as total_pcs,
            SUM(transaksi_satuans.harga_akhir) as total_harga
        ')
            ->join('transaksi_satuan_details', 'transaksi_satuans.id', '=', 'transaksi_satuan_details.transaksi_satuan_id')
            ->whereIn('transaksi_satuans.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->whereDate('transaksi_satuans.created_at', $tanggal)
            ->groupBy('customer_id')
            ->with('customers')
            ->get();

        // Kinerja Karyawan Reguler per hari
        $laporanKaryawanReguler = Transaksi::selectRaw('
            karyawan_id,
            SUM(kg) as total_kg,
            SUM(jumlah_lembar_baju) as total_lembar
        ')
            ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->whereDate('created_at', $tanggal)
            ->groupBy('karyawan_id')
            ->with('karyawan')
            ->get();

        // Kinerja Karyawan Satuan per hari
        $laporanKaryawanSatuan = TransaksiSatuan::selectRaw('
            karyawan_id,
            SUM(transaksi_satuan_details.pcs) as total_lembar
        ')
            ->join('transaksi_satuan_details', 'transaksi_satuans.id', '=', 'transaksi_satuan_details.transaksi_satuan_id')
            ->whereIn('transaksi_satuans.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->whereDate('transaksi_satuans.created_at', $tanggal)
            ->groupBy('karyawan_id')
            ->with('karyawan')
            ->get();

        return view('modul_admin.laporan.harian', compact(
            'laporanKgCustomer',
            'laporanSatuanCustomer',
            'laporanKaryawanReguler',
            'laporanKaryawanSatuan',
            'jumlahKg',
            'tanggal',
            'detailKgPerJenis',
            'detailPcsPerJenis'
        ));
    }

    public function bulanan()
    {
        $bulan = request('bulan', now()->month);
        $tahun = request('tahun', now()->year);

        // Total Kg bulan-tahun terpilih
        $jumlahKg = Transaksi::whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->sum('kg');

        // Detail KG per jenis untuk laporan bulanan
        $detailKgPerJenis = DB::table('hargas')
            ->leftJoin('transaksis', function ($join) use ($bulan, $tahun) {
                $join->on('transaksis.harga_id', '=', 'hargas.id')
                    ->whereIn('transaksis.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->whereMonth('transaksis.created_at', $bulan)
                    ->whereYear('transaksis.created_at', $tahun);
            })
            ->selectRaw("
                CASE
                    WHEN hargas.jenis LIKE '%BAYI%' THEN 'LAUNDRY BAYI'
                    WHEN hargas.jenis LIKE '%SYARIAH%' THEN 'LAUNDRY SYARIAH'
                    ELSE hargas.jenis
                END as jenis_grouped,
                COALESCE(SUM(transaksis.kg), 0) as total_kg
            ")
            ->groupBy('jenis_grouped')
            ->orderBy('jenis_grouped')
            ->get();

        // Detail PCS per jenis untuk laporan bulanan
        $detailPcsPerJenis = DB::table('hargas')
            ->leftJoin('transaksis', function ($join) use ($bulan, $tahun) {
                $join->on('transaksis.harga_id', '=', 'hargas.id')
                    ->whereIn('transaksis.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->whereMonth('transaksis.created_at', $bulan)
                    ->whereYear('transaksis.created_at', $tahun);
            })
            ->selectRaw("
                CASE
                    WHEN hargas.jenis LIKE '%BAYI%' THEN 'LAUNDRY BAYI'
                    WHEN hargas.jenis LIKE '%SYARIAH%' THEN 'LAUNDRY SYARIAH'
                    ELSE hargas.jenis
                END as jenis_grouped,
                COALESCE(SUM(transaksis.jumlah_lembar_baju), 0) as total_pcs
            ")
            ->groupBy('jenis_grouped')
            ->orderBy('jenis_grouped')
            ->get();

        $kgPerHariPerJenis = DB::table('hargas')
            ->leftJoin('transaksis', function ($join) use ($bulan, $tahun) {
                $join->on('transaksis.harga_id', '=', 'hargas.id')
                    ->whereIn('transaksis.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->whereMonth('transaksis.created_at', $bulan)
                    ->whereYear('transaksis.created_at', $tahun);
            })
            ->selectRaw("
                DATE(transaksis.created_at) as tanggal,
                CASE
                    WHEN hargas.jenis LIKE '%BAYI%' THEN 'LAUNDRY BAYI'
                    WHEN hargas.jenis LIKE '%SYARIAH%' THEN 'LAUNDRY SYARIAH'
                    ELSE hargas.jenis
                END as jenis_grouped,
                COALESCE(SUM(transaksis.kg), 0) as total_kg
            ")
            ->groupByRaw('tanggal, jenis_grouped')
            ->orderBy('tanggal')
            ->get();

        $pcsPerHariPerJenis = DB::table('hargas')
            ->leftJoin('transaksis', function ($join) use ($bulan, $tahun) {
                $join->on('transaksis.harga_id', '=', 'hargas.id')
                    ->whereIn('transaksis.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->whereMonth('transaksis.created_at', $bulan)
                    ->whereYear('transaksis.created_at', $tahun);
            })
            ->selectRaw("
                DATE(transaksis.created_at) as tanggal,
                CASE
                    WHEN hargas.jenis LIKE '%BAYI%' THEN 'LAUNDRY BAYI'
                    WHEN hargas.jenis LIKE '%SYARIAH%' THEN 'LAUNDRY SYARIAH'
                    ELSE hargas.jenis
                END as jenis_grouped,
                COALESCE(SUM(transaksis.jumlah_lembar_baju), 0) as total_pcs
            ")
            ->groupByRaw('tanggal, jenis_grouped')
            ->orderBy('tanggal')
            ->get();

        // Laporan Laundry Biasa per customer per bulan-tahun
        $laporanKgCustomer = Transaksi::selectRaw('
            customer_id,
            YEAR(created_at) as tahun,
            MONTH(created_at) as bulan,
            SUM(kg) as total_kg,
            SUM(jumlah_lembar_baju) as total_pcs,
            SUM(harga_akhir) as total_harga
        ')
            ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->groupBy('customer_id', 'tahun', 'bulan')
            ->with('customers')
            ->get();

        // Laporan Transaksi Satuan per customer per bulan-tahun
        $laporanSatuanCustomer = TransaksiSatuan::selectRaw('
            customer_id,
            YEAR(transaksi_satuans.created_at) as tahun,
            MONTH(transaksi_satuans.created_at) as bulan,
            SUM(transaksi_satuan_details.pcs) as total_pcs,
            SUM(transaksi_satuans.harga_akhir) as total_harga
        ')
            ->join('transaksi_satuan_details', 'transaksi_satuans.id', '=', 'transaksi_satuan_details.transaksi_satuan_id')
            ->whereIn('transaksi_satuans.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->whereYear('transaksi_satuans.created_at', $tahun)
            ->whereMonth('transaksi_satuans.created_at', $bulan)
            ->groupBy('customer_id', 'tahun', 'bulan')
            ->with('customers')
            ->get();

        // Kinerja Karyawan Reguler per bulan-tahun
        $laporanKaryawanReguler = Transaksi::selectRaw('
            karyawan_id,
            YEAR(created_at) as tahun,
            MONTH(created_at) as bulan,
            SUM(kg) as total_kg,
            SUM(jumlah_lembar_baju) as total_lembar
        ')
            ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->groupBy('karyawan_id', 'tahun', 'bulan')
            ->with('karyawan')
            ->get();

        // Kinerja Karyawan Satuan per bulan-tahun
        $laporanKaryawanSatuan = TransaksiSatuan::selectRaw('
            karyawan_id,
            YEAR(transaksi_satuans.created_at) as tahun,
            MONTH(transaksi_satuans.created_at) as bulan,
            SUM(transaksi_satuan_details.pcs) as total_lembar
        ')
            ->join('transaksi_satuan_details', 'transaksi_satuans.id', '=', 'transaksi_satuan_details.transaksi_satuan_id')
            ->whereIn('transaksi_satuans.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->whereYear('transaksi_satuans.created_at', $tahun)
            ->whereMonth('transaksi_satuans.created_at', $bulan)
            ->groupBy('karyawan_id', 'tahun', 'bulan')
            ->with('karyawan')
            ->get();

        return view('modul_admin.laporan.bulanan', compact(
            'laporanKgCustomer',
            'laporanSatuanCustomer',
            'laporanKaryawanReguler',
            'laporanKaryawanSatuan',
            'jumlahKg',
            'bulan',
            'tahun',
            'detailKgPerJenis',
            'detailPcsPerJenis',
            'kgPerHariPerJenis',
            'pcsPerHariPerJenis',
        ));
    }

    public function tahunan()
    {
        $tahun = request('tahun', now()->year);

        // Total Kg tahun terpilih
        $jumlahKg = Transaksi::whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->whereYear('created_at', $tahun)
            ->sum('kg');

        // Detail KG per jenis
        $detailKgPerJenis = DB::table('hargas')
            ->leftJoin('transaksis', function ($join) use ($tahun) {
                $join->on('transaksis.harga_id', '=', 'hargas.id')
                    ->whereIn('transaksis.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->whereYear('transaksis.created_at', $tahun);
            })
            ->selectRaw("
                CASE
                    WHEN hargas.jenis LIKE '%BAYI%' THEN 'LAUNDRY BAYI'
                    WHEN hargas.jenis LIKE '%SYARIAH%' THEN 'LAUNDRY SYARIAH'
                    ELSE hargas.jenis
                END as jenis_grouped,
                COALESCE(SUM(transaksis.kg), 0) as total_kg
            ")
            ->groupBy('jenis_grouped')
            ->orderBy('jenis_grouped')
            ->get();

        // Detail PCS per jenis
        $detailPcsPerJenis = DB::table('hargas')
            ->leftJoin('transaksis', function ($join) use ($tahun) {
                $join->on('transaksis.harga_id', '=', 'hargas.id')
                    ->whereIn('transaksis.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->whereYear('transaksis.created_at', $tahun);
            })
            ->selectRaw("
                CASE
                    WHEN hargas.jenis LIKE '%BAYI%' THEN 'LAUNDRY BAYI'
                    WHEN hargas.jenis LIKE '%SYARIAH%' THEN 'LAUNDRY SYARIAH'
                    ELSE hargas.jenis
                END as jenis_grouped,
                COALESCE(SUM(transaksis.jumlah_lembar_baju), 0) as total_pcs
            ")
            ->groupBy('jenis_grouped')
            ->orderBy('jenis_grouped')
            ->get();

        $kgPerBulanPerJenis = DB::table('hargas')
            ->leftJoin('transaksis', function ($join) use ($tahun) {
                $join->on('transaksis.harga_id', '=', 'hargas.id')
                    ->whereIn('transaksis.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->whereYear('transaksis.created_at', $tahun);
            })
            ->selectRaw("
                MONTH(transaksis.created_at) as bulan,
                CASE
                    WHEN hargas.jenis LIKE '%BAYI%' THEN 'LAUNDRY BAYI'
                    WHEN hargas.jenis LIKE '%SYARIAH%' THEN 'LAUNDRY SYARIAH'
                    ELSE hargas.jenis
                END as jenis_grouped,
                COALESCE(SUM(transaksis.kg), 0) as total_kg
            ")
            ->groupByRaw('bulan, jenis_grouped')
            ->orderBy('bulan')
            ->get();

        $pcsPerBulanPerJenis = DB::table('hargas')
            ->leftJoin('transaksis', function ($join) use ($tahun) {
                $join->on('transaksis.harga_id', '=', 'hargas.id')
                    ->whereIn('transaksis.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
                    ->whereYear('transaksis.created_at', $tahun);
            })
            ->selectRaw("
                MONTH(transaksis.created_at) as bulan,
                CASE
                    WHEN hargas.jenis LIKE '%BAYI%' THEN 'LAUNDRY BAYI'
                    WHEN hargas.jenis LIKE '%SYARIAH%' THEN 'LAUNDRY SYARIAH'
                    ELSE hargas.jenis
                END as jenis_grouped,
                COALESCE(SUM(transaksis.jumlah_lembar_baju), 0) as total_pcs
            ")
            ->groupByRaw('bulan, jenis_grouped')
            ->orderBy('bulan')
            ->get();

        // Laporan Laundry Biasa per customer per tahun
        $laporanKgCustomer = Transaksi::selectRaw('
            customer_id,
            YEAR(created_at) as tahun,
            SUM(kg) as total_kg,
            SUM(jumlah_lembar_baju) as total_pcs,
            SUM(harga_akhir) as total_harga
        ')
            ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->whereYear('created_at', $tahun)
            ->groupBy('customer_id', 'tahun')
            ->with('customers')
            ->get();

        // Laporan Transaksi Satuan per customer per tahun
        $laporanSatuanCustomer = TransaksiSatuan::selectRaw('
            customer_id,
            YEAR(transaksi_satuans.created_at) as tahun,
            SUM(transaksi_satuan_details.pcs) as total_pcs,
            SUM(transaksi_satuans.harga_akhir) as total_harga
        ')
            ->join('transaksi_satuan_details', 'transaksi_satuans.id', '=', 'transaksi_satuan_details.transaksi_satuan_id')
            ->whereIn('transaksi_satuans.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->whereYear('transaksi_satuans.created_at', $tahun)
            ->groupBy('customer_id', 'tahun')
            ->with('customers')
            ->get();

        // Kinerja Karyawan Reguler per tahun
        $laporanKaryawanReguler = Transaksi::selectRaw('
            karyawan_id,
            YEAR(created_at) as tahun,
            SUM(kg) as total_kg,
            SUM(jumlah_lembar_baju) as total_lembar
        ')
            ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->whereYear('created_at', $tahun)
            ->groupBy('karyawan_id', 'tahun')
            ->with('karyawan')
            ->get();

        // Kinerja Karyawan Satuan per tahun
        $laporanKaryawanSatuan = TransaksiSatuan::selectRaw('
            karyawan_id,
            YEAR(transaksi_satuans.created_at) as tahun,
            SUM(transaksi_satuan_details.pcs) as total_lembar
        ')
            ->join('transaksi_satuan_details', 'transaksi_satuans.id', '=', 'transaksi_satuan_details.transaksi_satuan_id')
            ->whereIn('transaksi_satuans.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->whereYear('transaksi_satuans.created_at', $tahun)
            ->groupBy('karyawan_id', 'tahun')
            ->with('karyawan')
            ->get();

        return view('modul_admin.laporan.tahunan', compact(
            'laporanKgCustomer',
            'laporanSatuanCustomer',
            'laporanKaryawanReguler',
            'laporanKaryawanSatuan',
            'jumlahKg',
            'tahun',
            'detailKgPerJenis',
            'detailPcsPerJenis',
            'kgPerBulanPerJenis',
            'pcsPerBulanPerJenis',
        ));
    }

    public function total()
    {
        // Total Kg
        $jumlahKg = Transaksi::whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->sum('kg');

        $detailKgPerJenis = DB::table('hargas')
            ->leftJoin('transaksis', function ($join) {
                $join->on('transaksis.harga_id', '=', 'hargas.id')
                    ->whereIn('transaksis.status_order', ['Antrian', 'Process', 'Done', 'Delivery']);
                // Tidak pakai whereYear atau filter tanggal
            })
            ->selectRaw("
                CASE
                    WHEN hargas.jenis LIKE '%BAYI%' THEN 'LAUNDRY BAYI'
                    WHEN hargas.jenis LIKE '%SYARIAH%' THEN 'LAUNDRY SYARIAH'
                    ELSE hargas.jenis
                END as jenis_grouped,
                COALESCE(SUM(transaksis.kg), 0) as total_kg
            ")
            ->groupBy('jenis_grouped')
            ->orderBy('jenis_grouped')
            ->get();

        $detailPcsPerJenis = DB::table('hargas')
            ->leftJoin('transaksis', function ($join) {
                $join->on('transaksis.harga_id', '=', 'hargas.id')
                    ->whereIn('transaksis.status_order', ['Antrian', 'Process', 'Done', 'Delivery']);
                // Tidak pakai whereYear atau filter tanggal
            })
            ->selectRaw("
                CASE
                    WHEN hargas.jenis LIKE '%BAYI%' THEN 'LAUNDRY BAYI'
                    WHEN hargas.jenis LIKE '%SYARIAH%' THEN 'LAUNDRY SYARIAH'
                    ELSE hargas.jenis
                END as jenis_grouped,
                COALESCE(SUM(transaksis.jumlah_lembar_baju), 0) as total_pcs
            ")
            ->groupBy('jenis_grouped')
            ->orderBy('jenis_grouped')
            ->get();

        // Total Laundry Biasa per customer (seluruh data)
        $laporanKgCustomer = Transaksi::selectRaw('
            customer_id,
            SUM(kg) as total_kg,
            SUM(jumlah_lembar_baju) as total_pcs,
            SUM(harga_akhir) as total_harga
        ')
            ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->groupBy('customer_id')
            ->with('customers')
            ->get();

        // Total Transaksi Satuan per customer (seluruh data)
        $laporanSatuanCustomer = TransaksiSatuan::selectRaw('
            customer_id,
            SUM(transaksi_satuan_details.pcs) as total_pcs,
            SUM(transaksi_satuans.harga_akhir) as total_harga
        ')
            ->join('transaksi_satuan_details', 'transaksi_satuans.id', '=', 'transaksi_satuan_details.transaksi_satuan_id')
            ->whereIn('transaksi_satuans.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->groupBy('customer_id')
            ->with('customers')
            ->get();

        // Kinerja Karyawan Reguler total
        $laporanKaryawanReguler = Transaksi::selectRaw('karyawan_id, SUM(kg) as total_kg, SUM(jumlah_lembar_baju) as total_lembar')
            ->whereIn('status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->groupBy('karyawan_id')
            ->with('karyawan')
            ->get();

        // Kinerja Karyawan Satuan total
        $laporanKaryawanSatuan = TransaksiSatuan::selectRaw('
            karyawan_id,
            SUM(transaksi_satuan_details.pcs) as total_lembar
        ')
            ->join('transaksi_satuan_details', 'transaksi_satuans.id', '=', 'transaksi_satuan_details.transaksi_satuan_id')
            ->whereIn('transaksi_satuans.status_order', ['Antrian', 'Process', 'Done', 'Delivery'])
            ->groupBy('karyawan_id')
            ->with('karyawan')
            ->get();

        return view('modul_admin.laporan.total', compact(
            'laporanKgCustomer',
            'laporanSatuanCustomer',
            'laporanKaryawanReguler',
            'laporanKaryawanSatuan',
            'jumlahKg',
            'detailKgPerJenis',
            'detailPcsPerJenis'
        ));
    }
}
