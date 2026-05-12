@extends('layouts.backend')
@section('title', 'Form Edit Data Gift')
@section('header', 'Edit Gift')
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Data Gift</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    @error('errors')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <form action="{{ route('gift.update', $gift->id) }}" method="POST" class="form form-vertical">
                        @csrf
                        @method('PUT')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="user_id">Customer</label>
                                        <select name="user_id" id="user_id"
                                            class="form-control @error('user_id') is-invalid @enderror">
                                            <option value="" disabled>Pilih Customer</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('user_id', $gift->user_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
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

                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="gift">Nama Gift</label>
                                        <input type="text" name="gift" id="gift"
                                            class="form-control @error('gift') is-invalid @enderror" placeholder="Nama Gift"
                                            value="{{ old('gift', $gift->gift) }}">
                                        @error('gift')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                            rows="2">{{ old('keterangan', $gift->keterangan) }}</textarea>
                                        @error('keterangan')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-4 col-xl-4 col-12">
                                    <div class="form-group">
                                        <label for="expired_at">Berlaku Sampai</label>
                                        <input type="date" name="expired_at" id="expired_at"
                                            class="form-control @error('expired_at') is-invalid @enderror"
                                            value="{{ old('expired_at', \Carbon\Carbon::parse($gift->expired_at)->format('Y-m-d')) }}">
                                        @error('expired_at')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1">Update</button>
                                    <a href="{{ route('gift.index') }}"
                                        class="btn btn-outline-warning mr-1 mb-1">Batal</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
