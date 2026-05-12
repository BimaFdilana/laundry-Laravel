@extends('layouts.backend')
@section('title', 'Admin - Edit Kuota Customer')
@section('header', 'Kuota Customer')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="card-title">Form Edit Kuota Laundry</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('kuota.update', $kuota->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-6 col-xl-6 col-12">
                                    <div class="form-group">
                                        <label for="nama">Nama Customer</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control" value="{{ $kuota->user->name }}"
                                                disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-6 col-12">
                                    <div class="form-group">
                                        <label for="kategori">Kategori Laundry</label>
                                        <div class="position-relative">
                                            <select name="kategori" class="form-control" required>
                                                @foreach (collect($pakets)->pluck('kategori')->unique() as $kategori)
                                                    <option value="{{ $kategori }}"
                                                        {{ (old('kategori') ?? $kuota->kategori) == $kategori ? 'selected' : '' }}>
                                                        {{ $kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-6 col-12">
                                    <div class="form-group">
                                        <label for="kuota">Jumlah Kuota</label>
                                        <div class="position-relative">
                                            <input type="text" name="kuota" id="kuota"
                                                class="form-control @error('kuota') is-invalid @enderror"
                                                value="{{ $kuota->kuota }}" required>
                                            @error('kuota')
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
                            <a href="{{ route('kuota.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
