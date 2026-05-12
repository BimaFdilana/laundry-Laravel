@extends('layouts.backend')
@section('title', 'Profile')
@section('style')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        #map {
            height: 300px;
        }
    </style>
@endsection
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
                        <div class="m-t-30"> <img src="{{ asset('backend/images/profile/user.jpg') }}" class="rounded"
                                width="230" />
                            <h4 class="card-title m-t-10">{{ $user->name }}
                            </h4>
                            <h6 class="card-subtitle">Customer</h6>
                        </div>
                    </div>
                </div>
                <div>
                    <hr>
                </div>

                <div class="card-body"> <small class="text-muted">Email address </small>
                    <h6>{{ $user->email }}</h6> <small class="text-muted p-t-30 db">Phone</small>
                    <h6>{{ $user->no_telp }}</h6> <small class="text-muted p-t-30 db">Address</small>
                    <h6>{{ $user->alamat }}</h6>

                    <div class="d-flex justify-content-between">
                        <a href="{{ url('profile-customer/edit', Auth::user()->id) }}" class="btn btn-primary mt-2">Edit</a>
                        <a href="" data-toggle="modal" data-target="#change_password"
                            class="btn btn-warning mt-2">Change Password</a>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <div class="card-body">
                    <h5>Inisial</h5>
                    <p>{{ $user->customer->inisial ?? '-' }}</p>
                    <h5>Tanggal Lahir</h5>
                    <p>{{ $user->customer->tgl_lahir ?? '-' }}</p>
                    <h5>Link G-Maps</h5>
                    @if (!empty($user->customer->link_gmaps))
                        <p>
                            <a href="{{ $user->customer->link_gmaps }}" target="_blank" rel="noopener noreferrer">
                                {{ $user->customer->link_gmaps }}
                            </a>
                        </p>
                    @else
                        <p>-</p>
                    @endif
                    <h5>Titik Lokasi Alamat</h5>
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
    @include('customer.profile.modal')
@endsection
