@extends('layouts.backend')
@section('title', 'Dashboard Super Admin')
@section('styles')
    <style>
        .hover-shadow:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('kelola-admin.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card hover-shadow">
                    <div class="card-header d-flex align-items-start pb-0">
                        <div>
                            <h2 class="text-bold-700 mb-0">{{ $jumlahAdmin }}</h2>
                            <p>Jumlah Admin</p>
                        </div>
                        <div class="avatar bg-rgba-primary p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-users text-primary font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('karyawan.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card hover-shadow">
                    <div class="card-header d-flex align-items-start pb-0">
                        <div>
                            <h2 class="text-bold-700 mb-0">{{ $jumlahKaryawan }}</h2>
                            <p>Jumlah Karyawan</p>
                        </div>
                        <div class="avatar bg-rgba-warning p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-users text-warning font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
            <a href="{{ route('supercustomer.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card hover-shadow">
                    <div class="card-header d-flex align-items-start pb-0">
                        <div>
                            <h2 class="text-bold-700 mb-0">{{ $jumlahCustomer }}</h2>
                            <p>Jumlah Customer</p>
                        </div>
                        <div class="avatar bg-rgba-success p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-users text-success font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <a href="{{ route('superadmin.piutang.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card hover-shadow bg-light-danger">
                    <div class="card-header d-flex align-items-start pb-0">
                        <div>
                            <h2 class="text-bold-700 mb-0 text-danger">Rp. {{ number_format($totalPiutang, 0, ',', '.') }}</h2>
                            <p>Total Piutang Belum Dibayar</p>
                        </div>
                        <div class="avatar bg-rgba-danger p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-alert-circle text-danger font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Target Laundry & Pemasukan --}}
    <div class="row">
        <div class="col-lg-6 col-12 d-flex">
            <div class="card bg-light-primary w-100 h-80">
                <div class="card-header">
                    <h4 class="card-title">Target Laundry (Kg)</h4>
                </div>
                <div class="card-body row">
                    {{-- Target Harian --}}
                    <div class="col-12 mb-2">
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
                    <div class="col-12 mb-2">
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
                    <div class="col-12 mb-2">
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
        <div class="col-lg-6 col-12 d-flex">
            <div class="card w-100 h-80">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Pemasukan</h4>
                    <h4 class="card-title">
                        <span>{{ Rupiah::getRupiah($totalPemasukan) }}</span>
                    </h4>
                </div>
                <div class="card-content">
                    <div class="card-body pt-50">
                        <div id="product-order-chart" class="mb-2"></div>
                        <div class="chart-info d-flex justify-content-between mb-1">
                            <div class="series-info d-flex align-items-center">
                                <i class="fa fa-circle-o text-bold-700 text-primary"></i>
                                <span class="text-bold-600 ml-50">Tahun Ini</span>
                            </div>
                            <div class="product-result">
                                <span>{{ Rupiah::getRupiah($tahun) }}</span>
                            </div>
                        </div>

                        <div class="chart-info d-flex justify-content-between mb-1">
                            <div class="series-info d-flex align-items-center">
                                <i class="fa fa-circle-o text-bold-700 text-warning"></i>
                                <span class="text-bold-600 ml-50">Bulan Ini</span>
                            </div>
                            <div class="product-result">
                                <span>{{ Rupiah::getRupiah($bulan) }}</span>
                            </div>
                        </div>

                        <div class="chart-info d-flex justify-content-between mb-25">
                            <div class="series-info d-flex align-items-center">
                                <i class="fa fa-circle-o text-bold-700 text-danger"></i>
                                <span class="text-bold-600 ml-50">Hari Ini</span>
                            </div>
                            <div class="product-result">
                                <span>{{ Rupiah::getRupiah($hari) }}</span>
                            </div>
                        </div>

                        <div class="chart-info d-flex justify-content-between mb-25">
                            <div class="series-info d-flex align-items-center">
                                <i class="fa fa-circle-o text-bold-700 text-success"></i>
                                <span class="text-bold-600 ml-50">Sampai Saat Ini</span>
                            </div>
                            <div class="product-result">
                                <span>{{ Rupiah::getRupiah($totalPemasukan) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Pendapatan Per-hari</h4>
                    <span>{{ \Carbon\Carbon::now()->format('F Y') }}</span>
                </div>
                <div class="card-content">
                    <div class="card-body pb-0">
                        <div style="overflow-x: auto;">
                            <div id="data-hari" style="min-width: 1000px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Pendapatan Per-bulan</h4>
                    <span>{{ \Carbon\Carbon::now()->format('Y') }}</span>
                </div>
                <div class="card-content">
                    <div style="overflow-x: auto;">
                        <div id="data-bulan" style="min-width: 1000px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Pendapatan Per-tahun</h4>
                    <span>{{ $startYear }} - {{ $currentYear }}</span>
                </div>
                <div class="card-content">
                    <div class="card-body pb-0">
                        <div style="overflow-x: auto;">
                            {{-- Misalnya hitung jumlah tahun --}}
                            @php
                                $jumlahTahun = $currentYear - $startYear + 1;
                                $lebarGrafik = $jumlahTahun * 100; // 100px per tahun (misal)
                            @endphp
                            <div id="data-tahun" style="min-width: {{ $lebarGrafik }}px;"></div>
                        </div>
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

        // Grafik Pendapatan Harian
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
            colors: ['#df87f2', '#7367F0', '#28C76F', '#EA5455'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    inverseColors: false,
                    gradientToColors: ['#df87f2', '#7367F0', '#28C76F', '#EA5455'],
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
                name: "Reguler",
                data: [{{ $_nilai_reg }}],
            }, {
                name: "Satuan",
                data: [{{ $_nilai_satuan }}],
            }, {
                name: "Paket Laundry (Kuota)",
                data: [{{ $_nilai_pem_kuota }}],
            }, {
                name: "Pemasukan Lain",
                data: [{{ $_nilai_pem_nonkuota }}],
            }],
        }

        var salesavgChart = new ApexCharts(
            document.querySelector("#data-hari"),
            salesavgChartoptions
        );

        salesavgChart.render();

        // Grafik Pendapatan Bulanan
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
            colors: ['#df87f2', '#7367F0', '#28C76F', '#EA5455'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    inverseColors: false,
                    gradientToColors: ['#df87f2', '#7367F0', '#28C76F', '#EA5455'],
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
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
                name: "Reguler",
                data: [{{ implode(',', $bulananReg) }}]
            }, {
                name: "Satuan",
                data: [{{ implode(',', $bulananSat) }}]
            }, {
                name: "Paket Laundry (Kuota)",
                data: [{{ implode(',', $bulananPemKuota) }}]
            }, {
                name: "Pemasukan Lain",
                data: [{{ implode(',', $bulananPem) }}]
            }],
        }

        var salesavgChart = new ApexCharts(
            document.querySelector("#data-bulan"),
            salesavgChartoptions
        );

        salesavgChart.render();

        // Grafik Pendapatan Tahunan
        var tahunanChart = new ApexCharts(
            document.querySelector("#data-tahun"), {
                chart: {
                    height: 300,
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
                colors: ['#df87f2', '#7367F0', '#28C76F', '#EA5455'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        inverseColors: false,
                        gradientToColors: ['#df87f2', '#7367F0', '#28C76F', '#EA5455'],
                        shadeIntensity: 1,
                        type: 'horizontal',
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100, 100, 100]
                    },
                },
                xaxis: {
                    categories: {!! json_encode(range($startYear, $currentYear)) !!},
                    labels: {
                        style: {
                            colors: $strok_color,
                        }
                    }
                },
                yaxis: {
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
                        show: true
                    }
                },
                series: [{
                    name: "Reguler",
                    data: {!! json_encode($tahunanReg) !!}
                }, {
                    name: "Satuan",
                    data: {!! json_encode($tahunanSat) !!}
                }, {
                    name: "Paket Laundry (Kuota)",
                    data: {!! json_encode($tahunanPemKuota) !!}
                }, {
                    name: "Pemasukan Lain",
                    data: {!! json_encode($tahunanPem) !!}
                }]
            }
        );
        tahunanChart.render();
    </script>
    <script type="text/javascript">
        var $primary = '#7367F0';
        var $danger = '#EA5455';
        var $warning = '#FF9F43';
        var $primary_light = '#9c8cfc';
        var $warning_light = '#FFC085';
        var $danger_light = '#f29292';

        // Data Finance
        var orderChartoptions = {
            chart: {
                height: 325,
                type: 'radialBar',
            },
            colors: [$primary, $warning, $danger],
            fill: {
                type: 'gradient',
                gradient: {
                    enabled: true,
                    shade: 'dark',
                    type: 'vertical',
                    shadeIntensity: 0.5,
                    gradientToColors: [$primary_light, $warning_light, $danger_light],
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100]
                },
            },
            stroke: {
                lineCap: 'round'
            },
            plotOptions: {
                radialBar: {
                    size: 150,
                    hollow: {
                        size: '20%'
                    },
                    track: {
                        strokeWidth: '100%',
                        margin: 15,
                    },
                    dataLabels: {
                        name: {
                            fontSize: '12px',
                        },
                        value: {
                            fontSize: '16px',
                        },
                        total: {
                            show: true,
                            label: 'Total Transaksi',

                            formatter: function(w) {
                                return [{{ $transaksi->count() }}]
                            }
                        }
                    }
                }
            },
            series: [{{ $ny }}, {{ $nm }}, {{ $nd }}],
            labels: ['Tahun Ini', 'Bulan Ini', 'Hari Ini'],
        }

        var orderChart = new ApexCharts(
            document.querySelector("#product-order-chart"),
            orderChartoptions
        );

        orderChart.render();
        // End Data Finance
    </script>
@endsection
