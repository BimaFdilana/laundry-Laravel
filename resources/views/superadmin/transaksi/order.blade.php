@extends('layouts.backend')
@section('title', 'Super Admin - Kelola Transaksi')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 mb-3">
            <a href="{{ route('superadmin.transaksi') }}"
                class="btn {{ request()->routeIs('superadmin.transaksi') ? 'btn-primary' : 'btn-outline-primary' }}">
                Transaksi
            </a>
            <a href="{{ route('superadmin.transaksisatuan') }}"
                class="btn {{ request()->routeIs('superadmin.transaksisatuan') ? 'btn-primary' : 'btn-outline-primary' }}">
                Transaksi Satuan
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Transaksi</h4>
            <div class="table-responsive m-t-0">
                <table id="myTable" class="table display table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No Resi</th>
                            <th>TGL Transaksi</th>
                            <th>Customer</th>
                            <th>Karyawan</th>
                            <th>Jenis Laundri</th>
                            <th>Kg</th>
                            <th>Jumlah Lembar Pakaian (pcs)</th>
                            <th>Hari</th>
                            <th>Total</th>
                            <th>Catatan Admin</th>
                            <th>Info Pembayaran</th>
                            <th>Status Payment</th>
                            <th>Status Order</th>
                            <th>Keterangan Delivery</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($order as $item)
                            <tr>
                                <td>{{ $no }}</td>
                                <td style="font-weight:bold;">{{ $item->invoice }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d-m-y') }}</td>
                                <td>{{ $item->customer }}</td>
                                <td>{{ $item->karyawan ? $item->karyawan->name : 'Karyawan Tidak Tersedia' }}</td>
                                <td>
                                    {{ $item->price->nama ?? 'Nama Tidak Tersedia' }} -
                                    {{ $item->price->jenis ?? 'Jenis Tidak Tersedia' }}
                                </td>
                                <td>{{ $item->kg }}</td>
                                <td>{{ $item->jumlah_lembar_baju }}</td>
                                <td>{{ $item->hari }}</td>
                                <td>{{ Rupiah::getRupiah($item->harga_akhir) }}</td>
                                <td>{{ $item->catatan_admin }}</td>
                                <td>{{ $item->info_pembayaran ?? 'Info Pembayaran Tidak Tersedia' }}</td>
                                <td>
                                    @if ($item->status_payment == 'Pending')
                                        <a href="{{ route('superadmin.ubahstatusbayar', ['id' => $item->id, 'status_payment' => 'Success']) }}"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Ubah status pembayaran menjadi Lunas?')">Belum Bayar</a>
                                    @elseif($item->status_payment == 'Success')
                                        <a href="{{ route('superadmin.ubahstatusbayar', ['id' => $item->id, 'status_payment' => 'Pending']) }}"
                                            class="btn btn-sm btn-success"
                                            onclick="return confirm('Ubah status pembayaran menjadi Belum Bayar?')">Sudah Dibayar</a>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status_order == 'Antrian')
                                        <a class="btn btn-sm btn-warning"
                                            style="pointer-events: none; cursor: default; color: white;">Antrian</a>
                                    @elseif ($item->status_order == 'Process')
                                        <a class="btn btn-sm btn-primary"
                                            style="pointer-events: none; cursor: default; color: white;">Proses</a>
                                    @elseif($item->status_order == 'Done')
                                        <a class="btn btn-sm btn-info"
                                            style="pointer-events: none; cursor: default; color: white;">Selesai</a>
                                    @elseif($item->status_order == 'Delivery')
                                        <a class="btn btn-sm btn-success"
                                            style="pointer-events: none; cursor: default; color: white;">Sudah Diambil</a>
                                    @endif
                                </td>
                                <td>{{ $item->ket_delivery ?? 'Ket Deliv' }}</td>
                                <td>
                                    <form action="{{ route('superadmin.transaksi.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            <?php $no++; ?>
                        @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        // DATATABLE
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
