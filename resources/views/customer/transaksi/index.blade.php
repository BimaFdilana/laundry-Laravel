@extends('layouts.backend')
@section('title', 'Transaksi Customer')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @elseif ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Transaksi</h4>
            <div class="table-responsive m-t-0">
                <table id="myTable" class="table display table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No Resi</th>
                            <th>Tanggal</th>
                            <th>Karyawan</th>
                            <th>Jenis Laundry</th>
                            <th>Berat (kg)</th>
                            <th>Jumlah (pcs)</th>
                            <th>Total</th>
                            <th>Catatan</th>
                            <th>Status Payment</th>
                            <th>Status Order</th>
                            <th>Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($order as $item)
                            <tr>
                                <td>{{ $no }}</td>
                                <td style="font-weight:bold;">{{ $item->invoice }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d-m-y') }}</td>
                                <td>{{ $item->karyawan ? $item->karyawan->name : 'Tidak tersedia' }}</td>
                                <td>
                                    {{ $item->price->nama ?? '-' }} -
                                    {{ $item->price->jenis ?? 'Jenis Tidak Tersedia' }}
                                </td>
                                <td>{{ $item->kg }}</td>
                                <td>{{ $item->jumlah_lembar_baju ?? '-' }}</td>
                                <td>{{ Rupiah::getRupiah($item->harga_akhir) }}</td>
                                <td>{{ $item->catatan_admin }}</td>
                                <td>
                                    @if ($item->status_payment == 'Pending')
                                        <button class="btn btn-sm btn-danger" style="opacity: 1" disabled>Harus Di
                                            Bayar</button>
                                    @elseif($item->status_payment == 'Success')
                                        <button class="btn btn-sm btn-success" style="opacity: 1" disabled>Lunas</button>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status_order == 'Antrian')
                                        <button class="btn btn-sm btn-warning" style="opacity: 1; color: white;"
                                            disabled>Dalam Antrian</button>
                                    @elseif ($item->status_order == 'Process')
                                        <button class="btn btn-sm btn-primary" style="opacity: 1; color: white;"
                                            disabled>Proses</button>
                                    @elseif ($item->status_order == 'Done')
                                        <button class="btn btn-sm btn-info" style="opacity: 1" disabled>Harus Di
                                            Ambil</button>
                                    @elseif($item->status_order == 'Delivery')
                                        <button class="btn btn-sm btn-success" style="opacity: 1" disabled>Selesai</button>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('customer.invoice', $item->invoice) }}"
                                        class="btn btn-sm btn-success">Invoice</a>
                                </td>
                            </tr>
                            <?php $no++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Transaksi Satuan</h4>
            <div class="table-responsive m-t-0">
                <table id="myTable2" class="table display table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No Resi</th>
                            <th>Tanggal</th>
                            <th>Karyawan</th>
                            <th>Pakaian/Barang</th>
                            <th>Jenis Laundry</th>
                            <th>Jumlah (pcs)</th>
                            <th>Total</th>
                            <th>Catatan</th>
                            <th>Status Payment</th>
                            <th>Status Order</th>
                            <th>Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order_satuan as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $item->invoice }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d-m-Y') }}</td>
                                <td>{{ $item->karyawan ? $item->karyawan->name : 'Tidak tersedia' }}</td>
                                <td>
                                    @foreach ($item->details as $detail)
                                        • {{ $detail->satuan->nama ?? '-' }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($item->details as $detail)
                                        • {{ $detail->satuan->jenis ?? '-' }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($item->details as $detail)
                                        • {{ $detail->pcs }}<br>
                                    @endforeach
                                </td>
                                <td>{{ Rupiah::getRupiah($item->harga_akhir) }}</td>
                                <td>{{ $item->catatan_admin }}</td>
                                <td>
                                    @if ($item->status_payment == 'Pending')
                                        <button class="btn btn-sm btn-danger" style="opacity: 1" disabled>Harus Di
                                            Bayar</button>
                                    @elseif($item->status_payment == 'Success')
                                        <button class="btn btn-sm btn-success" style="opacity: 1" disabled>Lunas</button>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status_order == 'Antrian')
                                        <button class="btn btn-sm btn-warning" style="opacity: 1; color: white;"
                                            disabled>Dalam Antrian</button>
                                    @elseif ($item->status_order == 'Process')
                                        <button class="btn btn-sm btn-primary" style="opacity: 1; color: white;"
                                            disabled>Proses</button>
                                    @elseif ($item->status_order == 'Done')
                                        <button class="btn btn-sm btn-info" style="opacity: 1" disabled>Harus Di
                                            Ambil</button>
                                    @elseif($item->status_order == 'Delivery')
                                        <button class="btn btn-sm btn-success" style="opacity: 1" disabled>Selesai</button>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('customer.invoicesatuan', $item->invoice) }}"
                                        class="btn btn-sm btn-success">Invoice</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#myTable').DataTable();
            $('#myTable2').DataTable();
        });
    </script>
@endsection
