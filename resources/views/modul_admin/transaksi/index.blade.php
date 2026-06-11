@extends('layouts.backend')
@section('title', 'Admin - Data Transaksi')
@section('content')
    <div class="row">
        <div class="col-lg-12 mb-3">
            <a href="{{ route('transaksi.index') }}"
                class="btn {{ request()->routeIs('transaksi.index') ? 'btn-primary' : 'btn-outline-primary' }}">
                Transaksi
            </a>
            <a href="{{ route('transaksi.indexsatuan') }}"
                class="btn {{ request()->routeIs('transaksi.indexsatuan') ? 'btn-primary' : 'btn-outline-primary' }}">
                Transaksi Satuan
            </a>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"> Data Transaksi</h4>

                    <form method="GET" action="{{ route('transaksi.index') }}" class="form-inline mb-3">
                        <input type="text" name="search" class="form-control mr-2" placeholder="Cari invoice/customer..."
                            value="{{ request('search') }}">
                        <input type="date" name="dari" class="form-control mr-2" value="{{ request('dari') }}">
                        <span class="mr-2">s/d</span>
                        <input type="date" name="sampai" class="form-control mr-2" value="{{ request('sampai') }}">
                        <button type="submit" class="btn btn-info mr-2">Filter</button>
                        @if (request()->hasAny(['search', 'dari', 'sampai']))
                            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Reset</a>
                        @endif
                    </form>

                    <p class="text-muted">Menampilkan {{ $transaksiBiasa->firstItem() ?? 0 }} - {{ $transaksiBiasa->lastItem() ?? 0 }} dari {{ $transaksiBiasa->total() }} transaksi</p>

                    <div class="table-responsive m-t-0">
                        <table class="table display table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>TGL Transaksi</th>
                                    <th>Customer</th>
                                    <th>Status Order</th>
                                    <th>Status Pembayaran</th>
                                    <th>Jenis Laundri</th>
                                    <th>Kg</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="refresh_body">
                                {{-- Transaksi Biasa --}}
                                @foreach ($transaksiBiasa as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ($transaksiBiasa->currentPage() - 1) * $transaksiBiasa->perPage() }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d-m-y') }}</td>
                                        <td>{{ $item->customer }}</td>
                                        <td>
                                            {{-- status_order Transaksi --}}
                                            @include('components.status-order', [
                                                'status' => $item->status_order,
                                            ])
                                        </td>
                                        <td>
                                            @include('components.status-payment', [
                                                'status' => $item->status_payment,
                                            ])
                                        </td>
                                        <td>{{ $item->price->nama ?? '-' }} - {{ $item->price->jenis ?? '-' }}</td>
                                        <td>{{ $item->kg }}</td>
                                        <td>{{ Rupiah::getRupiah($item->harga_akhir) }}</td>
                                        <td>
                                            <a href="{{ url('invoice-customer', $item->invoice) }}"
                                                class="btn btn-sm btn-success">Invoice</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $transaksiBiasa->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
