@extends('layouts.show')
@section('title', 'Profile')
@section('header', 'Data Admin')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="col text-center">
                        <div class="m-t-30">
                            <img src="{{ asset('backend/images/profile/user.jpg') }}" class="rounded" width="230" />
                            <h4 class="card-title m-t-10">{{ $admin->name }}</h4>
                            <h6 class="card-subtitle">Admin</h6>
                        </div>
                    </div>
                </div>
                <div>
                    <hr>
                </div>

                <div class="card-body">
                    <small class="text-muted">Email address</small>
                    <h6>{{ $admin->email }}</h6>

                    <small class="text-muted p-t-30 db">Phone</small>
                    <h6>{{ $admin->no_telp }}</h6>

                    <small class="text-muted p-t-30 db">Address</small>
                    <h6>{{ $admin->alamat }}</h6>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('kelola-admin.index') }}" class="btn btn-primary mt-2">Kembali</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <div class="card-body">
                    <h5>Jenis Kelamin</h5>
                    <p>{{ $admin->kelamin ?? '-' }}</p>

                    <h5>Status</h5>
                    <p>{{ $admin->status ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
