@extends('layouts.backend')
@section('title', 'Form Tambah Inventaris')
@section('header', 'Tambah Inventaris')
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Tambah Inventaris</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('inventaris.store') }}" method="POST" class="form form-vertical">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="nama_barang">Nama Barang</label>
                                        <input type="text" id="nama_barang" name="nama_barang"
                                            class="form-control @error('nama_barang') is-invalid @enderror"
                                            value="{{ old('nama_barang') }}" placeholder="Nama Barang">
                                        @error('nama_barang')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="jenis">Jenis</label>
                                        <select id="jenis" name="jenis"
                                            class="form-control @error('jenis') is-invalid @enderror">
                                            <option value="">-- Pilih Jenis --</option>
                                            <option value="Fixed Asset"
                                                {{ old('jenis') == 'Fixed Asset' ? 'selected' : '' }}>Fixed Asset</option>
                                            <option value="Supplies" {{ old('jenis') == 'Supplies' ? 'selected' : '' }}>
                                                Supplies</option>
                                        </select>
                                        @error('jenis')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="kategori_id">Kategori</label>
                                        <select name="kategori_id" id="kategori_id"
                                            class="form-control @error('kategori_id') is-invalid @enderror">
                                            <option value="" disabled selected>-- Pilih Kategori --</option>
                                            @foreach ($kategori as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kategori_id')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="satuan">Satuan</label>
                                        <input type="text" name="satuan" id="satuan"
                                            class="form-control @error('satuan') is-invalid @enderror"
                                            value="{{ old('satuan') }}" placeholder="Contoh: unit, buah, pcs">
                                        @error('satuan')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="stok">Stok</label>
                                        <input type="number" name="stok" id="stok"
                                            class="form-control @error('stok') is-invalid @enderror"
                                            value="{{ old('stok') }}" min="0">
                                        @error('stok')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="harga">Harga</label>
                                        <input type="number" name="harga" id="harga"
                                            class="form-control @error('harga') is-invalid @enderror"
                                            value="{{ old('harga') }}" step="0.01">
                                        @error('harga')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="kondisi">Kondisi</label>
                                        <select name="kondisi" id="kondisi"
                                            class="form-control @error('kondisi') is-invalid @enderror">
                                            <option value="">-- Pilih Kondisi --</option>
                                            <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik
                                            </option>
                                            <option value="Cukup Baik"
                                                {{ old('kondisi') == 'Cukup Baik' ? 'selected' : '' }}>Cukup Baik</option>
                                            <option value="Rusak" {{ old('kondisi') == 'Rusak' ? 'selected' : '' }}>Rusak
                                            </option>
                                        </select>
                                        @error('kondisi')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary mr-1">Tambah</button>
                                    <a href="{{ route('inventaris.index') }}" class="btn btn-outline-warning">Batal</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
