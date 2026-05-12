@extends('layouts.backend')
@section('title', 'Edit Profile')
@section('style')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet Geocoder CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

    <style>
        #map {
            height: 300px;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center class="m-t-30">
                        <img src="{{ asset('backend/images/profile/user.jpg') }}" class="rounded" width="230" />
                        <h4 class="card-title m-t-10">{{ old('name', $edit->name) }}</h4>
                        <h6 class="card-subtitle">Customer</h6>
                    </center>
                </div>
                <div>
                    <hr>
                </div>
                <div class="card-body">
                    <small class="text-muted">Email address </small>
                    <h6>{{ old('email', $edit->email) }}</h6>
                    <small class="text-muted p-t-30 db">Phone</small>
                    <h6>{{ old('no_telp', $edit->no_telp) }}</h6>
                    <small class="text-muted p-t-30 db">Address</small>
                    <h6>{{ old('alamat', $edit->alamat) }}</h6>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Profile</h4>
                    <hr>
                    <form action="{{ url('profile-customer/update', $edit->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="col-md-12">
                            <div class="form-group has-success">
                                <label class="control-label">Nama</label>
                                <input type="text" name="name" value="{{ old('name', $edit->name) }}"
                                    class="form-control @error('name') is-invalid @enderror" />
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group has-success">
                                <label class="control-label">Email</label>
                                <input type="email" name="email" value="{{ old('email', $edit->email) }}"
                                    class="form-control @error('email') is-invalid @enderror" />
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group has-success">
                                <label class="control-label">No. Telp</label>
                                <input type="number" name="no_telp" value="{{ old('no_telp', $edit->no_telp) }}"
                                    class="form-control @error('no_telp') is-invalid @enderror" />
                                @error('no_telp')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="jenis-kelamin">Jenis Kelamin</label>
                                <select name="kelamin" class="form-control @error('kelamin') is-invalid @enderror">
                                    <option value="" disabled {{ old('kelamin', $edit->kelamin) ? '' : 'selected' }}>
                                        Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki"
                                        {{ old('kelamin', $edit->kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="Perempuan"
                                        {{ old('kelamin', $edit->kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                                @error('kelamin')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Inisial (dari tabel customer) -->
                        <div class="col-md-12">
                            <div class="form-group has-success">
                                <label>Inisial</label>
                                <input type="text" name="inisial"
                                    value="{{ old('inisial', $edit->customer->inisial ?? '') }}"
                                    class="form-control @error('inisial') is-invalid @enderror" />
                                @error('inisial')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Tanggal Lahir (dari tabel customer) -->
                        <div class="col-md-12">
                            <div class="form-group has-success">
                                <label>Tanggal Lahir</label>
                                <input type="date" name="tgl_lahir"
                                    value="{{ old('tgl_lahir', $edit->customer->tgl_lahir ?? '') }}"
                                    class="form-control @error('tgl_lahir') is-invalid @enderror" />
                                @error('tgl_lahir')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Alamat (dari tabel users) -->
                        <div class="col-md-12">
                            <div class="form-group has-success">
                                <label class="control-label">Alamat</label>
                                <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $edit->alamat) }}</textarea>
                                @error('alamat')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Link G-Maps (dari tabel customer) -->
                        <div class="col-md-12">
                            <div class="form-group has-success">
                                <label>Link G-Maps</label>
                                <input type="text" name="link_gmaps"
                                    value="{{ old('link_gmaps', $edit->customer->link_gmaps ?? '') }}"
                                    class="form-control @error('link_gmaps') is-invalid @enderror" />
                                @error('link_gmaps')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12" hidden>
                            <div class="form-group has-success">
                                <label>Latitude</label>
                                <input type="text" name="latitude" class="form-control"
                                    value="{{ old('latitude', $edit->customer->latitude ?? '') }}" />
                            </div>
                        </div>
                        <div class="col-md-12" hidden>
                            <div class="form-group has-success">
                                <label>Longitude</label>
                                <input type="text" name="longitude" class="form-control"
                                    value="{{ old('longitude', $edit->customer->longitude ?? '') }}" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label>Pilih Lokasi di Peta (digunakan untuk penjemputan dan pengantaran laundry)</label>
                            <div id="map"></div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <button type="button" id="btn-lokasi" class="btn btn-info btn-sm">
                                Gunakan Lokasi Saya
                            </button>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-check"></i> Update
                            </button>
                            <a href="{{ url('profile-customer', Auth::user()->id) }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Leaflet Geocoder JS -->
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
        var initialLat = {{ old('latitude', $edit->customer->latitude ?? '-6.200000') }};
        var initialLng = {{ old('longitude', $edit->customer->longitude ?? '106.816666') }};

        var map = L.map('map').setView([initialLat, initialLng], 13);
        var marker = L.marker([initialLat, initialLng], {
            draggable: true
        }).addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        marker.on('dragend', function(e) {
            var latlng = marker.getLatLng();
            document.querySelector('input[name="latitude"]').value = latlng.lat;
            document.querySelector('input[name="longitude"]').value = latlng.lng;
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.querySelector('input[name="latitude"]').value = e.latlng.lat;
            document.querySelector('input[name="longitude"]').value = e.latlng.lng;
        });

        // Tambahkan pencarian alamat
        L.Control.geocoder({
                defaultMarkGeocode: false
            })
            .on('markgeocode', function(e) {
                var latlng = e.geocode.center;
                marker.setLatLng(latlng);
                map.setView(latlng, 16);
                document.querySelector('input[name="latitude"]').value = latlng.lat;
                document.querySelector('input[name="longitude"]').value = latlng.lng;
            })
            .addTo(map);
    </script>

    <script>
        document.getElementById('btn-lokasi').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;

                    // Update marker & peta
                    marker.setLatLng([lat, lng]);
                    map.setView([lat, lng], 16);

                    // Update input hidden
                    document.querySelector('input[name="latitude"]').value = lat;
                    document.querySelector('input[name="longitude"]').value = lng;
                }, function(error) {
                    alert("Gagal mendapatkan lokasi: " + error.message);
                });
            } else {
                alert("Browser Anda tidak mendukung Geolocation.");
            }
        });
    </script>
@endsection
