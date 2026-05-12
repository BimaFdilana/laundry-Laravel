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
                            <a class="nav-link d-flex py-75 active" id="pill-target" data-toggle="pill"
                                href="#vertical-target" aria-expanded="false">
                                <i class="feather icon-message-circle mr-50 font-medium-3"></i>
                                Target Laundry
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link d-flex py-75" id="pill-finance" data-toggle="pill" href="#vertical-finance"
                                aria-expanded="false">
                                <i class="feather icon-dollar-sign mr-50 font-medium-3"></i>
                                Target Finance
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link d-flex py-75" id="pill-theme" data-toggle="pill" href="#vertical-theme"
                                aria-expanded="false">
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
                                    {{-- Panel Target --}}
                                    <div class="tab-pane active" id="vertical-target" role="tabpanel"
                                        aria-labelledby="pill-target" aria-expanded="false">
                                        <form action="{{ route('set-target.update', $settarget->id) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="Target Hari">Target per-hari</label>
                                                            <input type="number" class="form-control" name="target_day"
                                                                value="{{ $settarget->target_day }}"
                                                                placeholder="Target Hari" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="Target Bulan">Target per-bulan</label>
                                                            <input type="number" class="form-control" name="target_month"
                                                                value="{{ $settarget->target_month }}"
                                                                placeholder="Target Bulan" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="Target Tahun">Target per-tahun</label>
                                                            <input type="number" class="form-control" name="target_year"
                                                                value="{{ $settarget->target_year }}"
                                                                placeholder="Target Tahun" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-start">
                                                    <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">Save
                                                        changes</button>
                                                    <button type="reset" class="btn btn-outline-warning">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- Panel Target Finance --}}
                                    <div class="tab-pane fade" id="vertical-finance" role="tabpanel"
                                        aria-labelledby="pill-finance" aria-expanded="false">
                                        <form action="{{ route('set-target-finance.update', $targetFinance->id) }}"
                                            method="post">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="target_hari">Target Harian</label>
                                                        <input type="number" class="form-control" name="target_hari"
                                                            value="{{ $targetFinance->target_hari }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="target_bulan">Target Bulanan</label>
                                                        <input type="number" class="form-control" name="target_bulan"
                                                            value="{{ $targetFinance->target_bulan }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="target_tahun">Target Tahunan</label>
                                                        <input type="number" class="form-control" name="target_tahun"
                                                            value="{{ $targetFinance->target_tahun }}" required>
                                                    </div>
                                                </div>

                                                <div class="col-12 d-flex justify-content-start mt-2">
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                    <button type="reset"
                                                        class="btn btn-outline-warning ml-2">Batal</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- Panel Theme --}}
                                    <div class="tab-pane fade" id="vertical-theme" role="tabpanel"
                                        aria-labelledby="pill-theme" aria-expanded="false">
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
