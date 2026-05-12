@extends('layouts.backend')
@section('title', 'Super Admin - Data Finance')
@section('header', 'Data Finance')
@section('content')
    <div class="row">
        {{-- Target Finance --}}
        <div class="col-12">
            <div class="card bg-light-primary">
                <div class="card-header">
                    <h4 class="card-title">Target Finance</h4>
                </div>
                <div class="card-body row">
                    <div class="col-4 mb-1">
                        <div class="text-bold-600">Target Harian</div>
                        <div class="text-danger">{{ \App\Helpers\FormatHelper::shortRupiah($targetHari) }}</div>
                    </div>
                    <div class="col-4 mb-1">
                        <div class="text-bold-600">Target Bulanan</div>
                        <div class="text-warning">{{ \App\Helpers\FormatHelper::shortRupiah($targetBulan) }}</div>
                    </div>
                    <div class="col-4 mb-1">
                        <div class="text-bold-600">Target Tahunan</div>
                        <div class="text-primary">{{ \App\Helpers\FormatHelper::shortRupiah($targetTahun) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <form method="GET" action="{{ route('superadmin.finance') }}" class="form-inline">
                            <label for="hari" class="mr-2">Hari:</label>
                            <select name="hari" id="hari" class="form-control mr-2">
                                <option value="">--</option>
                                @for ($d = 1; $d <= 31; $d++)
                                    <option value="{{ $d }}" {{ request('hari') == $d ? 'selected' : '' }}>
                                        {{ $d }}
                                    </option>
                                @endfor
                            </select>

                            <label for="bulan" class="mr-2">Bulan:</label>
                            <select name="bulan" id="bulan" class="form-control mr-2">
                                <option value="">--</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endfor
                            </select>

                            <label for="tahun" class="mr-2">Tahun:</label>
                            <select name="tahun" id="tahun" class="form-control mr-2">
                                <option value="">--</option>
                                @for ($y = date('Y'); $y >= 2022; $y--)
                                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                                        {{ $y }}</option>
                                @endfor
                            </select>

                            <button type="submit" class="btn btn-info">Filter</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Laporan Keuangan</h4>
                </div>
                <div class="card-body pt-50">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-6">
                                    <span>Total Transaksi Reguler</span>
                                </div>
                                <div class="col-6">
                                    <span>{{ Rupiah::getRupiah($totalTransaksi) }}</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-6">
                                    <span>Total Transaksi Satuan</span>
                                </div>
                                <div class="col-6">
                                    <span>{{ Rupiah::getRupiah($totalSatuan) }}</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-6">
                                    <span>Total Paket Laundry (Kuota)</span>
                                </div>
                                <div class="col-6">
                                    <span>{{ Rupiah::getRupiah($totalKuotaLunas) }}</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-6">
                                    <span>Total Pemasukan Manual</span>
                                </div>
                                <div class="col-6">
                                    <span>{{ Rupiah::getRupiah($totalPemasukanManual) }}</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-6">
                                    <span><b>Total Pemasukan</b></span>
                                </div>
                                <div class="col-6">
                                    <span><b>{{ Rupiah::getRupiah($totalPemasukanBersih) }}</b></span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-6">
                                    <span>Total Pengeluaran</span>
                                </div>
                                <div class="col-6">
                                    <span>{{ Rupiah::getRupiah($pengeluaran) }}</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item text-success">
                            <div class="row">
                                <div class="col-6">
                                    <span class="text-info"><b>Laba Bersih</b></span>
                                </div>
                                <div class="col-6">
                                    <span class="text-info"><b>{{ Rupiah::getRupiah($labaBersih) }}</b></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Total Transaksi Belum Lunas</h4>
                            <button class="btn btn-sm btn-outline-danger" type="button" data-toggle="collapse"
                                data-target="#pendingDetail" aria-expanded="false" aria-controls="pendingDetail">
                                Lihat Detail
                            </button>
                        </div>
                        <div class="card-body">
                            <h5 class="text-danger">{{ Rupiah::getRupiah($totalUtang) }}</h5>
                        </div>
                    </div>

                    {{-- TABEL TRANSAKSI PENDING --}}
                    <div class="collapse mt-1" id="pendingDetail">
                        <div class="card card-body table-responsive">
                            <h6>Transaksi Reguler</h6>
                            <table class="table zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Customer</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    @foreach ($utangReguler as $i => $item)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $item->customer ?? '-' }}</td>
                                            <td>{{ $item->created_at->format('d M Y') }}</td>
                                            <td>{{ Rupiah::getRupiah($item->harga_akhir) }}</td>
                                        </tr>
                                        <?php $no++; ?>
                                    @endforeach
                                </tbody>
                            </table>

                            <h6 class="mt-3">Transaksi Satuan</h6>
                            <table class="table zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Customer</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    @foreach ($utangSatuan as $i => $item)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $item->customer ?? '-' }}</td>
                                            <td>{{ $item->created_at->format('d M Y') }}</td>
                                            <td>{{ Rupiah::getRupiah($item->harga_akhir) }}</td>
                                        </tr>
                                        <?php $no++; ?>
                                    @endforeach
                                </tbody>
                            </table>

                            <h6 class="mt-3">Pembelian Kuota</h6>
                            <table class="table zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Customer</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    @foreach ($kuotaPending as $i => $item)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $item->pemasukan ?? '-' }}</td>
                                            <td>
                                                {{ ($item->tanggal ? \Carbon\Carbon::parse($item->tanggal) : $item->created_at)->format('d M Y') }}
                                            </td>
                                            <td>{{ Rupiah::getRupiah($item->total) }}</td>
                                        </tr>
                                        <?php $no++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Total Diskon</h4>
                            <button class="btn btn-sm btn-outline-warning" type="button" data-toggle="collapse"
                                data-target="#diskonDetail" aria-expanded="false" aria-controls="diskonDetail">
                                Lihat Detail
                            </button>
                        </div>
                        <div class="card-body">
                            <h5 class="text-warning">{{ Rupiah::getRupiah($totalDiskon) }}</h5>
                        </div>
                    </div>

                    <div class="collapse mt-1" id="diskonDetail">
                        <div class="card card-body table-responsive">
                            <h6>Diskon Transaksi Reguler</h6>
                            <table class="table zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Customer</th>
                                        <th>Tanggal</th>
                                        <th>Diskon</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($diskonTransaksiList as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $item->customer ?? '-' }}</td>
                                            <td>{{ $item->created_at->format('d M Y') }}</td>
                                            <td>{{ Rupiah::getRupiah($item->disc) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <h6 class="mt-3">Diskon Transaksi Satuan</h6>
                            <table class="table zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Customer</th>
                                        <th>Tanggal</th>
                                        <th>Diskon</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($diskonSatuanList as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $item->customer ?? '-' }}</td>
                                            <td>{{ $item->created_at->format('d M Y') }}</td>
                                            <td>{{ Rupiah::getRupiah($item->disc) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <h6 class="mt-3">Diskon Pembelian Kuota</h6>
                            <table class="table zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Customer</th>
                                        <th>Tanggal</th>
                                        <th>Diskon</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($diskonKuotaList as $item)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $item->pemasukan ?? '-' }}</td>
                                            <td>{{ $item->created_at->format('d M Y') }}</td>
                                            <td>
                                                @php
                                                    preg_match('/Diskon:\s*(\d+)/i', $item->keterangan, $matches);
                                                    $diskon = isset($matches[1]) ? (int) $matches[1] : 0;
                                                @endphp
                                                {{ Rupiah::getRupiah($diskon) }}
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
    </div>
@endsection
@section('scripts')
@endsection
