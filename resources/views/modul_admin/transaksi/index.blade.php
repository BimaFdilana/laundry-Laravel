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
                    <div class="table-responsive m-t-0">
                        <table id="myTable" class="table display table-bordered table-striped">
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
                                @foreach ($transaksiBiasa as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
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
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#myTable').DataTable();
            $(document).ready(function() {
                var table = $('#example').DataTable({
                    "columnDefs": [{
                        "visible": false,
                        "targets": 2
                    }],
                    "order": [
                        [2, 'asc']
                    ],
                    "displayLength": 25,
                    "drawCallback": function(settings) {
                        var api = this.api();
                        var rows = api.rows({
                            page: 'current'
                        }).nodes();
                        var last = null;
                        api.column(2, {
                            page: 'current'
                        }).data().each(function(group, i) {
                            if (last !== group) {
                                $(rows).eq(i).before(
                                    '<tr class="group"><td colspan="5">' + group +
                                    '</td></tr>');
                                last = group;
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
