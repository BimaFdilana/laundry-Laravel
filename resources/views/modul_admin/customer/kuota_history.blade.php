@extends('layouts.backend')
@section('title', 'Admin - Riwayat Kuota')
@section('header', isset($customer) ? 'Riwayat Kuota - ' . $customer->name : 'Riwayat Kuota')

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

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ isset($customer) ? route('kuota.history.customer', $customer->id) : route('kuota.history') }}" class="form-inline">
                @if(!isset($customer))
                    <div class="form-group mr-2 mb-2">
                        <label for="user_id" class="mr-1">Customer</label>
                        <select name="user_id" id="user_id" class="form-control form-control-sm">
                            <option value="">Semua Customer</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ request('user_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="form-group mr-2 mb-2">
                    <label for="kategori" class="mr-1">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control form-control-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-2 mb-2">
                    <label for="tipe" class="mr-1">Tipe</label>
                    <select name="tipe" id="tipe" class="form-control form-control-sm">
                        <option value="">Semua Tipe</option>
                        <option value="pemakaian" {{ request('tipe') == 'pemakaian' ? 'selected' : '' }}>Pemakaian</option>
                        <option value="pembelian" {{ request('tipe') == 'pembelian' ? 'selected' : '' }}>Pembelian</option>
                        <option value="penambahan_admin" {{ request('tipe') == 'penambahan_admin' ? 'selected' : '' }}>Penambahan Admin</option>
                        <option value="koreksi_admin" {{ request('tipe') == 'koreksi_admin' ? 'selected' : '' }}>Koreksi Admin</option>
                        <option value="kuota_awal" {{ request('tipe') == 'kuota_awal' ? 'selected' : '' }}>Kuota Awal</option>
                    </select>
                </div>
                <div class="form-group mr-2 mb-2">
                    <label for="dari" class="mr-1">Dari</label>
                    <input type="date" name="dari" id="dari" class="form-control form-control-sm" value="{{ request('dari') }}">
                </div>
                <div class="form-group mr-2 mb-2">
                    <label for="sampai" class="mr-1">Sampai</label>
                    <input type="date" name="sampai" id="sampai" class="form-control form-control-sm" value="{{ request('sampai') }}">
                </div>
                <button type="submit" class="btn btn-sm btn-primary mr-1 mb-2">Filter</button>
                <a href="{{ isset($customer) ? route('kuota.history.customer', $customer->id) : route('kuota.history') }}" class="btn btn-sm btn-secondary mb-2">Reset</a>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">
                        @if(isset($customer))
                            Riwayat Kuota - {{ $customer->name }}
                            <a href="{{ route('kuota.history') }}" class="btn btn-secondary btn-sm">Semua Riwayat</a>
                        @else
                            Riwayat Kuota Laundry
                        @endif
                        <span class="badge badge-info">{{ $logs->total() }} data</span>
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    @if(!isset($customer))
                                        <th>Customer</th>
                                    @endif
                                    <th>Kategori</th>
                                    <th>Tipe</th>
                                    <th>Sebelum</th>
                                    <th>Perubahan</th>
                                    <th>Sesudah</th>
                                    <th>Invoice / Sumber</th>
                                    <th>Petugas</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $i => $log)
                                    <tr>
                                        <td>{{ $logs->firstItem() + $i }}</td>
                                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                        @if(!isset($customer))
                                            <td>{{ $log->user->name ?? '-' }}</td>
                                        @endif
                                        <td><span class="badge badge-secondary">{{ $log->kategori }}</span></td>
                                        <td>
                                            @if($log->tipe == 'pemakaian')
                                                <span class="badge badge-warning">Pemakaian</span>
                                            @elseif($log->tipe == 'pembelian')
                                                <span class="badge badge-success">Pembelian</span>
                                            @elseif($log->tipe == 'penambahan_admin')
                                                <span class="badge badge-primary">Penambahan Admin</span>
                                            @elseif($log->tipe == 'koreksi_admin')
                                                <span class="badge badge-danger">Koreksi Admin</span>
                                            @elseif($log->tipe == 'kuota_awal')
                                                <span class="badge badge-info">Kuota Awal</span>
                                            @else
                                                <span class="badge badge-light">{{ $log->tipe }}</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($log->kuota_sebelum ?? 0, 2) }}</td>
                                        <td>
                                            @if($log->perubahan > 0)
                                                <span class="text-success">+{{ number_format($log->perubahan, 2) }}</span>
                                            @elseif($log->perubahan < 0)
                                                <span class="text-danger">{{ number_format($log->perubahan, 2) }}</span>
                                            @else
                                                {{ number_format($log->perubahan, 2) }}
                                            @endif
                                        </td>
                                        <td><strong>{{ number_format($log->kuota_sesudah ?? 0, 2) }}</strong></td>
                                        <td>
                                            @if($log->transaksi_id)
                                                {{ $log->transaksi->invoice ?? $log->transaksi_id }}
                                            @elseif($log->purchase_request_id)
                                                PR#{{ $log->purchase_request_id }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $log->creator->name ?? '-' }}</td>
                                        <td><small>{{ $log->keterangan }}</small></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="{{ isset($customer) ? 10 : 11 }}" class="text-center">Tidak ada data riwayat kuota.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
