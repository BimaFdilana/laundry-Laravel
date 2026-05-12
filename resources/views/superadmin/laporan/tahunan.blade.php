@extends('layouts.backend')
@section('title', 'Super Admin - Laporan Laundry')
@section('style')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="btn-group" role="group" aria-label="Filter Laporan">
                <a href="{{ route('laporan.harian') }}"
                    class="btn {{ request()->routeIs('laporan.harian') ? 'btn-primary' : 'btn-outline-primary' }}">
                    Harian
                </a>
                <a href="{{ route('laporan.bulanan') }}"
                    class="btn {{ request()->routeIs('laporan.bulanan') ? 'btn-primary' : 'btn-outline-primary' }}">
                    Bulanan
                </a>
                <a href="{{ route('laporan.tahunan') }}"
                    class="btn {{ request()->routeIs('laporan.tahunan') ? 'btn-primary' : 'btn-outline-primary' }}">
                    Tahunan
                </a>
                <a href="{{ route('laporan.total') }}"
                    class="btn {{ request()->routeIs('laporan.total') ? 'btn-primary' : 'btn-outline-primary' }}">
                    Total
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('laporan.tahunan') }}">
                        @if ($tahun)
                            <div class="alert alert-info">
                                Menampilkan laporan untuk:
                                <strong>{{ \Carbon\Carbon::createFromDate($tahun)->translatedFormat('Y') }}</strong>
                            </div>
                        @endif

                        <div class="form-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tahun</label>
                                        <select name="tahun" class="form-control">
                                            @for ($i = 2024; $i <= now()->year; $i++)
                                                <option value="{{ $i }}"
                                                    {{ (request('tahun') ?? now()->year) == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <br>
                                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header cursor-pointer" onclick="toggleCardBody('laundryRegulerBody')">
                    <div>
                        <h2 class="text-bold-700 mb-0">{{ number_format($jumlahKg, 2, ',', '.') }} Kg
                            ({{ $laporanKgCustomer->where('tahun', $tahun)->sum('total_pcs') }}
                            Pcs)</h2>
                        <p>Laundry Reguler {{ $tahun }}</p>
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
                            {{ number_format($laporanSatuanCustomer->where('tahun', $tahun)->sum('total_pcs'), 2, ',', '.') }}
                            Pcs</h2>
                        <p>Laundry Satuan {{ $tahun }}</p>
                    </div>
                </div>
                <div class="card-body" id="laundrySatuanBody" style="display: none;">
                    <!-- Isi tambahan untuk laundry satuan jika ada -->
                    <p class="text-muted">Detail laundry satuan belum dimasukkan.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Grafik Laundry Bulanan</h4>
                    <span>{{ \Carbon\Carbon::createFromDate($tahun)->translatedFormat('Y') }}</span>
                </div>
                <div class="card-content">
                    <div class="card-body pb-0">
                        <div style="overflow-x: auto;">
                            <div id="grafik-kg-pcs-bulanan" style="min-width: 1500px;"></div>
                        </div>
                    </div>
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
                                    <th>Tahun</th>
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
                                        <td>{{ $item->tahun }}</td>
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
                                    <th>Tahun</th>
                                    <th>Total Pcs</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporanSatuanCustomer as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->customers->name ?? 'Tidak Diketahui' }}</td>
                                        <td>{{ $item->tahun }}</td>
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
                                    <th>Tahun</th>
                                    <th>Total Kg</th>
                                    <th>Total Lembar Baju</th>
                                </tr>
                            </thead>
                            <tbody id="refresh_body">
                                @foreach ($laporanKaryawanReguler as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->karyawan->name ?? 'Tidak Diketahui' }}</td>
                                        <td>{{ $item->tahun }}</td>
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
                                    <th>Tahun</th>
                                    <th>Total Pcs</th>
                                </tr>
                            </thead>
                            <tbody id="refresh_body">
                                @foreach ($laporanKaryawanSatuan as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->karyawan->name ?? 'Tidak Diketahui' }}</td>
                                        <td>{{ $item->tahun }}</td>
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

    @php
        $namaBulan = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];
        $bulanList = range(1, 12);

        $jenisKgBulan = $kgPerBulanPerJenis->groupBy('jenis_grouped');
        $jenisPcsBulan = $pcsPerBulanPerJenis->groupBy('jenis_grouped');

        $_kg_series_bulanan = [];
        foreach ($jenisKgBulan as $jenis => $records) {
            $data = [];
            foreach ($bulanList as $bln) {
                $found = $records->firstWhere('bulan', $bln);
                $data[] = $found ? (float) $found->total_kg : 0;
            }
            $_kg_series_bulanan[] = ['name' => $jenis, 'data' => $data];
        }

        $_pcs_series_bulanan = [];
        foreach ($jenisPcsBulan as $jenis => $records) {
            $data = [];
            foreach ($bulanList as $bln) {
                $found = $records->firstWhere('bulan', $bln);
                $data[] = $found ? (int) $found->total_pcs : 0;
            }
            $_pcs_series_bulanan[] = ['name' => $jenis, 'data' => $data];
        }
    @endphp

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
    <script type="text/javascript">
        var $primary = '#7367F0';
        var $label_color = '#e7eef7';
        var $strok_color = '#b9c3cd';

        var bulanList = {!! json_encode($namaBulan) !!};
        var kgSeries = {!! json_encode($_kg_series_bulanan) !!};
        var pcsSeries = {!! json_encode($_pcs_series_bulanan) !!};

        // Gabungkan series KG dan PCS dengan yAxisIndex
        var combinedSeries = [];

        kgSeries.forEach(function(item) {
            combinedSeries.push({
                name: item.name + " (KG)",
                data: item.data,
                yAxisIndex: 0
            });
        });

        pcsSeries.forEach(function(item) {
            combinedSeries.push({
                name: item.name + " (PCS)",
                data: item.data,
                yAxisIndex: 1
            });
        });

        var combinedChartOptions = {
            chart: {
                height: 370,
                type: 'line',
                toolbar: {
                    show: false
                },
                dropShadow: {
                    enabled: true,
                    top: 20,
                    left: 2,
                    blur: 6,
                    opacity: 0.20
                },
            },
            stroke: {
                curve: 'smooth',
                width: 4
            },
            grid: {
                borderColor: $label_color
            },
            colors: ['#7367F0', '#28C76F', '#EA5455', '#FF9F43', '#00cfe8'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    inverseColors: false,
                    gradientToColors: [$primary],
                    shadeIntensity: 1,
                    type: 'horizontal',
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100, 100, 100]
                },
            },
            markers: {
                size: 0,
                hover: {
                    size: 5
                }
            },
            xaxis: {
                categories: bulanList,
                labels: {
                    style: {
                        colors: $strok_color
                    }
                },
                axisTicks: {
                    show: false
                },
                axisBorder: {
                    show: false
                },
                tickPlacement: 'on'
            },
            yaxis: [{
                    title: {
                        text: 'KG'
                    },
                    labels: {
                        style: {
                            color: $strok_color
                        },
                        formatter: function(val) {
                            return val > 999 ? (val / 1000).toFixed(1) + 'k' : val;
                        }
                    }
                },
                {
                    opposite: true,
                    title: {
                        text: 'PCS'
                    },
                    labels: {
                        style: {
                            color: $strok_color
                        },
                        formatter: function(val) {
                            return val > 999 ? (val / 1000).toFixed(1) + 'k' : val;
                        }
                    }
                }
            ],
            tooltip: {
                x: {
                    show: false
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center',
                labels: {
                    colors: $strok_color
                }
            },
            responsive: [{
                breakpoint: 768,
                options: {
                    chart: {
                        height: 300
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            series: combinedSeries
        };

        var combinedChart = new ApexCharts(
            document.querySelector("#grafik-kg-pcs-bulanan"),
            combinedChartOptions
        );

        combinedChart.render();
    </script>
@endsection
