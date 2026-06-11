@extends('layouts.backend')
@section('title', 'Admin - Data Piutang')
@section('header', 'Data Piutang')
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

    <div class="row">
        <div class="col-lg-4 col-sm-6">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Total Piutang</h6>
                        <h4 class="text-danger mb-0">Rp. {{ number_format($totalPiutang, 0, ',', '.') }}</h4>
                    </div>
                    <div class="avatar bg-rgba-danger p-50">
                        <div class="avatar-content">
                            <i class="feather icon-alert-circle text-danger font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Jumlah Piutang</h6>
                        <h4 class="mb-0">{{ $piutang->count() }} transaksi</h4>
                    </div>
                    <div class="avatar bg-rgba-warning p-50">
                        <div class="avatar-content">
                            <i class="feather icon-file-text text-warning font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Daftar Piutang (Belum Lunas)</h4>
                        <form method="GET" action="{{ route('admin.piutang.index') }}" class="form-inline">
                            <select name="customer" class="form-control mr-2">
                                <option value="">-- Semua Customer --</option>
                                @foreach ($listCustomer as $cs)
                                    <option value="{{ $cs }}" {{ $customer == $cs ? 'selected' : '' }}>{{ $cs }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-info">Filter</button>
                            @if ($customer)
                                <a href="{{ route('admin.piutang.index') }}" class="btn btn-secondary ml-1">Reset</a>
                            @endif
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table zero-configuration">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Customer</th>
                                    <th>Tipe</th>
                                    <th>Invoice</th>
                                    <th>Metode</th>
                                    <th>Total Utang</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($piutang as $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y H:i') }}</td>
                                        <td>{{ $item['customer'] ?? '-' }}</td>
                                        <td>
                                            @if ($item['tipe'] === 'reguler')
                                                <span class="badge badge-primary">Reguler</span>
                                            @elseif ($item['tipe'] === 'satuan')
                                                <span class="badge badge-info">Satuan</span>
                                            @else
                                                <span class="badge badge-warning">Paket</span>
                                            @endif
                                        </td>
                                        <td>{{ $item['invoice'] }}</td>
                                        <td>{{ $item['jenis_pembayaran'] }}</td>
                                        <td class="text-danger font-weight-bold">Rp. {{ number_format($item['total'], 0, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('admin.piutang.bayar', ['tipe' => $item['tipe'], 'id' => $item['id']]) }}"
                                                method="POST" style="display:inline-block;"
                                                onsubmit="return confirm('Yakin ingin melunasi piutang ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="feather icon-check"></i> Bayar Full
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="text-right"><strong>Total Piutang Keseluruhan</strong></td>
                                    <td colspan="2"><strong class="text-danger">Rp. {{ number_format($totalPiutang, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
