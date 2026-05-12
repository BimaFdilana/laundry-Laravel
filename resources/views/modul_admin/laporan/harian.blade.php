@extends('layouts.backend')
@section('title', 'Admin - Laporan Harian')
@section('style')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
@endsection
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

    {{-- Form Filter Tanggal --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin-laporan.harian') }}">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="tanggal">Pilih Tanggal</label>
                                        <input type="date" name="tanggal" class="form-control"
                                            value="{{ request('tanggal', $tanggal) }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary mt-2">Tampilkan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div class="row">
        <!-- Laundry Reguler -->
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header d-flex align-items-start pb-0 cursor-pointer"
                    onclick="toggleCardBody('laundryRegulerBody')">
                    <div>
                        <h2 class="text-bold-700 mb-0">{{ number_format($jumlahKg, 2, ',', '.') }} Kg ({{ $laporanKgCustomer->sum('total_pcs') }}
                            Pcs)</h2>
                        <p>Laundry ({{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }})</p>
                    </div>
                </div>
                <div class="card-body" id="laundryRegulerBody" style="display: none;">
                    <div class="row">
                        <div class="col">
                            @foreach ($detailKgPerJenis as $detail)
                                <div class="d-flex justify-content-between">
                                    <span>{{ $detail->jenis_grouped }}</span>
                                    <span>{{ number_format($detail->total_kg, 2, ',', '.') }} Kg</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="col">
                            @foreach ($detailPcsPerJenis as $detail)
                                <div class="d-flex justify-content-between">
                                    <span>({{ $detail->total_pcs }} pcs)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laundry Satuan -->
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header d-flex align-items-start pb-0 cursor-pointer"
                    onclick="toggleCardBody('laundrySatuanBody')">
                    <div>
                        <h2 class="text-bold-700 mb-0">{{ number_format($laporanSatuanCustomer->sum('total_pcs'), 2, ',', '.') }} Pcs</h2>
                        <p>Laundry Satuan ({{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }})</p>
                    </div>
                </div>
                <div class="card-body" id="laundrySatuanBody" style="display: none;">
                    <!-- Isi tambahan untuk laundry satuan jika ada -->
                    <p class="text-muted">Detail laundry satuan belum dimasukkan.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Laundry Customer Reguler --}}
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Laundry Customer (Reguler)</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped display">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Customer</th>
                            <th>Total Kg</th>
                            <th>Total Lembar</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporanKgCustomer as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->customers->name ?? '-' }}</td>
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

    {{-- Tabel Laundry Customer Satuan --}}
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Laundry Customer (Satuan)</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped display">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Customer</th>
                            <th>Total Pcs</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporanSatuanCustomer as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->customers->name ?? '-' }}</td>
                                <td>{{ number_format($item->total_pcs, 2, ',', '.') }} pcs</td>
                                <td>Rp. {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tabel Kinerja Karyawan Reguler --}}
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Kinerja Karyawan (Reguler)</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped display">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Karyawan</th>
                            <th>Total Kg</th>
                            <th>Total Lembar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporanKaryawanReguler as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->karyawan->name ?? '-' }}</td>
                                <td>{{ number_format($item->total_kg, 2, ',', '.') }} kg</td>
                                <td>{{ $item->total_lembar }} pcs</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tabel Kinerja Karyawan Satuan --}}
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Kinerja Karyawan (Satuan)</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped display">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Karyawan</th>
                            <th>Total Pcs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporanKaryawanSatuan as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->karyawan->name ?? '-' }}</td>
                                <td>{{ number_format($item->total_lembar, 2, ',', '.') }} pcs</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('table.display').DataTable();
        });

        function toggleCardBody(id) {
            const el = document.getElementById(id);
            if (el.style.display === 'none') {
                el.style.display = 'block';
            } else {
                el.style.display = 'none';
            }
        }
    </script>
@endsection
