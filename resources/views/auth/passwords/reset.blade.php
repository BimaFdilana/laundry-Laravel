@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <section class="row flexbox-container">
        <div class="col-xl-6 col-11 d-flex justify-content-center">
            <div class="card bg-authentication rounded-0 mb-0 w-100">
                <div class="card rounded-0 mb-0 px-2">
                    <div class="card-header pb-1">
                        <div class="card-title">
                            <h4 class="mb-0">Reset Password</h4>
                        </div>
                    </div>
                    <p class="px-2">Silahkan masukkan password baru kamu.</p>
                    <div class="card-content">
                        <div class="card-body pt-1">
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">

                                <fieldset class="form-label-group form-group position-relative has-icon-left">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                    <div class="form-control-position">
                                        <i class="feather icon-mail"></i>
                                    </div>
                                    <label for="email">Email</label>
                                    @error('email')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </fieldset>

                                <fieldset class="form-label-group form-group position-relative has-icon-left">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">
                                    <div class="form-control-position">
                                        <i class="feather icon-lock"></i>
                                    </div>
                                    <label for="password">Password Baru</label>
                                    @error('password')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </fieldset>

                                <fieldset class="form-label-group form-group position-relative has-icon-left">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                    <div class="form-control-position">
                                        <i class="feather icon-lock"></i>
                                    </div>
                                    <label for="password-confirm">Konfirmasi Password</label>
                                </fieldset>

                                <button type="submit" class="btn btn-primary float-right btn-inline btn-block">
                                    Reset Password
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="login-footer">
                        <div class="divider">
                            <div class="divider-text"><a href="/">LaundryCamp</a></div>
                        </div>
                        <p style="font-size:10px">
                            Jika mengalami kendala, hubungi kami melalui
                            <a href="https://wa.me/6282284392025" target="_blank">WhatsApp: 0822-8439-2025</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
