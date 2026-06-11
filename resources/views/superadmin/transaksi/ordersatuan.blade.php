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
            <h4 class="card-title">Transaksi Satuan</h4>

            <form method="GET" action="{{ route('superadmin.transaksisatuan') }}" class="form-inline mb-3">
                <input type="text" name="search" class="form-control mr-2" placeholder="Cari invoice/customer..."
                    value="{{ request('search') }}">
                <input type="date" name="dari" class="form-control mr-2" value="{{ request('dari') }}">
                <span class="mr-2">s/d</span>
                <input type="date" name="sampai" class="form-control mr-2" value="{{ request('sampai') }}">
                <select name="status_payment" class="form-control mr-2">
                    <option value="">-- Status --</option>
                    <option value="Pending" {{ request('status_payment') == 'Pending' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="Success" {{ request('status_payment') == 'Success' ? 'selected' : '' }}>Lunas</option>
                </select>
                <button type="submit" class="btn btn-info mr-2">Filter</button>
                @if (request()->hasAny(['search', 'dari', 'sampai', 'status_payment']))
                    <a href="{{ route('superadmin.transaksisatuan') }}" class="btn btn-secondary">Reset</a>
                @endif
            </form>

            <p class="text-muted">Menampilkan {{ $ordersatuan->firstItem() ?? 0 }} - {{ $ordersatuan->lastItem() ?? 0 }} dari {{ $ordersatuan->total() }} transaksi satuan</p>

            <div class="table-responsive m-t-0">
                <table class="table display table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>No Resi</th>
                            <th>TGL Transaksi</th>
                            <th>Customer</th>
                            <th>Karyawan</th>
                            <th>Pakaian/Barang</th>
                            <th>Jenis Laundri</th>
                            <th>Pcs</th>
                            <th>Hari</th>
                            <th>Harga</th>
                            <th>Total</th>
                            <th>Catatan Admin</th>
                            <th>Info Pembayaran</th>
                            <th>Status Payment</th>
                            <th>Status Order</th>
                            <th>Ket Delivery</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ordersatuan as $item)
                            <tr>
                                <td>{{ $loop->iteration + ($ordersatuan->currentPage() - 1) * $ordersatuan->perPage() }}</td>
                                <td style="font-weight:bold;">{{ $item->invoice }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d-m-y') }}</td>
                                <td>{{ $item->customer }}</td>
                                <td>{{ $item->karyawan ? $item->karyawan->name : 'Tidak Tersedia' }}</td>
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
                                <td>
                                    @foreach ($item->details as $detail)
                                        • {{ $detail->hari }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($item->details as $detail)
                                        • {{ Rupiah::getRupiah($detail->harga) }}<br>
                                    @endforeach
                                </td>
                                <td>{{ Rupiah::getRupiah($item->harga_akhir) }}</td>
                                <td>{{ $item->catatan_admin }}</td>
                                <td>{{ $item->info_pembayaran ?? '-' }}</td>
                                <td>
                                    @if ($item->status_payment == 'Pending')
                                        <a href="{{ route('superadmin.ubahstatusbayarsatuan', ['id' => $item->id, 'status_payment' => 'Success']) }}"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Ubah status pembayaran menjadi Lunas?')">Belum Bayar</a>
                                    @elseif($item->status_payment == 'Success')
                                        <a href="{{ route('superadmin.ubahstatusbayarsatuan', ['id' => $item->id, 'status_payment' => 'Pending']) }}"
                                            class="btn btn-sm btn-success"
                                            onclick="return confirm('Ubah status pembayaran menjadi Belum Bayar?')">Sudah Dibayar</a>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status_order == 'Antrian')
                                        <span class="badge badge-warning">Antrian</span>
                                    @elseif ($item->status_order == 'Process')
                                        <span class="badge badge-primary">Proses</span>
                                    @elseif($item->status_order == 'Done')
                                        <span class="badge badge-info">Selesai</span>
                                    @elseif($item->status_order == 'Delivery')
                                        <span class="badge badge-success">Sudah Diambil</span>
                                    @endif
                                </td>
                                <td>{{ $item->ket_delivery ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('superadmin.transaksisatuan.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus transaksi satuan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $ordersatuan->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
@endsection
