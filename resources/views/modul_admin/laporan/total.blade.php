@extends('layouts.backend')
@section('title', 'Admin - Laporan Laundry')
@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group" role="group" aria-label="Filter Laporan">
                <a href="{{ route('admin-laporan.harian') }}"
                    class="btn {{ request()->routeIs('admin-laporan.harian') ? 'btn-primary' : 'btn-outline-primary' }}">
                    Harian
                </a>
                <a href="{{ route('admin-laporan.bulanan') }}"
                    class="btn {{ request()->routeIs('admin-laporan.bulanan') ? 'btn-primary' : 'btn-outline-primary' }}">
                    Bulanan
                </a>
                <a href="{{ route('admin-laporan.tahunan') }}"
                    class="btn {{ request()->routeIs('admin-laporan.tahunan') ? 'btn-primary' : 'btn-outline-primary' }}">
                    Tahunan
                </a>
                <a href="{{ route('admin-laporan.total') }}"
                    class="btn {{ request()->routeIs('admin-laporan.total') ? 'btn-primary' : 'btn-outline-primary' }}">
                    Total
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header cursor-pointer" onclick="toggleCardBody('laundryRegulerBody')">
                    <div>
                        <h2 class="text-bold-700 mb-0">{{ number_format($jumlahKg, 2, ',', '.') }} Kg ({{ $laporanKgCustomer->sum('total_pcs') }}
                            Pcs)</h2>
                        <p>Laundry Reguler</p>
                    </div>
                </div>
                <div class="card-body" id="laundryRegulerBody" style="display: none;">
                    <div class="row">
                        <div class="col">
                            @foreach ($detailKgPerJenis as $item)
                                <div class="d-flex justify-content-between">
                                    <span>{{ $item->jenis_grouped }}</span>
                                    <span>{{ number_format($item->total_kg, 1) }} Kg</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="col">
                            @foreach ($detailPcsPerJenis as $item)
                                <div class="d-flex justify-content-between">
                                    <span>({{ $item->total_pcs }} pcs)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header d-flex align-items-start pb-0 cursor-pointer"
                    onclick="toggleCardBody('laundrySatuanBody')">
                    <div>
                        <h2 class="text-bold-700 mb-0">
                            {{ number_format($laporanSatuanCustomer->sum('total_pcs'), 2, ',', '.') }} Pcs</h2>
                        <p>Laundry Satuan</p>
                    </div>
                </div>
                <div class="card-body" id="laundrySatuanBody" style="display: none;">
                    <!-- Isi tambahan untuk laundry satuan jika ada -->
                    <p class="text-muted">Detail laundry satuan belum dimasukkan.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Laundry Customer</h4>
                    <div class="table-responsive m-t-0">
                        <table id="tableReguler" class="table display table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Customer</th>
                                    <th>Total Kg</th>
                                    <th>Total Lembar Baju</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporanKgCustomer as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->customers->name ?? 'Tidak Diketahui' }}</td>
                                        <td>{{ number_format($item->total_kg, 2, ',', '.') }} kg</td>
                                        <td>{{ $item->total_pcs }} pcs</td>
                                        <td>Rp. {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Laundry Satuan Customer</h4>
                    <div class="table-responsive m-t-0">
                        <table id="tableSatuan" class="table display table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Customer</th>
                                    <th>Total Pcs</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporanSatuanCustomer as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->customers->name ?? 'Tidak Diketahui' }}</td>
                                        <td>{{ number_format($item->total_pcs, 2, ',', '.') }} pcs</td>
                                        <td>Rp. {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Kinerja Karyawan Laundry</h4>
                    <div class="table-responsive m-t-0">
                        <table id="tableKaryawan" class="table display table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Karyawan</th>
                                    <th>Total Kg</th>
                                    <th>Total Lembar Baju</th>
                                </tr>
                            </thead>
                            <tbody id="refresh_body">
                                @foreach ($laporanKaryawanReguler as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->karyawan->name ?? 'Tidak Diketahui' }}</td>
                                        <td>{{ number_format($item->total_kg, 2, ',', '.') }} kg</td>
                                        <td>{{ $item->total_lembar }} pcs</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Kinerja Karyawan Laundry Satuan</h4>
                    <div class="table-responsive m-t-0">
                        <table id="tableKaryawanSatuan" class="table display table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Karyawan</th>
                                    <th>Total Pcs</th>
                                </tr>
                            </thead>
                            <tbody id="refresh_body">
                                @foreach ($laporanKaryawanSatuan as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->karyawan->name ?? 'Tidak Diketahui' }}</td>
                                        <td>{{ number_format($item->total_lembar, 2, ',', '.') }} pcs</td>
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
            $('#tableReguler').DataTable();
            $('#tableSatuan').DataTable();
            $('#tableKaryawan').DataTable();
            $('#tableKaryawanSatuan').DataTable();
        });

        function toggleCardBody(id) {
            const el = document.getElementById(id);
            el.style.display = (el.style.display === 'none') ? 'block' : 'none';
        }
    </script>
@endsection
