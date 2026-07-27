@extends('layouts.backend')
@section('title', 'Panduan Fitur Baru')
@section('header', 'Panduan Fitur Baru')
@section('content')
    <div class="content-body">
        <div class="row">
            <div class="col-12 col-xl-10">
                <!-- Card Header -->
                <div class="card shadow-sm border-0 mb-2">
                    <div class="card-body p-3 text-center bg-light-primary rounded">
                        <h2 class="font-weight-bold text-primary mb-50"><i class="feather icon-book-open mr-50"></i>Pusat Panduan & Dokumentasi Fitur</h2>
                        <p class="text-muted mb-0">Halaman panduan khusus untuk membantu Anda mempelajari cara kerja dan alur penggunaan fitur-fitur baru aplikasi laundry.</p>
                    </div>
                </div>

                <!-- Accordion / List of Features -->
                <div class="accordion" id="accordionPanduan">
                    
                    <!-- 1. Detail History Kuota -->
                    <div class="card shadow-sm border-0 mb-1">
                        <div class="card-header border-bottom-0 pb-1" id="headingOne">
                            <h4 class="mb-0">
                                <button class="btn btn-link btn-block text-left font-weight-bold text-dark d-flex align-items-center justify-content-between collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="text-decoration: none; padding: 0;">
                                    <span><i class="feather icon-clock mr-1 text-primary"></i>1. Riwayat Transaksi Kuota Laundry (History Kuota)</span>
                                    <i class="feather icon-chevron-down font-medium-3 toggle-icon"></i>
                                </button>
                            </h4>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionPanduan">
                            <div class="card-body pt-0 text-dark">
                                <p>Admin kini dapat memantau riwayat keluar-masuk penggunaan kuota laundry milik customer secara transparan:</p>
                                <ul>
                                    <li>Fitur ini dapat diakses melalui menu <strong>Data Customer</strong> (atau <strong>Kuota Customer</strong>).</li>
                                    <li>Klik tombol <strong>Detail</strong> pada kolom aksi di samping nama customer terpilih.</li>
                                    <li>Sistem akan menampilkan pop-up modal interaktif yang menampilkan seluruh log aktivitas kuota (penambahan kuota, pengurangan akibat pemakaian transaksi, tanggal transaksi, serta admin/kasir yang memprosesnya).</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Sinkronisasi Jumlah Kuota -->
                    <div class="card shadow-sm border-0 mb-1">
                        <div class="card-header border-bottom-0 pb-1" id="headingTwo">
                            <h4 class="mb-0">
                                <button class="btn btn-link btn-block text-left font-weight-bold text-dark d-flex align-items-center justify-content-between collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="text-decoration: none; padding: 0;">
                                    <span><i class="feather icon-refresh-cw mr-1 text-info"></i>2. Otomatisasi Input Jumlah Kuota & Paket Laundry</span>
                                    <i class="feather icon-chevron-down font-medium-3 toggle-icon"></i>
                                </button>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionPanduan">
                            <div class="card-body pt-0 text-dark">
                                <p>Proses pengisian kuota laundry customer kini berjalan lebih akurat dan terintegrasi:</p>
                                <ul>
                                    <li>Pada halaman <strong>Tambah Kuota Customer</strong>, kolom pilihan **Jumlah Kuota** kini otomatis menyesuaikan dengan data **Paket Laundry** aktif.</li>
                                    <li>Data paket laundry tersebut diinput dan dikelola sepenuhnya oleh Super Admin, sehingga Kasir/Admin di lapangan terhindar dari salah input jumlah kuota atau harga beli kuota.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Sistem Voucher Diskon -->
                    <div class="card shadow-sm border-0 mb-1">
                        <div class="card-header border-bottom-0 pb-1" id="headingThree">
                            <h4 class="mb-0">
                                <button class="btn btn-link btn-block text-left font-weight-bold text-dark d-flex align-items-center justify-content-between collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="text-decoration: none; padding: 0;">
                                    <span><i class="feather icon-tag mr-1 text-success"></i>3. Manajemen Diskon dengan Sistem Voucher</span>
                                    <i class="feather icon-chevron-down font-medium-3 toggle-icon"></i>
                                </button>
                            </h4>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionPanduan">
                            <div class="card-body pt-0 text-dark">
                                <p>Sistem pencatatan diskon kini dimodernisasi menggunakan sistem voucher digital terpusat:</p>
                                <ul>
                                    <li>Diskon tidak lagi diinput secara manual bebas, melainkan dapat memilih kode voucher aktif yang diatur oleh Super Admin di menu **Voucher Diskon**.</li>
                                    <li>Kasir cukup memilih voucher diskon saat checkout transaksi kiloan, satuan, maupun beli kuota, dan nominal diskon otomatis akan terisi secara instan dan memotong total tagihan.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Aktivitas Karyawan -->
                    <div class="card shadow-sm border-0 mb-1">
                        <div class="card-header border-bottom-0 pb-1" id="headingFour">
                            <h4 class="mb-0">
                                <button class="btn btn-link btn-block text-left font-weight-bold text-dark d-flex align-items-center justify-content-between collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour" style="text-decoration: none; padding: 0;">
                                    <span><i class="feather icon-user-check mr-1 text-warning"></i>4. Pelacakan Kinerja (Aktivitas Karyawan)</span>
                                    <i class="feather icon-chevron-down font-medium-3 toggle-icon"></i>
                                </button>
                            </h4>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionPanduan">
                            <div class="card-body pt-0 text-dark">
                                <p>Monitor produktivitas dan kontribusi karyawan di lapangan secara transparan:</p>
                                <ul>
                                    <li>Setiap pengerjaan transaksi (pencucian, penyetrikaan, penyelesaian) akan otomatis mengikat nama karyawan yang memprosesnya.</li>
                                    <li>Memudahkan Super Admin dalam mengukur beban kerja masing-masing staf secara adil dan akurat sesuai data riil transaksi di lapangan.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->auth == 'SuperAdmin')
                        <!-- 5. Menu Target ke Sidebar (SuperAdmin Only) -->
                        <div class="card shadow-sm border-0 mb-1">
                            <div class="card-header border-bottom-0 pb-1" id="headingFive">
                                <h4 class="mb-0">
                                    <button class="btn btn-link btn-block text-left font-weight-bold text-dark d-flex align-items-center justify-content-between collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive" style="text-decoration: none; padding: 0;">
                                        <span><i class="feather icon-target mr-1 text-danger"></i>5. Pemisahan Menu Target ke Sidebar (Khusus Super Admin)</span>
                                        <i class="feather icon-chevron-down font-medium-3 toggle-icon"></i>
                                    </button>
                                </h4>
                            </div>
                            <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionPanduan">
                                <div class="card-body pt-0 text-dark">
                                    <p>Menu pengaturan target kini telah dipisahkan dari halaman pengaturan umum:</p>
                                    <ul>
                                        <li>Memudahkan Super Admin dalam memantau dan mengubah target operasional secara berkala melalui menu sidebar induk <strong>Target</strong>.</li>
                                        <li>Terbagi menjadi dua submenu utama: **Target Keuangan** (untuk target omzet nominal rupiah) dan **Target Laundry** (untuk target berat cucian masuk dalam Kg).</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        .accordion .card-header .btn-link i.toggle-icon {
            transition: transform 0.2s ease-in-out;
        }
        .accordion .card-header .btn-link:not(.collapsed) i.toggle-icon {
            transform: rotate(180deg);
        }
    </style>
@endsection
