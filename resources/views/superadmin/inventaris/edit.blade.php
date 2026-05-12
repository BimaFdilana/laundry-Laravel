@extends('layouts.backend')
@section('title', 'Edit Inventaris')
@section('header', 'Edit Inventaris')
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Inventaris</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('inventaris.update', $inventaris->id) }}" method="POST" class="form form-vertical">
                        @csrf
                        @method('PUT')
                        <div class="form-body">
                            <div class="row">

                                @php
                                    $oldOr = fn($key, $default) => old($key, $inventaris->$key ?? $default);
                                @endphp

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nama_barang">Nama Barang</label>
                                        <input type="text" name="nama_barang" id="nama_barang"
                                            class="form-control @error('nama_barang') is-invalid @enderror"
                                            value="{{ $oldOr('nama_barang', '') }}">
                                        @error('nama_barang')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="jenis">Jenis</label>
                                        <select name="jenis" id="jenis"
                                            class="form-control @error('jenis') is-invalid @enderror">
                                            <option value="">-- Pilih Jenis --</option>
                                            <option value="Fixed Asset"
                                                {{ $oldOr('jenis', '') == 'Fixed Asset' ? 'selected' : '' }}>Fixed Asset
                                            </option>
                                            <option value="Supplies"
                                                {{ $oldOr('jenis', '') == 'Supplies' ? 'selected' : '' }}>Supplies</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kategori_id">Kategori</label>
                                        <select name="kategori_id" id="kategori_id"
                                            class="form-control @error('kategori_id') is-invalid @enderror">
                                            <option value="" disabled>Pilih Kategori</option>
                                            @foreach ($kategori as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $oldOr('kategori_id', '') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kategori_id')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="satuan">Satuan</label>
                                        <input type="text" name="satuan" id="satuan"
                                            class="form-control @error('satuan') is-invalid @enderror"
                                            value="{{ $oldOr('satuan', '') }}">
                                        @error('satuan')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="stok">Stok</label>
                                        <input type="number" name="stok" id="stok"
                                            class="form-control @error('stok') is-invalid @enderror"
                                            value="{{ $oldOr('stok', '') }}">
                                        @error('stok')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="harga">Harga</label>
                                        <input type="number" step="0.01" name="harga" id="harga"
                                            class="form-control @error('harga') is-invalid @enderror"
                                            value="{{ $oldOr('harga', '') }}">
                                        @error('harga')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kondisi">Kondisi</label>
                                        <select name="kondisi" id="kondisi"
                                            class="form-control @error('kondisi') is-invalid @enderror">
                                            <option value="">-- Pilih Kondisi --</option>
                                            <option value="Baik" {{ $oldOr('kondisi', '') == 'Baik' ? 'selected' : '' }}>
                                                Baik
                                            </option>
                                            <option value="Cukup Baik"
                                                {{ $oldOr('kondisi', '') == 'Cukup Baik' ? 'selected' : '' }}>Cukup Baik
                                            </option>
                                            <option value="Rusak"
                                                {{ $oldOr('kondisi', '') == 'Rusak' ? 'selected' : '' }}>
                                                Rusak
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Update</button>
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
