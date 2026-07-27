@extends('layouts.backend')
@section('title', 'Super Admin - Target Keuangan')
@section('header', 'Target Keuangan')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @elseif($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <div class="content-body">
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header pb-1 border-bottom">
                        <h4 class="card-title font-weight-bold"><i class="feather icon-dollar-sign mr-50 text-success"></i>Atur Target Keuangan (Pendapatan)</h4>
                    </div>
                    <div class="card-body mt-2">
                        <form action="{{ route('superadmin.target-finance.update', $targetFinance->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-4 mb-1">
                                    <div class="form-group">
                                        <label for="target_hari" class="font-weight-bold">Target Harian (Rp)</label>
                                        <input type="number" class="form-control" name="target_hari"
                                            value="{{ $targetFinance->target_hari }}" required placeholder="Contoh: 500000">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <div class="form-group">
                                        <label for="target_bulan" class="font-weight-bold">Target Bulanan (Rp)</label>
                                        <input type="number" class="form-control" name="target_bulan"
                                            value="{{ $targetFinance->target_bulan }}" required placeholder="Contoh: 15000000">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <div class="form-group">
                                        <label for="target_tahun" class="font-weight-bold">Target Tahunan (Rp)</label>
                                        <input type="number" class="form-control" name="target_tahun"
                                            value="{{ $targetFinance->target_tahun }}" required placeholder="Contoh: 180000000">
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
