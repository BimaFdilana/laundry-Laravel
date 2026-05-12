@extends('layouts.backend')
@section('title', 'Konfirmasi Pembelian Paket')
@section('content')
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">Pembelian Menunggu Konfirmasi</h4>
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table id="waitTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Customer</th>
                                <th>Paket</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendingRequests as $req)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $req->user->name }}</td>
                                    <td>{{ $req->package_kg }} kg</td>
                                    <td>{{ $req->package_category }}</td>
                                    <td>Rp. {{ number_format((int) $req->package_price, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-warning">Menunggu</span></td>
                                    <td>
                                        <form method="POST" action="{{ route('konfirmasi.confirm', $req->id) }}"
                                            style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Konfirmasi</button>
                                        </form>

                                        <form method="POST" action="{{ route('konfirmasi.destroy', $req->id) }}"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Yakin ingin menghapus permintaan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada pembelian yang menunggu konfirmasi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Tabel Paket yang Sudah Dikonfirmasi --}}
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pembelian yang Sudah Dikonfirmasi</h4>
                <div class="table-responsive">
                    <table id="confirmedTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Customer</th>
                                <th>Paket</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($confirmedRequests as $req)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $req->user->name }}</td>
                                    <td>{{ $req->package_kg }} kg</td>
                                    <td>{{ $req->package_category }}</td>
                                    <td>Rp. {{ number_format((int) $req->package_price, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-success">Terkonfirmasi</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada pembelian yang dikonfirmasi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Ringkasan Total Kuota per Customer --}}
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title">Total Pembelian Paket</h4>

                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kategori</th>
                                <th>Nama Customer</th>
                                <th>Total Kuota (kg)</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($groupedTotals as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user['kategori'] }}</td>
                                    <td>{{ $user['name'] }}</td>
                                    <td>{{ $user['total_kuota'] }} kg</td>
                                    <td>Rp {{ number_format($user['total_harga'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data kuota terkonfirmasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#waitTable').DataTable();
        });

        $(document).ready(function() {
            $('#confirmedTable').DataTable();
        });

        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
@endsection
