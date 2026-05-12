@extends('layouts.backend')
@section('title', 'Form Edit Data Admin')
@section('header', 'Edit Admin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="card-title">Form Edit Data Admin</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('kelola-admin.update', $edit->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="name">Nama</label>
                                        <div class="position-relative">
                                            <input type="text" name="name" id="name"
                                                class="form-control @error('name') is-invalid @enderror" placeholder="Nama"
                                                value="{{ $edit->name }}">
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
                                        <label for="email">Email</label>
                                        <div class="position-relative">
                                            <input type="email" name="email" id="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="Email" value="{{ $edit->email }}">
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
                                        <label for="no-telp">No. Telp</label>
                                        <div class="position-relative">
                                            <input type="number" name="no_telp" id="no-telp"
                                                class="form-control @error('no_telp') is-invalid @enderror"
                                                value="{{ $edit->no_telp }}">
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
                                        <label for="status">Status Admin</label>
                                        <div class="position-relative">
                                            <select name="status"
                                                class="form-control @error('status') is-invalid @enderror">
                                                <option value="">Pilih Status</option>
                                                <option value="Active" {{ $edit->status == 'Active' ? 'selected' : '' }}>
                                                    Aktif</option>
                                                <option value="Not Active"
                                                    {{ $edit->status == 'Not Active' ? 'selected' : '' }}>Tidak Aktif
                                                </option>
                                            </select>
                                            @error('status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="kelamin">Jenis Kelamin</label>
                                        <div class="position-relative">
                                            <select name="kelamin"
                                                class="form-control @error('kelamin') is-invalid @enderror">
                                                <option value="" selected>Pilih Jenis Kelamin</option>
                                                <option value="Laki-laki"
                                                    {{ $edit->kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                                </option>
                                                <option value="Perempuan"
                                                    {{ $edit->kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan
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
                                        <label for="alamat">Alamat</label>
                                        <div class="position-relative">
                                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat" rows="3">{{ $edit->alamat }}</textarea>
                                            @error('alamat')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="{{ route('kelola-admin.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
