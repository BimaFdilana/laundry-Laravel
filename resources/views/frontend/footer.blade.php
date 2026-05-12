<footer>
    @php
        $setpage = \App\Models\PageSettings::first();
    @endphp
    <style>
        /* Modern Solid Footer */
        .footer-area {
            background: #122a28;
            /* Warna Hijau Tua yang Solid & Mewah */
            color: #ffffff;
            padding: 80px 0 50px;
            position: relative;
        }

        /* Logo Styling */
        .footer-logo img {
            border-radius: 16px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 25px;
            transition: 0.3s;
        }

        /* Typography & Contrast */
        .footer-tittle h4 {
            color: #ffffff !important;
            /* Putih bersih agar tidak beradu */
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
        }

        .footer-tittle h4::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            width: 35px;
            height: 3px;
            background: #0fb9b1;
            /* Aksen Emerald */
        }

        .footer-pera p {
            color: #bdc3c7 !important;
            /* Warna perak terang agar enak dibaca */
            line-height: 1.8;
            font-size: 15px;
            max-width: 300px;
        }

        /* List Styling */
        .footer-tittle ul {
            padding: 0;
            list-style: none;
        }

        .footer-tittle ul li {
            margin-bottom: 12px;
        }

        .footer-tittle ul li a {
            color: #bdc3c7 !important;
            /* Warna perak terang */
            text-decoration: none;
            transition: 0.3s ease;
            font-size: 15px;
            display: inline-block;
        }

        .footer-tittle ul li a:hover {
            color: #0fb9b1 !important;
            /* Hijau saat hover */
            transform: translateX(8px);
        }

        /* Contact Box Fix */
        .contact-info-box {
            background: rgba(255, 255, 255, 0.03);
            padding: 20px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .contact-info-box a {
            color: #ffffff !important;
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            margin-bottom: 10px;
        }

        .contact-info-box i {
            color: #0fb9b1;
            font-size: 18px;
        }

        /* Social Icons */
        .footer-social {
            margin-top: 25px;
            display: flex;
            gap: 12px;
        }

        .footer-social a {
            width: 42px;
            height: 42px;
            background: rgba(15, 185, 177, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: #ffffff !important;
            transition: 0.3s;
            border: 1px solid rgba(15, 185, 177, 0.2);
        }

        .footer-social a:hover {
            background: #0fb9b1;
            color: white !important;
            transform: translateY(-5px);
        }

        /* Footer Bottom Bar */
        .footer-bottom-area {
            background: #0d1f1d;
            /* Lebih gelap dari main footer */
            padding: 25px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer-copy-right p {
            color: #7f8c8d !important;
            font-size: 14px;
            margin-bottom: 0;
        }

        .footer-copy-right a {
            color: #0fb9b1 !important;
            text-decoration: none;
        }
    </style>

    <div class="footer-area">
        <div class="container">
            <div class="row justify-content-between">
                <!-- Brand Section -->
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                    <div class="single-footer-caption mb-50">
                        <div class="footer-logo">
                            <a href="{{ url('/') }}">
                                <img width="90px" src="{{ asset('frontend/img/logo.jpeg') }}" alt="Laundry Camp">
                            </a>
                        </div>
                        <div class="footer-pera">
                            <p>Memberikan perawatan terbaik untuk setiap helai kain Anda dengan teknologi modern dan
                                sistem otomasi terpercaya.</p>
                        </div>
                        <div class="footer-social">
                            <a href="{{ $setpage->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="{{ $setpage->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
                            <a href="{{ $setpage->facebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        </div>
                    </div>
                </div>
                <!-- Services Section -->
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                    <div class="single-footer-caption mb-50">
                        <div class="footer-tittle">
                            <h4>Layanan Utama</h4>
                            <ul>
                                <li><a href="#services">Cuci Komplit Kilat</a></li>
                                <li><a href="#services">Setrika Uap & Lipat</a></li>
                                <li><a href="#services">Laundry Satuan Jas</a></li>
                                <li><a href="#services">Layanan Baby Care</a></li>
                                <li><a href="#services">Laundry Karpet & Boneka</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                    <div class="single-footer-caption mb-50">
                        <div class="footer-tittle">
                            <h4>Hubungi Kami</h4>
                        </div>
                        <div class="contact-info-box">
                            <a href="https://wa.me/{{ $setpage->whatsapp }}" target="_blank">
                                <i class="fab fa-whatsapp"></i> +{{ $setpage->whatsapp }}
                            </a>
                            <a href="mailto:{{ $setpage->email }}" target="_blank">
                                <i class="far fa-envelope"></i> {{ $setpage->email }}
                            </a>
                            <p class="mt-3 mb-0" style="font-size: 14px; color: #bdc3c7;">
                                <i class="fas fa-map-marker-alt text-emerald mr-2"></i>
                                Bengkalis, Riau, Indonesia
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bottom Copybar -->
    <div class="footer-bottom-area">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-xl-12">
                    <div class="footer-copy-right">
                        <p>
                            &copy;
                            <script>document.write(new Date().getFullYear());</script>
                            <strong>Laundry Camp</strong>. Dipersembahkan oleh
                            <a href="{{ url('/') }}">Indonesia Maxima Teknologi</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>