@extends('layouts.backend')
@section('title', 'Dashboard Admin')
@section('content')
    <div class="row">
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-header d-flex align-items-start pb-0">
                    <div>
                        <h2 class="text-bold-700 mb-0">{{ $customer->count() }}</h2>
                        <p>Jumlah Customer</p>
                    </div>
                    <div class="avatar bg-rgba-primary p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-users text-primary font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-header d-flex align-items-start pb-0">
                    <div>
                        <h2 class="text-bold-700 mb-0">{{ $masuk }}</h2>
                        <p>Laundry Masuk</p>
                    </div>
                    <div class="avatar bg-rgba-success p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-box text-success font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-header d-flex align-items-start pb-0">
                    <div>
                        <h2 class="text-bold-700 mb-0">{{ $selesai }}</h2>
                        <p>Laundry Selesai</p>
                    </div>
                    <div class="avatar bg-rgba-danger p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-check text-danger font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card">
                <div class="card-header d-flex align-items-start pb-0">
                    <div>
                        <h2 class="text-bold-700 mb-0">{{ $diambil }}</h2>
                        <p>Laundry Diambil</p>
                    </div>
                    <div class="avatar bg-rgba-warning p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-check-square text-warning font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Target Laundry --}}
    <div class="row">
        <div class="col-12">
            <div class="card bg-light-primary">
                <div class="card-header">
                    <h4 class="card-title">Target Laundry (Kg)</h4>
                </div>
                <div class="card-body row">
                    {{-- Target Harian --}}
                    <div class="col-md-4 mb-2">
                        <div class="text-bold-600">Target Harian</div>
                        <div class="text-danger">{{ $targetHari }} kg</div>
                        <small class="text-muted">
                            Tercapai: {{ number_format($kgHariIni, 2) }} kg
                            ({{ $targetHari > 0 ? number_format(($kgHariIni / $targetHari) * 100, 2) : 0 }}%)
                        </small>
                        <div class="progress mt-1" style="height: 10px;">
                            <div class="progress-bar bg-danger" role="progressbar"
                                style="width: {{ $targetHari > 0 ? ($kgHariIni / $targetHari) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    {{-- Target Bulanan --}}
                    <div class="col-md-4 mb-2">
                        <div class="text-bold-600">Target Bulanan</div>
                        <div class="text-warning">{{ $targetBulan }} kg</div>
                        <small class="text-muted">
                            Tercapai: {{ number_format($kgBulanIni, 2) }} kg
                            ({{ $targetBulan > 0 ? number_format(($kgBulanIni / $targetBulan) * 100, 2) : 0 }}%)
                        </small>
                        <div class="progress mt-1" style="height: 10px;">
                            <div class="progress-bar bg-warning" role="progressbar"
                                style="width: {{ $targetBulan > 0 ? ($kgBulanIni / $targetBulan) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    {{-- Target Tahunan --}}
                    <div class="col-md-4 mb-2">
                        <div class="text-bold-600">Target Tahunan</div>
                        <div class="text-primary">{{ $targetTahun }} kg</div>
                        <small class="text-muted">
                            Tercapai: {{ number_format($kgTahunIni, 2) }} kg
                            ({{ $targetTahun > 0 ? number_format(($kgTahunIni / $targetTahun) * 100, 2) : 0 }}%)
                        </small>
                        <div class="progress mt-1" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar"
                                style="width: {{ $targetTahun > 0 ? ($kgTahunIni / $targetTahun) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 col-xl-7 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Data Per-hari</h4>
                </div>
                <div class="card-content">
                    <div class="card-body pb-0">
                        <div id="data-hari"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-xl-5 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Data Per-bulan</h4>
                </div>
                <div class="card-content">
                    <div class="card-body pb-0">
                        <div id="data-bulan"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var $primary = '#7367F0';
        var $label_color = '#e7eef7';
        var $purple = '#df87f2';
        var $strok_color = '#b9c3cd';

        // Bar data bulan
        var salesavgChartoptions = {
            chart: {
                height: 270,
                toolbar: {
                    show: false
                },
                type: 'line',
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
                width: 4,
            },
            grid: {
                borderColor: $label_color,
            },
            legend: {
                show: false,
            },
            colors: [$purple],
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
                labels: {
                    style: {
                        colors: $strok_color,
                    }
                },
                axisTicks: {
                    show: false,
                },
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Juni', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                axisBorder: {
                    show: false,
                },
                tickPlacement: 'on'
            },
            yaxis: {
                tickAmount: 5,
                labels: {
                    style: {
                        color: $strok_color,
                    },
                    formatter: function(val) {
                        return val > 999 ? (val / 1000).toFixed(1) + 'k' : val;
                    }
                }
            },
            tooltip: {
                x: {
                    show: false
                }
            },
            series: [{
                name: "Laundry Masuk",
                data: [{{ $jan }}, {{ $feb }}, {{ $mar }}, {{ $apr }},
                    {{ $mey }}, {{ $juni }}, {{ $july }}, {{ $aug }},
                    {{ $sep }}, {{ $oct }}, {{ $nov }}, {{ $dec }}
                ]
            }],

        }

        var salesavgChart = new ApexCharts(
            document.querySelector("#data-bulan"),
            salesavgChartoptions
        );

        salesavgChart.render();
        // End Bar bulan

        // Bar Data Hari
        var salesavgChartoptions = {
            chart: {
                height: 270,
                toolbar: {
                    show: false
                },
                type: 'line',
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
                width: 4,
            },
            grid: {
                borderColor: $label_color,
            },
            legend: {
                show: false,
            },
            colors: [$purple],
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
                labels: {
                    style: {
                        colors: $strok_color,
                    }
                },
                axisTicks: {
                    show: false,
                },
                categories: [{{ $_tanggal }}],
                axisBorder: {
                    show: false,
                },
                tickPlacement: 'on'
            },
            yaxis: {
                tickAmount: 5,
                labels: {
                    style: {
                        color: $strok_color,
                    },
                    formatter: function(val) {
                        return val > 999 ? (val / 1000).toFixed(1) + 'k' : val;
                    }
                }
            },
            tooltip: {
                x: {
                    show: false
                }
            },
            series: [{
                name: "Laundry Masuk",
                data: [{{ $_nilai }}],
            }],

        }

        var salesavgChart = new ApexCharts(
            document.querySelector("#data-hari"),
            salesavgChartoptions
        );

        salesavgChart.render();
        // End Bar
    </script>
@endsection
