@extends('layouts.frontend')
@section('title', 'Laundry Camp')
@section('styles')
    <style>
        html {
            scroll-behavior: smooth;
        }

        :root {
            --primary-color: #0fb9b1;
            --dark-color: #264653;
            --dark2-color: #1a3847;
            --accent-color: #f4a261;
            --soft-bg: #f0f7f6;
            --glass-bg: rgba(255, 255, 255, 0.85);
        }

        .hero-overly {
            background: linear-gradient(rgba(38, 70, 83, 0.85), rgba(38, 70, 83, 0.85)), url('{{ asset("assets/img/gallery/section_bg01.png") }}');
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }

        .search-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            padding: 35px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .hero-image-wrapper {
            position: relative;
            z-index: 2;
            padding-left: 30px;
            animation: float 6s ease-in-out infinite;
        }

        .hero-image-container {
            position: relative;
            width: 100%;
            border-radius: 30px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            opacity: 0.85;
        }

        .hero-img-slide {
            width: 100%;
            display: block;
            object-fit: cover;
        }

        .hero-img-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            animation: crossfade 8s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        @keyframes crossfade {

            0%,
            40% {
                opacity: 0;
            }

            50%,
            90% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        .service-card-modern {
            background: var(--glass-bg);
            backdrop-filter: blur(5px);
            border-radius: 24px;
            padding: 45px 35px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(15, 185, 177, 0.1);
            height: 100%;
        }

        .service-card-modern:hover {
            transform: translateY(-15px);
            background: white;
            box-shadow: 0 25px 50px rgba(15, 185, 177, 0.15);
            border-color: var(--primary-color);
        }

        .service-icon-wrapper {
            width: 90px;
            height: 90px;
            background: #e6f7f6;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            transform: rotate(-5deg);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .service-icon-wrapper img {
            width: 45px;
            height: auto;
            transition: all 0.3s ease;
        }

        .service-card-modern:hover .service-icon-wrapper {
            transform: rotate(0deg) scale(1.1);
            background: var(--primary-color);
            box-shadow: 0 10px 20px rgba(15, 185, 177, 0.3);
        }

        .pricing-card {
            transition: all 0.4s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
        }

        .price-item {
            padding: 15px 0;
            border-bottom: 1px solid #f1f1f1;
            transition: 0.3s;
        }

        .price-item:last-child {
            border-bottom: none;
        }

        .price-item:hover {
            padding-left: 10px;
            background: rgba(15, 185, 177, 0.02);
        }

        .price-tag {
            font-size: 1.2rem;
            color: var(--primary-color);
            font-weight: 800;
        }

        .unit {
            font-size: 0.8rem;
            color: #999;
            font-weight: 400;
        }

        .featured-pricing {
            background: var(--dark-color) !important;
            border: none !important;
        }

        .featured-pricing .price-item {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .featured-pricing .price-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .wa-btn-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            background: #ffffff;
            color: var(--primary-color) !important;
            padding: 18px 40px;
            border-radius: 20px;
            font-weight: 800;
            transition: 0.3s;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .wa-btn-container:hover {
            transform: scale(1.05);
            background: #f8f9fa;
        }

        .stat-box {
            background: white;
            padding: 40px;
            border-radius: 25px;
            border-bottom: 5px solid var(--primary-color);
            transition: 0.3s;
        }

        .stat-box:hover {
            background: var(--primary-color);
        }

        .stat-box:hover h2,
        .stat-box:hover p {
            color: white !important;
        }

        .about-img-container {
            position: relative;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
        }

        .about-experience {
            position: absolute;
            bottom: 30px;
            right: 30px;
            background: var(--primary-color);
            color: white;
            padding: 20px 30px;
            border-radius: 20px;
            text-align: center;
        }

        .location-image {
            height: 450px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .location-img-wrapper:hover .location-image {
            transform: scale(1.05);
        }
    </style>
@endsection

@section('content')
        @php
    $setpage = \App\Models\PageSettings::first();
        @endphp

        <section id="home" class="slider-area hero-overly">
            <div class="slider-active">
                <div class="single-slider slider-height d-flex align-items-center">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-xl-7 col-lg-7 col-md-11">
                                <div class="hero__caption">
                                    <span data-animation="fadeInLeft" data-delay="0.1s"
                                        class="text-white text-uppercase font-weight-bold" style="letter-spacing: 3px;">Premium
                                        Laundry Service</span>
                                    <h1 data-animation="fadeInLeft" data-delay="0.3s"
                                        style="font-weight: 900; font-size: 4.5rem; line-height: 1.1;" class="mb-20">Bersih
                                        Kilau, <br><span style="color: var(--primary-color);">Tanpa Ribet.</span></h1>

                                    <div class="search-container mt-40" data-animation="fadeInUp" data-delay="0.6s">
                                        <h4 class="text-white mb-4">Cek Status Laundry Anda</h4>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="search_status"
                                                placeholder="Masukkan Nomor Invoice (contoh: LC-260611-001)"
                                                style="height: 65px; border-radius: 15px 0 0 15px; border: none; padding-left: 30px; font-size: 1.1rem;">
                                            <button id="search-btn" class="btn"
                                                style="padding: 0 35px; border-radius: 0 15px 15px 0; background: var(--primary-color); color: white; border: none;">
                                                <i class="fas fa-search"></i> Lacak
                                            </button>
                                        </div>
                                    </div>
                                    @include('frontend.modal')
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-5 d-none d-lg-block">
                                <div class="hero-image-wrapper" data-animation="fadeInRight" data-delay="0.4s">
                                    <div class="hero-image-container">
                                        <img src="{{ asset('assets/img/gallery/offers11.png') }}" alt="" class="hero-img-slide">
                                        <img src="{{ asset('assets/img/gallery/offers22.png') }}" alt=""
                                            class="hero-img-slide hero-img-overlay">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="services" class="section-padding40" style="background: var(--soft-bg);">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-8">
                        <div class="section-tittle text-center mb-70">
                            <h2 style="font-weight: 800; color: var(--dark-color);">Layanan Kami</h2>
                            <p class="text-muted">Pilih paket layanan yang sesuai dengan kebutuhan Anda</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="service-card-modern">
                            <div class="service-icon-wrapper">
                                <img src="{{ asset('assets/img/icon/services-icon1.svg') }}" alt="Cuci Kiloan">
                            </div>
                            <h3 class="font-weight-bold mb-3" style="color: var(--dark2-color);">Cuci Kiloan</h3>
                            <p class="text-muted">Solusi hemat untuk pakaian harian. Dicuci bersih, dikeringkan sempurna, dan
                                dilipat rapi.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="service-card-modern">
                            <div class="service-icon-wrapper">
                                <img src="{{ asset('assets/img/icon/services-icon2.svg') }}" alt="Express">
                            </div>
                            <h3 class="font-weight-bold mb-3" style="color: var(--dark2-color);">Express 6 Jam</h3>
                            <p class="text-muted">Butuh cepat? Layanan express kami memastikan pakaian Anda siap dalam hitungan
                                jam.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="service-card-modern">
                            <div class="service-icon-wrapper">
                                <img src="{{ asset('assets/img/icon/services-icon3.svg') }}" alt="Satuan">
                            </div>
                            <h3 class="font-weight-bold mb-3" style="color: var(--dark2-color);">Cuci Satuan</h3>
                            <p class="text-muted">Perawatan khusus untuk Jas, Kebaya, Bedcover, dan bahan sensitif lainnya.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="pricing" class="section-padding40" style="background: white;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-8">
                        <div class="section-tittle text-center mb-70">
                            <span
                                style="color: var(--primary-color); font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">Daftar
                                Harga</span>
                            <h2 style="font-weight: 800; color: var(--dark-color);" class="mt-2">Investasi Untuk Pakaian Anda
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="row align-items-stretch">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="pricing-card"
                            style="background: var(--soft-bg); border-radius: 30px; padding: 45px 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); height: 100%;">
                            <div class="text-center mb-4">
                                <div
                                    style="background: white; width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                                    <i class="fa fa-balance-scale" style="color: var(--primary-color); font-size: 24px;"></i>
                                </div>
                                <h3 class="font-weight-bold" style="color: var(--dark-color);">Cuci Kiloan</h3>
                            </div>
                            <div class="price-list">
                                @foreach($hargas->take(6) as $harga)
                                    <div class="price-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="d-block font-weight-bold"
                                                style="color: var(--dark-color);">{{ $harga->nama }}</span>
                                            <small class="text-muted">{{ $harga->jenis }}</small>
                                        </div>
                                        <div class="text-right">
                                            <span class="price-tag">Rp {{ number_format($harga->harga, 0, ',', '.') }}</span>
                                            <span class="unit d-block">/kg</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-4">
                                <p class="mb-0" style="font-size: 13px; color: #777;"><i class="far fa-clock mr-1"></i> Estimasi
                                    selesai {{ $hargas->first() ? $hargas->first()->hari : 2 }} Hari</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="pricing-card featured-pricing"
                            style="border-radius: 30px; padding: 50px 35px; box-shadow: 0 30px 60px rgba(38,70,83,0.15); height: 100%; position: relative;">
                            <div
                                style="position: absolute; top: 20px; right: 20px; background: var(--accent-color); color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase;">
                                Hemat 20%</div>
                            <div class="text-center mb-4">
                                <div
                                    style="background: rgba(255,255,255,0.1); width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                                    <i class="fas fa-star" style="color: var(--accent-color); font-size: 24px;"></i>
                                </div>
                                <h3 class="font-weight-bold text-white">Paket Member</h3>
                                <p class="text-white-50" style="font-size: 14px;">Bayar di awal lebih hemat</p>
                            </div>
                            <div class="price-list">
                                @foreach($pakets->take(6) as $paket)
                                    <div class="price-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="d-block font-weight-bold text-white">{{ $paket->kg }} Kg</span>
                                            <small class="text-white-50">{{ $paket->kategori }}</small>
                                        </div>
                                        <div class="text-right">
                                            <span class="price-tag" style="color: var(--accent-color);">Rp
                                                {{ number_format($paket->harga, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <a href="https://wa.me/{{ $setpage->whatsapp }}" class="btn btn-block"
                                    style="background: var(--primary-color); color: white; border-radius: 15px; font-weight: 700; padding: 12px;">Beli
                                    Paket Member</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="pricing-card"
                            style="background: var(--soft-bg); border-radius: 30px; padding: 45px 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); height: 100%;">
                            <div class="text-center mb-4">
                                <div
                                    style="background: white; width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                                    <i class="fa fa-leaf" style="color: var(--primary-color); font-size: 24px;"></i>
                                </div>
                                <h3 class="font-weight-bold" style="color: var(--dark-color);">Cuci Satuan</h3>
                            </div>
                            <div class="price-list">
                                @foreach($satuans->take(6) as $satuan)
                                    <div class="price-item d-flex justify-content-between align-items-center">
                                        <span class="font-weight-bold" style="color: var(--dark-color);">{{ $satuan->nama }}</span>
                                        <div class="text-right">
                                            <span class="price-tag">Rp {{ number_format($satuan->harga, 0, ',', '.') }}</span>
                                            <span class="unit d-block">/pcs</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-4">
                                <p class="mb-0" style="font-size: 13px; color: #777;">Tersedia paket cuci Sepatu & Helm</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="section-padding40 bg-white">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-50">
                        <div class="about-img-container">
                            <img src="{{ asset('assets/img/gallery/18067.jpg') }}" class="w-100" alt="About Image">
                            <div class="about-experience">
                                <h3 class="text-white font-weight-bold mb-0">10+</h3>
                                <small>Tahun Pengalaman</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="section-tittle mb-35">
                            <span style="color: var(--primary-color); font-weight: 700;">TENTANG KAMI</span>
                            <h2 style="font-weight: 800; font-size: 2.5rem;" class="mt-10">Kami Peduli Terhadap Setiap Serat
                                Pakaian Anda</h2>
                        </div>
                        <p class="text-muted mb-30" style="font-size: 1.1rem; line-height: 1.8;">Laundry Camp hadir sebagai
                            solusi modern untuk kebutuhan laundry Anda. Kami menggunakan teknologi mesin terbaru yang menjamin
                            pakaian tidak hanya bersih, tapi juga awet.</p>

                        <ul class="list-unstyled mb-40">
                            <li class="mb-3"><i class="fas fa-check-circle mr-2 text-success"></i> Proses Pencucian Higienis
                            </li>
                            <li class="mb-3"><i class="fas fa-check-circle mr-2 text-success"></i> Penggunaan Air Terfilter</li>
                            <li class="mb-3"><i class="fas fa-check-circle mr-2 text-success"></i> Antar Jemput Tepat Waktu</li>
                        </ul>
                        </div>
                        </div>
                        </div>
                        </section>

                        <section class="container py-5">
                            <div class="rounded-lg shadow-lg"
                                style="background: linear-gradient(135deg, #0fb9b1 0%, #264653 100%); padding: 80px 50px; border-radius: 40px !important;">
                                <div class="row align-items-center">
                                    <div class="col-xl-8 col-lg-8 text-center text-lg-left mb-4 mb-lg-0">
                                        <h2 class="text-white font-weight-bold" style="font-size: 2.8rem;">Booking Via WhatsApp Sekarang</h2>
                                        <p class="text-white-50" style="font-size: 1.2rem;">Dapatkan kemudahan layanan jemput-antar langsung
                                            dari HP Anda.</p>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 text-center">
                                        <a href="https://wa.me/{{ $setpage->whatsapp }}" class="wa-btn-container" target="_blank">
                                            <i class="fab fa-whatsapp" style="color: #25D366; font-size: 2 rem;"></i>
                                            <span style="color: var(--dark-color);">Pesan Jemputan</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="section-padding40" style="background: var(--soft-bg);">
                            <div class="container">
                                <div class="row text-center">
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="stat-box shadow-sm">
                                            <h2 style="font-weight: 900; color: var(--dark-color); font-size: 3.5rem;">4k+</h2>
                                            <p class="mb-0 font-weight-bold text-muted text-uppercase">Pesanan Selesai</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="stat-box shadow-sm">
                                            <h2 style="font-weight: 900; color: var(--dark-color); font-size: 3.5rem;">100%</h2>
                                            <p class="mb-0 font-weight-bold text-muted text-uppercase">Garansi Bersih</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="stat-box shadow-sm">
                                            <h2 style="font-weight: 900; color: var(--dark-color); font-size: 3.5rem;">30+</h2>
                                            <p class="mb-0 font-weight-bold text-muted text-uppercase">Kurir Siaga</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="lokasi" class="section-padding40 bg-white">
                            <div class="container mb-5">
                                <div class="row justify-content-center">
                                    <div class="col-xl-7 col-lg-8">
                                        <div class="section-tittle text-center mb-60">
                                            <span
                                                style="color: var(--primary-color); font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">Lokasi
                                                Kami</span>
                                            <h2 style="font-weight: 800; color: var(--dark-color);" class="mt-2">Temukan Laundry Camp Terdekat
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <div class="location-img-wrapper"
                                            style="border-radius: 30px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                                            <img src="{{ asset('assets/img/gallery/offers22.png') }}" alt="Laundry Camp Location"
                                                class="w-100 location-image">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div
                                            style="border-radius: 30px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1); border: 6px solid white;">
                                            <iframe
                                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d997.1248874117696!2d102.12781025561767!3d1.4731863203183948!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d15ff3fa904125%3A0x33e9ffd8e560e40a!2sLaundry%20Camp!5e0!3m2!1sen!2sid!4v1750090758115!5m2!1sen!2sid"
                                                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
@endsection
                    
                    @section('scripts')
                        <script type="text/javascript">
                            $(document).on('click', '#search-btn', function (e) {
                                var search_status = $("#search_status").val();
                                if (!search_status) {
                                    swal({ icon: "info", text: "Silakan masukkan nomor invoice Anda." });
                                    return;
                                }
                                $.get('pencarian-laundry', {
                                    '_token': $('meta[name=csrf-token]').attr('content'),
                                    search_status: search_status
                                }, function (resp) {
                                    if (resp != 0) {
                                        $(".modal_status").show();
                                        $("#customer").html(resp.customer);
                                        $("#tgl_transaksi").html(resp.tgl_transaksi);
                                        $("#status_order").html(resp.status_order);
                                    } else {
                                        swal({ icon: "error", text: "Nomor invoice tidak ditemukan." });
                                    }
                                });
                            });
                            function close_dlgs() {
                                $(".modal_status").hide();
                                $("#search_status").val("");
                            }
                        </script>
                    @endsection