@extends('layouts.backend')
@section('title', 'Form Tambah Data Customer')
@section('header', 'Tambah Customer')
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Tambah Data Customer</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    @error('errors')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <form action="{{ route('supercustomer.store') }}" method="POST" class="form form-vertical">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <div class="position-relative">
                                            <input type="text" name="name" id="nama"
                                                class="form-control @error('name') is-invalid @enderror" placeholder="Nama"
                                                value="{{ old('name') }}">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="email-id-icon">Email</label>
                                        <div class="position-relative">
                                            <input type="email" name="email" id="email-id-icon"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="Email" value="{{ old('email') }}">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="alamat">Alamat Customer</label>
                                        <div class="position-relative">
                                            <textarea type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                                                rows="3" value="{{ old('alamat') }}"></textarea>
                                            @error('alamat')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="no-telp">No. Telp</label>
                                        <div class="position-relative">
                                            <input type="number" name="no_telp" id="no-telp"
                                                class="form-control @error('no_telp') is-invalid @enderror"
                                                placeholder="No. Telp" value="{{ old('no_telp') }}">
                                            @error('no_telp')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="jenis-kelamin">Jenis Kelamin</label>
                                        <div class="position-relative">
                                            <select name="kelamin" id="jenis-kelamin"
                                                class="form-control @error('kelamin') is-invalid @enderror">
                                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                                <option value="Laki-laki"
                                                    {{ old('kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                                </option>
                                                <option value="Perempuan"
                                                    {{ old('kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                                </option>
                                            </select>
                                            @error('kelamin')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="dapat_kuota">Dapat Kuota Laundry?</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="dapat_kuota"
                                                id="dapat_kuota" value="1" {{ old('dapat_kuota') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="dapat_kuota">Ya</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-xl-4 col-12" id="kategori-kuota-container"
                                    style="{{ old('dapat_kuota') ? '' : 'display:none;' }}">
                                    <div class="form-group">
                                        <label for="kategori_kuota">Kategori Kuota</label>
                                        <select name="kategori_kuota" id="kategori_kuota"
                                            class="form-control @error('kategori_kuota') is-invalid @enderror">
                                            <option value="SETRIKA"
                                                {{ old('kategori_kuota') == 'SETRIKA' ? 'selected' : '' }}>SETRIKA</option>
                                            <option value="CUCI LIPAT" {{ old('kategori_kuota') == 'CUCI LIPAT' ? 'selected' : '' }}>
                                                CUCI LIPAT</option>
                                        </select>
                                        @error('kategori_kuota')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1">Tambah</button>
                                    <a href=" {{ route('supercustomer.index') }} "
                                        class="btn btn-outline-warning mr-1 mb-1">Batal</a>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.getElementById('dapat_kuota').addEventListener('change', function() {
            document.getElementById('kategori-kuota-container').style.display = this.checked ? 'block' : 'none';
        });
    </script>
@endsection
