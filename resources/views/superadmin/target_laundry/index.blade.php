@extends('layouts.backend')
@section('title', 'Super Admin - Target Laundry')
@section('header', 'Target Laundry')
@section('content')
    @include('partials.flash-message')
    <div class="content-body">
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header pb-1 border-bottom">
                        <h4 class="card-title font-weight-bold"><i class="feather icon-box mr-50 text-primary"></i>Atur Target Laundry (Berat / Kg)</h4>
                    </div>
                    <div class="card-body mt-2">
                        <form action="{{ route('superadmin.target-laundry.update', $targetLaundry->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-4 mb-1">
                                    <div class="form-group">
                                        <label for="target_day" class="font-weight-bold">Target Harian (Kg)</label>
                                        <input type="number" class="form-control" name="target_day"
                                            value="{{ $targetLaundry->target_day }}" required placeholder="Contoh: 50">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <div class="form-group">
                                        <label for="target_month" class="font-weight-bold">Target Bulanan (Kg)</label>
                                        <input type="number" class="form-control" name="target_month"
                                            value="{{ $targetLaundry->target_month }}" required placeholder="Contoh: 1500">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <div class="form-group">
                                        <label for="target_year" class="font-weight-bold">Target Tahunan (Kg)</label>
                                        <input type="number" class="form-control" name="target_year"
                                            value="{{ $targetLaundry->target_year }}" required placeholder="Contoh: 18000">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-start mt-1">
                                <button type="submit" class="btn btn-primary font-weight-bold">Simpan Perubahan</button>
                                <button type="reset" class="btn btn-outline-warning ml-2 font-weight-bold">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
