@extends('layouts.auth')
@section('title', 'Masuk')
@section('content')
    <style>
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }

        .login-left {
            background: linear-gradient(135deg, #0fb9b1 0%, #0a8f89 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            animation: pulse 8s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .login-left .brand-logo {
            position: relative;
            z-index: 1;
        }

        .login-left .brand-logo img {
            width: 160px;
            height: 160px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid rgba(255, 255, 255, 0.35);
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.25);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .login-left .brand-logo img:hover {
            transform: translateY(-6px) scale(1.04);
            box-shadow: 0 22px 55px rgba(0, 0, 0, 0.35);
        }

        .login-left h2 {
            position: relative;
            z-index: 1;
            font-size: 2.25rem;
            font-weight: 700;
            margin-top: 1.75rem;
            text-align: center;
            letter-spacing: 0.5px;
            color: #ffffff;
        }

        .login-left p {
            position: relative;
            z-index: 1;
            font-size: 1.05rem;
            opacity: 0.9;
            text-align: center;
            max-width: 340px;
            margin-top: 0.75rem;
            line-height: 1.6;
        }

        .login-features {
            position: relative;
            z-index: 1;
            margin-top: 2.5rem;
            width: 100%;
            max-width: 320px;
        }

        .login-features .feature-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.65rem 0;
            font-size: 0.95rem;
            opacity: 0.95;
        }

        .login-features .feature-item .feature-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            min-width: 38px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(4px);
        }

        .login-features .feature-item .feature-icon i {
            font-size: 18px;
            color: #fff;
        }

        .login-right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            background: #f8f9fc;
        }

        .login-form-container {
            width: 100%;
            max-width: 400px;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .brand-header img {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 24px;
            margin-bottom: 1.25rem;
            box-shadow: 0 12px 30px rgba(15, 185, 177, 0.25);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .brand-header img:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 18px 40px rgba(15, 185, 177, 0.35);
        }

        .login-form-container h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.25rem;
        }

        .login-form-container .subtitle {
            color: #718096;
            font-size: 0.95rem;
            margin-bottom: 0;
        }

        .form-group-custom {
            margin-bottom: 1.5rem;
        }

        .form-group-custom label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }

        .form-group-custom .input-wrapper {
            position: relative;
        }

        .form-group-custom .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 16px;
        }

        .form-group-custom .input-wrapper .toggle-pw {
            position: absolute;
            right: 14px;
            left: auto;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            cursor: pointer;
            font-size: 16px;
        }

        .form-group-custom .form-control {
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background: #fff;
            height: auto;
        }

        .form-group-custom .form-control:focus {
            border-color: #0fb9b1;
            box-shadow: 0 0 0 3px rgba(15, 185, 177, 0.15);
        }

        .form-group-custom .form-control.is-invalid {
            border-color: #e53e3e;
        }

        .forgot-link {
            display: block;
            text-align: right;
            font-size: 0.85rem;
            color: #0fb9b1;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .forgot-link:hover {
            color: #0a8f89;
            text-decoration: none;
        }

        .btn-login {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, #0fb9b1 0%, #0a8f89 100%);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(15, 185, 177, 0.35);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15, 185, 177, 0.5);
            color: #fff;
        }

        .login-footer-custom {
            margin-top: 2rem;
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .login-footer-custom .brand-name {
            font-weight: 700;
            color: #0fb9b1;
            font-size: 1rem;
        }

        .login-footer-custom .contact-text {
            font-size: 0.8rem;
            color: #718096;
            margin-top: 0.5rem;
        }

        .login-footer-custom .contact-text a {
            color: #0fb9b1;
            font-weight: 500;
        }

        @media (max-width: 991px) {
            .login-left {
                display: none;
            }
            .login-right {
                padding: 2rem 1.5rem;
            }
        }
    </style>

    <div class="login-wrapper">
        <div class="col-lg-6 col-12 login-left d-none d-lg-flex">
            <div class="brand-logo">
                <img src="{{ asset('frontend/img/logo.jpeg') }}" alt="LaundryCamp">
            </div>
            <h2>LaundryCamp</h2>
            <p>Kelola bisnis laundry kamu dengan lebih mudah, cepat, dan efisien.</p>
            <div class="login-features">
                <div class="feature-item">
                    <span class="feature-icon"><i class="feather icon-trending-up"></i></span>
                    <span>Pantau transaksi & laporan real-time</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon"><i class="feather icon-users"></i></span>
                    <span>Kelola pelanggan dengan rapi</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon"><i class="feather icon-clock"></i></span>
                    <span>Hemat waktu operasional harian</span>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-12 login-right">
            <div class="login-form-container">
                <h3>Selamat Datang</h3>
                <p class="subtitle">Masuk menggunakan akun kamu untuk melanjutkan.</p>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group-custom">
                        <label for="login">Email atau No HP</label>
                        <div class="input-wrapper">
                            <i class="feather icon-user"></i>
                            <input type="text" name="login"
                                class="form-control @error('login') is-invalid @enderror"
                                id="login" placeholder="Masukkan email atau no HP"
                                value="{{ old('login') }}" required>
                        </div>
                        @error('login')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group-custom">
                        <label for="user-password">Password</label>
                        <div class="input-wrapper">
                            <i class="feather icon-lock"></i>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                id="user-password" placeholder="Masukkan password" required>
                            <i class="feather icon-eye toggle-pw" id="togglePasswordIcon" onclick="togglePassword()"></i>
                        </div>
                        @error('password')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa sandi?</a>

                    <button type="submit" class="btn btn-login">Masuk</button>
                </form>

                <div class="login-footer-custom">
                    <a href="/" class="brand-name">LaundryCamp</a>
                    <p class="contact-text">
                        Ingin mendaftar? Hubungi kami via
                        <a href="https://wa.me/6282284392025" target="_blank">WhatsApp: 0822-8439-2025</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("user-password");
            const toggleIcon = document.getElementById("togglePasswordIcon");
            const isPassword = passwordInput.type === "password";
            passwordInput.type = isPassword ? "text" : "password";
            toggleIcon.classList.toggle("icon-eye");
            toggleIcon.classList.toggle("icon-eye-off");
        }
        feather.replace();
    </script>
@endsection
