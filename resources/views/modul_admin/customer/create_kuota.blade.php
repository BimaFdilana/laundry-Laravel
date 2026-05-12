@extends('layouts.backend')
@section('title', 'Admin - Tambah Kuota Customer')
@section('header', 'Kuota Customer')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="card-title">Form Tambah Kuota Laundry</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('kuota.store') }}" method="POST">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-6 col-xl-6 col-12">
                                    <div class="form-group">
                                        <label for="user_id">Nama Customer</label>
                                        <div class="position-relative">
                                            <select name="user_id" id="user_id"
                                                class="form-control @error('user_id') is-invalid @enderror" required>
                                                <option value="">-- Pilih Customer --</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-6 col-12">
                                    <div class="form-group">
                                        <label for="kategori">Kategori Laundry</label>
                                        <div class="position-relative">
                                            <select name="kategori" id="kategori"
                                                class="form-control @error('kategori') is-invalid @enderror" required>
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach (collect($pakets)->pluck('kategori')->unique() as $kategori)
                                                    <option value="{{ $kategori }}"
                                                        {{ old('kategori') == $kategori ? 'selected' : '' }}>
                                                        {{ $kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('kategori')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-6 col-12">
                                    <div class="form-group">
                                        <label for="kuota">Jumlah Kuota</label>
                                        <div class="position-relative">
                                            <input type="text" name="kuota" id="kuota"
                                                class="form-control @error('kuota') is-invalid @enderror"
                                                value="{{ old('kuota') }}" min="0" required>
                                            @error('kuota')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-6 col-12">
                                    <div class="form-group">
                                        <label for="harga">Harga</label>
                                        <div class="position-relative">
                                            <input type="number" name="harga" id="harga"
                                                class="form-control @error('harga') is-invalid @enderror"
                                                value="{{ old('harga') }}" min="0" required>
                                            @error('harga')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-6 col-12">
                                    <div class="form-group">
                                        <label for="diskon">Diskon</label>
                                        <div class="position-relative">
                                            <input type="number" name="diskon" id="diskon"
                                                class="form-control @error('diskon') is-invalid @enderror"
                                                value="{{ old('diskon', 0) }}" min="0">
                                            @error('diskon')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-xl-12 col-12">
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <div class="position-relative">
                                            <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                                rows="3">{{ old('keterangan') }}</textarea>
                                            @error('keterangan')
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
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('kuota.index') }}" class="btn btn-danger">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
