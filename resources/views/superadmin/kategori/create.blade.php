@extends('layouts.backend')
@section('title', 'Form Tambah Kategori')
@section('header', 'Tambah Kategori')
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Tambah Kategori</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('kategori.store') }}" method="POST" class="form form-vertical">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <div class="form-group">
                                        <label for="nama">Nama Kategori</label>
                                        <input type="text" name="nama" id="nama"
                                            class="form-control @error('nama') is-invalid @enderror"
                                            placeholder="Contoh: Peralatan Kebersihan" value="{{ old('nama') }}">
                                        @error('nama')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Tambah</button>
                                    <a href="{{ route('kategori.index') }}" class="btn btn-outline-warning">Batal</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
