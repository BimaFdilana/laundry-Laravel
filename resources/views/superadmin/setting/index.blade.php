@extends('layouts.backend')
@section('title', 'Super Admin - Settings')
@section('header', 'Settings')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @elseif($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <div class="content-body">
        <section>
            <div class="row">
                <!-- left menu section -->
                <div class="col-md-3 mb-2 mb-md-0">
                    <ul class="nav nav-pills flex-column mt-md-0 mt-1">




                        <li class="nav-item">
                            <a class="nav-link d-flex py-75 active" id="pill-theme" data-toggle="pill" href="#vertical-theme"
                                aria-expanded="true">
                                <i class="feather icon-feather mr-50 font-medium-3"></i>
                                Theme
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- right content section -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="tab-content">




                                    {{-- Panel Theme --}}
                                    <div class="tab-pane active" id="vertical-theme" role="tabpanel"
                                        aria-labelledby="pill-theme" aria-expanded="true">
                                        <form action="{{ route('superadmin-setting-theme.update', Auth::id()) }}"
                                            method="post">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <h5 class="m-1">Theme Dark <i
                                                        class=" {{ Auth::user()->theme == 1 ? 'fa fa-check' : '' }} "
                                                        style="color: chartreuse"></i> </h5>
                                                <div class="col-12 mb-1">
                                                    <div class="custom-control custom-switch custom-control-inline">
                                                        <input type="checkbox" class="custom-control-input"
                                                            name="theme" {{ Auth::user()->theme == 1 ? 'checked' : '' }}
                                                            value="1" id="theme">
                                                        <label class="custom-control-label mr-1" for="theme"></label>
                                                        <span class="switch-label w-100">Aktifkan Jika Ingin Menggunakan
                                                            Theme Dark</span>
                                                    </div>
                                                </div>

                                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-start">
                                                    <button type="submit"
                                                        class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save
                                                        changes</button>
                                                    <button type="reset" class="btn btn-outline-warning">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <script>
        @if (count($errors) > 0)
            $(function() {
                $('#addpayment').modal('show');
            });
        @endif
    </script>
@endsection
