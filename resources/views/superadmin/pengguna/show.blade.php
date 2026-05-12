@extends('layouts.show')
@section('title', 'Profile')
@section('header', 'Data Customer')
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
                            <h4 class="card-title m-t-10">{{ $customer->name }}
                            </h4>
                            <h6 class="card-subtitle">Customer</h6>
                        </div>
                    </div>
                </div>
                <div>
                    <hr>
                </div>

                <div class="card-body"> <small class="text-muted">Email address </small>
                    <h6>{{ $customer->email }}</h6> <small class="text-muted p-t-30 db">Phone</small>
                    <h6>{{ $customer->no_telp }}</h6> <small class="text-muted p-t-30 db">Address</small>
                    <h6>{{ $customer->alamat }}</h6>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('supercustomer.index') }}" class="btn btn-primary mt-2">Kembali</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <div class="card-body">
                    <h5>Inisial</h5>
                    <p>{{ $customer->customer->inisial ?? '-' }}</p>
                    <h5>Tanggal Lahir</h5>
                    <p>{{ $customer->customer->tgl_lahir ?? '-' }}</p>
                    <h5>Link G-Maps</h5>
                    @if (!empty($customer->customer->link_gmaps))
                        <p>
                            <a href="{{ $customer->customer->link_gmaps }}" target="_blank" rel="noopener noreferrer">
                                {{ $customer->customer->link_gmaps }}
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
@endsection
