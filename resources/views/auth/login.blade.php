@extends('layouts.auth')
@section('title', 'Masuk')
@section('content')
    <section class="row flexbox-container">
        <div class="col-xl-8 col-11 d-flex justify-content-center">
            <div class="card bg-authentication rounded-0 mb-0">
                <div class="row m-0">
                    <div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
                        <img src="{{ asset('backend/images/pages/login.png') }}" alt="branding logo">
                    </div>
                    <div class="col-lg-6 col-12 p-0">
                        <div class="card rounded-0 mb-0 px-2">
                            <div class="card-header pb-1">
                                <div class="card-title">
                                    <h4 class="mb-0">Masuk</h4>
                                </div>
                            </div>
                            <p class="px-2">Selamat Datang, Masuk Menggunakan Akun Kamu.</p>
                            <div class="card-content">
                                <div class="card-body pt-1">
                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <fieldset class="form-label-group form-group position-relative has-icon-left">
                                            <input type="text" name="login"
                                                class="form-control @error('login') is-invalid @enderror" id="login"
                                                placeholder="Email atau No HP" value="{{ old('login') }}" required>
                                            <div class="form-control-position">
                                                <i class="feather icon-user"></i>
                                            </div>
                                            <label for="login">Email atau No HP</label>
                                            @error('login')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </fieldset>

                                        <fieldset class="form-label-group position-relative has-icon-left">
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="user-password" placeholder="Password" required>
                                            <div class="form-control-position">
                                                <i class="feather icon-lock"></i>
                                            </div>

                                            <!-- Toggle eye icon -->
                                            <div class="form-control-position"
                                                style="right: 10px; left: auto; cursor: pointer;"
                                                onclick="togglePassword()">
                                                <i class="feather icon-eye" id="togglePasswordIcon"></i>
                                            </div>

                                            <label for="user-password">Password</label>
                                            @error('password')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </fieldset>

                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <a href="{{ route('password.request') }}" class="text-primary"
                                                style="font-size: 0.85rem;">
                                                Lupa sandi?
                                            </a>
                                        </div>

                                        <button type="submit"
                                            class="btn btn-primary float-right btn-inline btn-block">Login</button>
                                    </form>
                                </div>
                            </div>
                            <div class="login-footer">
                                <div class="divider">
                                    <div class="divider-text"><a href="/">LaundryCamp</a></div>
                                </div>
                                <p style="font-size:10px">
                                    Jika ingin mendaftar silahkan hubungi kami melalui
                                    <a href="https://wa.me/6282284392025" target="_blank">WhatsApp: 0822-8439-2025</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Toggle Password Script -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("user-password");
            const toggleIcon = document.getElementById("togglePasswordIcon");

            const isPassword = passwordInput.type === "password";
            passwordInput.type = isPassword ? "text" : "password";

            toggleIcon.classList.toggle("icon-eye");
            toggleIcon.classList.toggle("icon-eye-off");
        }

        // Feather icons (jika belum di-init di layout)
        feather.replace();
    </script>
@endsection
