@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <section class="row flexbox-container">
        <div class="col-xl-8 col-11 d-flex justify-content-center">
            <div class="card bg-authentication rounded-0 mb-0">
                <div class="row m-0">
                    <div class="col-lg-12 col-12 p-0">
                        <div class="card rounded-0 mb-0 px-2">
                            <div class="card-header pb-1">
                                <div class="card-title">
                                    <h4 class="mb-0">Reset Password</h4>
                                </div>
                            </div>
                            <p class="px-2">Masukkan email kamu untuk menerima link reset password.</p>
                            <div class="card-content">
                                <div class="card-body pt-1">
                                    @if (session('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('password.email') }}">
                                        @csrf

                                        <fieldset class="form-label-group form-group position-relative has-icon-left mt-2">
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="email" autofocus>
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

                                        <button type="submit"
                                            class="btn btn-primary float-right btn-inline btn-block mb-2">Kirim Link
                                            Reset</button>
                                    </form>

                                    <div class="text-center">
                                        <a href="{{ route('login') }}" class="text-primary">← Kembali ke Login</a>
                                    </div>
                                </div>
                            </div>
                            <div class="login-footer">
                                <div class="divider">
                                    <div class="divider-text"><a href="/" target="_blank">LaundryCamp</a></div>
                                </div>
                                <p style="font-size:10px">
                                    Jika mengalami kendala, hubungi kami melalui
                                    <a href="https://wa.me/6282284392025" target="_blank">WhatsApp: 0822-8439-2025</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
