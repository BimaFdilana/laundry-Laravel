@extends('layouts.backend')
@section('title', 'Admin - Kelola Transaksi')
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

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                Transaksi
                <a href="{{ url('add-order') }}" class="btn btn-primary">Tambah</a>
            </h4>
            <h6>Info : <code> Untuk Mengubah Status Order & Pembayaran Klik Pada Bagian 'Action' Masing-masing.</code></h6>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($order as $item)
                            <tr>
                                <td>{{ $no }}</td>
                                <td style="font-weight:bold;">{{ $item->invoice }}</td>
                                <td>{{ carbon\carbon::parse($item->tgl_transaksi)->format('d-m-y') }}</td>
                                <td>{{ $item->customer }}</td>
                                <td>{{ $item->karyawan ? $item->karyawan->name : 'Karyawan Tidak Tersedia' }}</td>
                                <td>
                                    {{ $item->price->nama ?? 'Nama Tidak Tersedia' }} -
                                    {{ $item->price->jenis ?? 'Jenis Tidak Tersedia' }}
                                </td>
                                <td>{{ $item->kg }}</td>
                                <td>{{ $item->jumlah_lembar_baju }}</td>
                                <td>{{ $item->hari }}</td>
                                <td>
                                    {{ Rupiah::getRupiah($item->harga_akhir) }}
                                </td>
                                <td>{{ $item->catatan_admin }}</td>
                                <td>{{ $item->info_pembayaran ?? 'Info Pembayaran Tidak Tersedia' }}</td>
                                <td>
                                    @if ($item->status_payment == 'Pending')
                                        <a class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-id-pay="{{ $item->id }}" data-id-name="{{ $item->customer }}"
                                            data-id-bayar="{{ $item->status_payment }}" id="klick"
                                            data-target="#ubah_status_pay" style="color:white">Bayar</a>
                                    @elseif($item->status_payment == 'Success')
                                        <a class="btn btn-sm btn-success" data-toggle="modal"
                                            data-id-pay="{{ $item->id }}" data-id-name="{{ $item->customer }}"
                                            data-id-bayar="{{ $item->status_payment }}" id="klick"
                                            data-target="#ubah_status_pay" style="color:white">Sudah Dibayar</a>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status_order == 'Antrian')
                                        <a class="btn btn-sm btn-warning" data-toggle="modal" data-id="{{ $item->id }}"
                                            data-id-nama="{{ $item->customer }}" data-id-order="{{ $item->status_order }}"
                                            id="klikmodal" data-target="#ubah_status" style="color:white">Antrian</a>
                                    @elseif ($item->status_order == 'Process')
                                        <a class="btn btn-sm btn-primary" data-toggle="modal" data-id="{{ $item->id }}"
                                            data-id-nama="{{ $item->customer }}" data-id-order="{{ $item->status_order }}"
                                            id="klikmodal" data-target="#ubah_status" style="color:white">Proses</a>
                                    @elseif($item->status_order == 'Done')
                                        <a class="btn btn-sm btn-info" data-toggle="modal" data-id="{{ $item->id }}"
                                            data-id-nama="{{ $item->customer }}" data-id-order="{{ $item->status_order }}"
                                            id="klikmodal" data-target="#ubah_status" style="color:white">Selesai</a>
                                    @elseif($item->status_order == 'Delivery')
                                        <a class="btn btn-sm btn-success" style="color:white">Sudah Diambil</a>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-success edit-ket-delivery"
                                        data-id="{{ $item->id }}" data-ket="{{ $item->ket_delivery ?? '' }}">
                                        {{ $item->ket_delivery ?? 'Ket Deliv' }}
                                    </a>
                                </td>

                            </tr>
                            <?php $no++; ?>
                        @endforeach
                    </tbody>
                </table>

            </div>

            @include('modul_admin.transaksi.statusorder')
            @include('modul_admin.transaksi.statusbayar')
            <!-- Modal Edit Ket Delivery -->
            <div class="modal fade" id="modalEditKetDelivery" tabindex="-1" role="dialog"
                aria-labelledby="ketDeliveryModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form id="formKetDelivery">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Keterangan Delivery</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="id_transaksi_delivery" name="id">
                                <div class="form-group">
                                    <label for="ket_delivery">Keterangan</label>
                                    <textarea class="form-control" id="ket_delivery" name="ket_delivery" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        // Tampilkan Modal Ubah Status Order
        $(document).on('click', '#klikmodal', function() {
            var id = $(this).attr('data-id');
            var customer = $(this).attr('data-id-nama');
            var status_order = $(this).attr('data-id-order');
            $("#id").val(id)
            $("#customer").val(customer)
            $("#status_order").val(status_order)
        });

        // Proses Ubah Status Order
        $(document).on('click', '#save_status', function() {
            var id = $("#id").val();
            var customer = $("#customer").val();
            var status_order = $("#status_order").val();

            $.get('{{ Url('ubah-status-order') }}', {
                '_token': $('meta[name=csrf-token]').attr('content'),
                id: id,
                customer: customer,
                status_order: status_order
            }, function(resp) {
                $("#id").val('');
                $("#customer").val('');
                $("#status_order").val('');

                location.reload();
            });
        });

        // Tampilkan Modal Ubah Status Pembayaran
        $(document).on('click', '#klick', function() {
            var id = $(this).attr('data-id-pay');
            var customer = $(this).attr('data-id-name');
            var status_payment = $(this).attr('data-id-bayar');
            $("#id_bayar").val(id)
            $("#customer_pay").val(customer)
            $("#status_payment").val(status_payment)
        });

        // Proses Ubah Status Pembayaran
        $(document).on('click', '#simpan_status', function() {
            var id = $("#id_bayar").val();
            var customer = $("#customer_pay").val();
            var status_payment = $("#status_payment").val();

            $.get('{{ Url('ubah-status-bayar') }}', {
                '_token': $('meta[name=csrf-token]').attr('content'),
                id: id,
                customer: customer,
                status_payment: status_payment
            }, function(resp) {
                $("#id_bayar").val('');
                $("#customer_pay").val('');
                $("#status_payment").val('');
                location.reload();
            });
        });

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

        // Buka Modal Ket Delivery
        $(document).on('click', '.edit-ket-delivery', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var ket = $(this).data('ket');

            $('#id_transaksi_delivery').val(id);
            $('#ket_delivery').val(ket);
            $('#modalEditKetDelivery').modal('show');
        });

        // Submit Form Update Ket Delivery
        $('#formKetDelivery').submit(function(e) {
            e.preventDefault();
            let id = $('#id_transaksi_delivery').val();
            let ket_delivery = $('#ket_delivery').val();

            $.ajax({
                url: '{{ url('update-ket-delivery') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    ket_delivery: ket_delivery
                },
                success: function(response) {
                    $('#modalEditKetDelivery').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Gagal menyimpan. Silakan coba lagi.');
                }
            });
        });
    </script>
@endsection
