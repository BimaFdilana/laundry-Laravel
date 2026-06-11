@extends('layouts.backend')
@section('title', 'Super Admin - Perbandingan Data')
@section('header', 'Perbandingan Data Paket & Transaksi')
@section('content')
    @php
        function hitungPersen($sekarang, $pembanding) {
            if ($pembanding == 0) return $sekarang > 0 ? 100 : 0;
            return round((($sekarang - $pembanding) / $pembanding) * 100, 1);
        }

        $persenPaketTahun = hitungPersen($paketHariIni['total_nominal'], $paketTahunLalu['total_nominal']);
        $persenPaketBulan = hitungPersen($paketHariIni['total_nominal'], $paketBulanLalu['total_nominal']);
        $persenTrxTahun = hitungPersen($trxHariIni['total_nominal'], $trxTahunLalu['total_nominal']);
        $persenTrxBulan = hitungPersen($trxHariIni['total_nominal'], $trxBulanLalu['total_nominal']);
        $persenJmlTahun = hitungPersen($trxHariIni['jumlah'], $trxTahunLalu['jumlah']);
        $persenJmlBulan = hitungPersen($trxHariIni['jumlah'], $trxBulanLalu['jumlah']);
    @endphp

    <div class="row mb-2">
        <div class="col-12">
            <form method="GET" action="{{ route('laporan.perbandingan') }}" class="form-inline">
                <label class="mr-2">Tanggal Acuan:</label>
                <input type="date" name="tanggal" class="form-control mr-2"
                    value="{{ request('tanggal', $hariIni->toDateString()) }}">
                <button type="submit" class="btn btn-primary">Bandingkan</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h5>Perbandingan berdasarkan: <strong>{{ $hariIni->format('d F Y') }}</strong></h5>
            <p class="text-muted">vs Tahun Lalu: {{ $tahunLalu->format('d F Y') }} | vs Bulan Lalu: {{ $bulanLalu->format('d F Y') }}</p>
        </div>
    </div>

    {{-- Perbandingan Data Paket --}}
    <h4 class="mt-2">Data Paket</h4>
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Hari Ini</h6>
                    <h3 class="mb-0">{{ $paketHariIni['total_kg'] }} kg</h3>
                    <p>Rp. {{ number_format($paketHariIni['total_nominal'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">vs Tahun Lalu ({{ $tahunLalu->format('d/m/Y') }})</h6>
                    <h3 class="mb-0">{{ $paketTahunLalu['total_kg'] }} kg</h3>
                    <p>Rp. {{ number_format($paketTahunLalu['total_nominal'], 0, ',', '.') }}</p>
                    <span class="badge {{ $persenPaketTahun >= 0 ? 'badge-success' : 'badge-danger' }}">
                        {{ $persenPaketTahun >= 0 ? '+' : '' }}{{ $persenPaketTahun }}%
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">vs Bulan Lalu ({{ $bulanLalu->format('d/m/Y') }})</h6>
                    <h3 class="mb-0">{{ $paketBulanLalu['total_kg'] }} kg</h3>
                    <p>Rp. {{ number_format($paketBulanLalu['total_nominal'], 0, ',', '.') }}</p>
                    <span class="badge {{ $persenPaketBulan >= 0 ? 'badge-success' : 'badge-danger' }}">
                        {{ $persenPaketBulan >= 0 ? '+' : '' }}{{ $persenPaketBulan }}%
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Perbandingan Data Transaksi --}}
    <h4 class="mt-2">Data Transaksi</h4>
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Hari Ini</h6>
                    <h3 class="mb-0">{{ $trxHariIni['jumlah'] }} transaksi</h3>
                    <p>Rp. {{ number_format($trxHariIni['total_nominal'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">vs Tahun Lalu ({{ $tahunLalu->format('d/m/Y') }})</h6>
                    <h3 class="mb-0">{{ $trxTahunLalu['jumlah'] }} transaksi</h3>
                    <p>Rp. {{ number_format($trxTahunLalu['total_nominal'], 0, ',', '.') }}</p>
                    <span class="badge {{ $persenTrxTahun >= 0 ? 'badge-success' : 'badge-danger' }}">
                        {{ $persenTrxTahun >= 0 ? '+' : '' }}{{ $persenTrxTahun }}%
                    </span>
                    <span class="badge {{ $persenJmlTahun >= 0 ? 'badge-success' : 'badge-danger' }} ml-1">
                        Jml: {{ $persenJmlTahun >= 0 ? '+' : '' }}{{ $persenJmlTahun }}%
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">vs Bulan Lalu ({{ $bulanLalu->format('d/m/Y') }})</h6>
                    <h3 class="mb-0">{{ $trxBulanLalu['jumlah'] }} transaksi</h3>
                    <p>Rp. {{ number_format($trxBulanLalu['total_nominal'], 0, ',', '.') }}</p>
                    <span class="badge {{ $persenTrxBulan >= 0 ? 'badge-success' : 'badge-danger' }}">
                        {{ $persenTrxBulan >= 0 ? '+' : '' }}{{ $persenTrxBulan }}%
                    </span>
                    <span class="badge {{ $persenJmlBulan >= 0 ? 'badge-success' : 'badge-danger' }} ml-1">
                        Jml: {{ $persenJmlBulan >= 0 ? '+' : '' }}{{ $persenJmlBulan }}%
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Bar Chart --}}
    <div class="row mt-2">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Perbandingan Nominal Paket</h4>
                </div>
                <div class="card-body">
                    <div id="chart-paket"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Perbandingan Nominal Transaksi</h4>
                </div>
                <div class="card-body">
                    <div id="chart-transaksi"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="{{ asset('backend/vendors/css/charts/apexcharts.css') }}">
    <script src="{{ asset('backend/vendors/js/charts/apexcharts.min.js') }}"></script>
    <script>
        var optPaket = {
            chart: { type: 'bar', height: 300 },
            series: [{
                name: 'Nominal Paket',
                data: [{{ $paketHariIni['total_nominal'] }}, {{ $paketBulanLalu['total_nominal'] }}, {{ $paketTahunLalu['total_nominal'] }}]
            }],
            xaxis: {
                categories: ['Hari Ini ({{ $hariIni->format("d/m/Y") }})', 'Bulan Lalu ({{ $bulanLalu->format("d/m/Y") }})', 'Tahun Lalu ({{ $tahunLalu->format("d/m/Y") }})']
            },
            colors: ['#0fb9b1'],
            plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } },
            dataLabels: { enabled: false }
        };
        new ApexCharts(document.querySelector("#chart-paket"), optPaket).render();

        var optTrx = {
            chart: { type: 'bar', height: 300 },
            series: [
                { name: 'Jumlah Transaksi', data: [{{ $trxHariIni['jumlah'] }}, {{ $trxBulanLalu['jumlah'] }}, {{ $trxTahunLalu['jumlah'] }}] },
                { name: 'Nominal (Rp)', data: [{{ $trxHariIni['total_nominal'] }}, {{ $trxBulanLalu['total_nominal'] }}, {{ $trxTahunLalu['total_nominal'] }}] }
            ],
            xaxis: {
                categories: ['Hari Ini ({{ $hariIni->format("d/m/Y") }})', 'Bulan Lalu ({{ $bulanLalu->format("d/m/Y") }})', 'Tahun Lalu ({{ $tahunLalu->format("d/m/Y") }})']
            },
            colors: ['#667eea', '#0fb9b1'],
            plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } },
            dataLabels: { enabled: false }
        };
        new ApexCharts(document.querySelector("#chart-transaksi"), optTrx).render();
    </script>
@endsection
