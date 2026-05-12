<header>
    @php
        $setpage = \App\Models\PageSettings::first();
    @endphp
    <style>
        .header-area {
            width: 100%;
            z-index: 999;
            background: transparent;
            transition: all 0.4s ease;
        }

        .main-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 40px;
            min-height: 80px;
        }

        .logo {
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .menu-wrapper {
            flex-grow: 1;
            display: flex;
            justify-content: center;
        }

        #navigation {
            display: flex;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        #navigation li {
            position: relative;
            padding: 10px 20px;
        }

        #navigation li a {
            color: #264653;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
            text-decoration: none;
            display: block;
            transition: color 0.3s ease;
        }

        #navigation li a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            background: #0fb9b1;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            transition: width 0.3s ease;
            border-radius: 10px;
        }

        #navigation li:hover a::after,
        #navigation li.active a::after {
            width: 30px;
        }

        #navigation li:hover a,
        #navigation li.active a {
            color: #0fb9b1 !important;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-shrink: 0;
        }

        .header-btn1 {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #264653 !important;
            font-weight: 700;
            background: rgba(15, 185, 177, 0.1);
            padding: 12px 18px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .header-btn1:hover {
            background: #0fb9b1;
            color: white !important;
        }

        .header-btn1 i {
            font-size: 16px;
            color: #0fb9b1;
        }

        .header-btn1:hover i {
            color: white;
        }

        .header-btn2 {
            background: #264653;
            color: white !important;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: 0.3s;
            border: 2px solid #264653;
        }

        .header-btn2:hover {
            background: transparent;
            color: #264653 !important;
        }

        .header-sticky.sticky-bar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(15px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
            }

            to {
                transform: translateY(0);
            }
        }

        @media (max-width: 991px) {
            .main-header {
                padding: 10px 20px;
            }

            .header-right,
            .main-menu {
                display: none !important;
            }
        }
    </style>

    <div class="header-area">
        <div class="main-header header-sticky">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('frontend/img/logo.jpeg') }}" alt="Laundry Camp Logo">
                </a>
            </div>
            <div class="menu-wrapper">
                <div class="main-menu d-none d-lg-block">
                    <nav>
                        <ul id="navigation">
                            <li><a href="#home">Home</a></li>
                            <li><a href="#services">Services</a></li>
                            <li><a href="#pricing">Pricing</a></li>
                            <li><a href="#about">About</a></li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="header-right d-none d-lg-flex">
                <a href="https://wa.me/{{ $setpage->whatsapp }}" target="_blank" class="header-btn1">
                    <i class="fab fa-whatsapp"></i>
                    <span>+{{ $setpage->whatsapp }}</span>
                </a>
                @auth
                    <a href="{{ route('home') }}" class="header-btn2">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="header-btn2">Login</a>
                @endauth
            </div>

            <div class="mobile_menu d-block d-lg-none"></div>
        </div>
    </div>
</header>