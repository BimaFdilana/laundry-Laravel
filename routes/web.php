<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\KuotaController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\NotifikasiController;
use App\Http\Controllers\SuperAdmin\PaketController;
use App\Http\Controllers\Admin\PurchaseRequestController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksiController;
use App\Http\Controllers\Admin\TransaksiSatuanController;
use App\Http\Controllers\Customer\GiftController;
use App\Http\Controllers\Customer\NotifikasiController as CustomerNotifikasiController;
use App\Http\Controllers\Customer\PurchaseController;
use App\Http\Controllers\Customer\TransaksiController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SuperAdmin\InventarisController;
use App\Http\Controllers\SuperAdmin\LaporanController;
use App\Http\Controllers\SuperAdmin\NotifikasiController as SuperAdminNotifikasiController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SuperAdmin\TransaksiController as SuperAdminTransaksiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'FrontController@index');

// Frontend
Route::get('pencarian-laundry', 'FrontController@search');

Auth::routes([
    'register' => false,
]);

Route::get('/transaksi-customer/invoice/{invoice}', [InvoiceController::class, 'invoice'])->name('customer.invoice');
Route::get('/transaksi-satuan-customer/invoice/{invoice}', [InvoiceController::class, 'invoicesatuan'])->name('customer.invoicesatuan');

Route::middleware('auth')->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');

    // Modul Super Admin
    Route::prefix('/')->middleware('role:SuperAdmin')->group(function () {
        // Setting
        Route::get('setting-superadmin', 'SuperAdmin\SettingsController@setting');
        Route::put('superadmin/set-theme/{id}', 'SuperAdmin\SettingsController@set_theme')->name('superadmin-setting-theme.update');
        Route::put('set-target-laundry/{id}', 'SuperAdmin\SettingsController@set_target_laundry')->name('set-target.update');
        Route::put('set-target-finance/{id}', 'SuperAdmin\SettingsController@update_target_finance')->name('set-target-finance.update');

        // Route untuk ganti password
        Route::post('profile-superadmin-change-password', [SuperAdminController::class, 'changePassword'])->name('profile.superadmin.changePassword');

        // Notifikasi
        Route::post('/superadmin/notif-readall', [SuperAdminNotifikasiController::class, 'readAllNotif'])->name('superadmin.notif.readall');

        // Profile
        Route::get('profile-superadmin/{id}', 'SuperAdmin\SuperAdminController@profile');
        Route::get('profile-superadmin-edit', 'SuperAdmin\SuperAdminController@edit_profile');

        // Finance
        Route::get('data-finance', 'SuperAdmin\SuperAdminController@finance');
        Route::get('data-finance', [SuperAdminController::class, 'finance'])->name('superadmin.finance');
        Route::resource('pengeluaran', 'SuperAdmin\PengeluaranController');
        Route::resource('pemasukan', 'SuperAdmin\PemasukanController');

        // Laporan
        Route::get('/laporan/harian', [LaporanController::class, 'harian'])->name('laporan.harian');
        Route::get('/laporan/bulanan', [LaporanController::class, 'bulanan'])->name('laporan.bulanan');
        Route::get('/laporan/tahunan', [LaporanController::class, 'tahunan'])->name('laporan.tahunan');
        Route::get('/laporan/total', [LaporanController::class, 'total'])->name('laporan.total');

        // Admin
        Route::resource('kelola-admin', 'SuperAdmin\AdminController');

        // Karyawan
        Route::resource('karyawan', 'SuperAdmin\KaryawanController');
        Route::resource('bintang', 'SuperAdmin\BintangKaryawanController');

        // Customer
        Route::resource('supercustomer', 'SuperAdmin\CustomerController');

        // Layanan
        Route::get('data-harga', 'SuperAdmin\SuperAdminController@dataharga');
        Route::post('harga-store', 'SuperAdmin\SuperAdminController@hargastore');
        Route::put('edit-harga', 'SuperAdmin\SuperAdminController@hargaedit')->name('edit-harga');
        // Layanan Satuan
        Route::get('data-satuan', 'SuperAdmin\SuperAdminController@datasatuan');
        Route::post('satuan-store', 'SuperAdmin\SuperAdminController@satuanstore');
        Route::put('edit-satuan', 'SuperAdmin\SuperAdminController@satuanedit')->name('edit-satuan');

        // Paket
        Route::resource('paket', 'SuperAdmin\PaketController');
        Route::put('/superadmin/paket/{id}', [PaketController::class, 'update'])->name('paket.update');
        Route::delete('/superadmin/paket/{id}', [PaketController::class, 'destroy'])->name('paket.destroy');

        // Inventaris
        Route::resource('kategori', 'SuperAdmin\KategoriController');
        Route::get('inventaris', [InventarisController::class, 'index'])->name('inventaris.index');
        Route::get('inventaris/create', [InventarisController::class, 'create'])->name('inventaris.create');
        Route::post('inventaris', [InventarisController::class, 'store'])->name('inventaris.store');
        Route::get('inventaris/{id}/edit', [InventarisController::class, 'edit'])->name('inventaris.edit');
        Route::put('inventaris/{id}', [InventarisController::class, 'update'])->name('inventaris.update');
        Route::delete('inventaris/{id}', [InventarisController::class, 'destroy'])->name('inventaris.destroy');

        // Transaksi
        Route::get('/superadmin/transaksi', [SuperAdminTransaksiController::class, 'index'])->name('superadmin.transaksi');
        Route::get('/superadmin/transaksi-satuan', [SuperAdminTransaksiController::class, 'satuan'])->name('superadmin.transaksisatuan');
        Route::delete('/superadmin/transaksi/{id}', [SuperAdminTransaksiController::class, 'destroy'])->name('superadmin.transaksi.destroy');
        Route::delete('/superadmin/transaksi-satuan/{id}', [SuperAdminTransaksiController::class, 'destroysatuan'])->name('superadmin.transaksisatuan.destroy');
    });

    // Modul Admin
    Route::prefix('/')->middleware('role:Admin')->group(function () {
        Route::resource('admin', 'Admin\AdminController');

        // Route untuk ganti password
        Route::post('profile-admin-change-password', [AdminController::class, 'changePassword'])->name('profile.admin.changePassword');

        // Customer
        Route::resource('customer', 'Admin\CustomerController');

        // Kuota Customer
        Route::resource('kuota', 'Admin\KuotaController');
        Route::get('/kuota/create', [KuotaController::class, 'create'])->name('kuota.create');
        Route::post('/kuota', [KuotaController::class, 'store'])->name('kuota.store');

        // Data Transaksi
        Route::resource('transaksi', 'Admin\TransaksiController');
        Route::get('transaksisatuan', [AdminTransaksiController::class, 'indexsatuan'])->name('transaksi.indexsatuan');
        Route::get('add-order', 'Admin\PelayananController@addorders');

        // Transaksi Satuan
        Route::resource('transaksi-satuan', 'Admin\TransaksiSatuanController');
        Route::get('add-order-satuan', 'Admin\TransaksiSatuanController@create');
        Route::get('ubah-status-order-satuan', 'Admin\TransaksiSatuanController@ubahstatusorder');
        Route::get('ubah-status-bayar-satuan', 'Admin\TransaksiSatuanController@ubahstatusbayar');
        Route::post('update-ket-delivery-satuan', 'Admin\TransaksiSatuanController@updateKetDelivery');

        // Pelayanan
        Route::resource('pelayanan', 'Admin\PelayananController');
        Route::get('ubah-status-order', 'Admin\PelayananController@ubahstatusorder');
        Route::get('ubah-status-bayar', 'Admin\PelayananController@ubahstatusbayar');
        Route::post('update-ket-delivery', 'Admin\PelayananController@updateKetDelivery');

        // Print Transaksi
        Route::get('/transaksi/print/{id}', [AdminTransaksiController::class, 'print'])->name('transaksi.print');
        Route::get('/transaksi-satuan/print/{id}', [TransaksiSatuanController::class, 'print'])->name('transaksi-satuan.print');

        // Filter
        Route::get('listharga', 'Admin\PelayananController@listharga');
        Route::get('listhari', 'Admin\PelayananController@listhari');

        // Customer
        Route::get('list-customer', 'Admin\PelayananController@listcs');
        Route::get('list-customer-add', 'Admin\PelayananController@listcsadd');
        Route::post('list-costomer-store', 'Admin\PelayananController@addcs');

        // Invoice
        Route::get('invoice-customer/{invoice}', 'Admin\TransaksiController@invoice');
        Route::get('invoice-satuan-customer/{invoice}', 'Admin\TransaksiSatuanController@invoice');
        Route::get('/transaksi/{id}/print-invoice', [AdminTransaksiController::class, 'printInvoice'])->name('transaksi.print_invoice');
        Route::get('/transaksi-satuan/{id}/print-invoice', [TransaksiSatuanController::class, 'printInvoice'])->name('transaksi_satuan.print_invoice');

        // Setting
        Route::get('settings', 'Admin\SettingsController@setting');
        Route::put('proses-setting-page/{id}', 'Admin\SettingsController@proses_set_page')->name('seting-page.update');
        Route::put('set-theme/{id}', 'Admin\SettingsController@set_theme')->name('setting-theme.update');

        // Profile
        Route::get('profile-admin/{id}', 'Admin\AdminController@profile');
        Route::get('profile-admin-edit', 'Admin\AdminController@edit_profile');

        // Paket
        Route::resource('konfirmasi', 'Admin\PurchaseRequestController');
        Route::post('/admin/konfirmasi/{id}', [PurchaseRequestController::class, 'confirm'])->name('konfirmasi.confirm');
        Route::delete('/admin/purchase-request/{id}', [PurchaseRequestController::class, 'destroy'])->name('konfirmasi.destroy');

        // Notifikasi
        Route::post('/admin/notif-readall', [NotifikasiController::class, 'readAllNotif'])->name('admin.notif.readall');

        // Gift
        Route::resource('gift', 'Admin\GiftController');

        // Laporan
        Route::get('/admin-laporan/harian', [AdminLaporanController::class, 'harian'])->name('admin-laporan.harian');
        Route::get('/admin-laporan/bulanan', [AdminLaporanController::class, 'bulanan'])->name('admin-laporan.bulanan');
        Route::get('/admin-laporan/tahunan', [AdminLaporanController::class, 'tahunan'])->name('admin-laporan.tahunan');
        Route::get('/admin-laporan/total', [AdminLaporanController::class, 'total'])->name('admin-laporan.total');
    });

    // Modul Customer
    Route::prefix('/')->middleware('role:Customer')->group(function () {
        // Profile
        Route::get('profile-customer/{id}', 'Customer\ProfileController@customerProfile');
        Route::get('profile-customer/edit/{id}', 'Customer\ProfileController@customerProfileEdit');
        Route::put('profile-customer/update/{id}', 'Customer\ProfileController@customerProfileSave');
        Route::put('change-password/{id}', 'Customer\ProfileController@change_password')->name('change.password');

        // Transaksi
        Route::resource('transaksi-customer', 'Customer\TransaksiController');

        // Paket
        Route::resource('paket-customer', 'Customer\PaketController');
        Route::post('/api/purchase-request', [PurchaseController::class, 'store']);

        // Gift
        Route::resource('gift-customer', 'Customer\GiftController');
        Route::patch('/customer/gift/{gift}/read', [GiftController::class, 'markAsRead'])->name('customer.gift.read');

        // Setting
        Route::get('setting-customer', 'Customer\SettingsController@setting');
        Route::put('proses-setting-customer/{id}', 'Customer\SettingsController@proses_setting_customer')->name('proses-setting-customer.update');

        // Notifikasi
        Route::post('/customer/notif-readall', [CustomerNotifikasiController::class, 'readAllNotif'])->name('customer.notif.readall');

        Route::patch('/customer/hide-transaction/{id}', [TransaksiController::class, 'hideTransaction'])->name('customer.hide-transaction');
    });
});
