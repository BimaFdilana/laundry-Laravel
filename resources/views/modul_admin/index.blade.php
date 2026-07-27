@extends('layouts.backend')
@section('title', 'Dashboard Admin')
@section('content')
    <!-- Header dengan Filter Waktu Dinamis -->
    <div class="row mb-2">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div>
                <h1 class="h3 text-gray-800 mb-0 font-weight-bold">Ringkasan Operasional & Keuangan</h1>
                <p class="text-muted mb-0">Pantau metrik penting, omzet, dan aktivitas laundry Anda.</p>
            </div>
            <div class="d-flex align-items-center mt-1 mt-md-0">
                <select id="filter-preset" class="form-control mr-1" style="width: 160px; font-weight: bold; border-color: #7367F0; color: #7367F0;">
                    <option value="today">Hari Ini</option>
                    <option value="this_week">Minggu Ini</option>
                    <option value="this_month" selected>Bulan Ini</option>
                    <option value="this_year">Tahun Ini</option>
                    <option value="custom">Kustom Tanggal</option>
                </select>
                <div id="custom-date-container" class="d-none align-items-center">
                    <input type="date" id="start-date" class="form-control mr-50" style="width: 150px;" value="{{ now()->startOfMonth()->toDateString() }}">
                    <span class="mr-50">s.d</span>
                    <input type="date" id="end-date" class="form-control" style="width: 150px;" value="{{ now()->toDateString() }}">
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards dengan Persentase Komparasi -->
    <div class="row">
        <!-- Revenue Card -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0 font-weight-bold small">PENDAPATAN GABUNGAN</p>
                        <h3 class="text-bold-700 my-50 text-success" id="kpi-revenue">Rp 0</h3>
                        <span id="kpi-revenue-change" class="font-weight-bold small"></span>
                    </div>
                    <div class="avatar bg-rgba-success p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-dollar-sign text-success font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Weight Card -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0 font-weight-bold small">TOTAL BERAT LAUNDRY</p>
                        <h3 class="text-bold-700 my-50 text-primary" id="kpi-weight">0 kg</h3>
                        <span id="kpi-weight-change" class="font-weight-bold small"></span>
                    </div>
                    <div class="avatar bg-rgba-primary p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-box text-primary font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Active Orders Card -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0 font-weight-bold small">ORDER AKTIF (PROSES)</p>
                        <h3 class="text-bold-700 my-50 text-warning" id="kpi-active-orders">0</h3>
                        <span class="text-muted small">Cucian sedang diproses</span>
                    </div>
                    <div class="avatar bg-rgba-warning p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-clock text-warning font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Completed Orders Card -->
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-0 font-weight-bold small">ORDER SELESAI</p>
                        <h3 class="text-bold-700 my-50 text-info" id="kpi-completed-orders">0</h3>
                        <span class="text-muted small">Cucian siap diambil</span>
                    </div>
                    <div class="avatar bg-rgba-info p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-check-circle text-info font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Piutang Card -->
    <div class="row">
        <div class="col-12">
            <a href="{{ route('admin.piutang.index') }}" style="text-decoration: none; color: inherit;">
                <div class="card bg-light-secondary border-secondary hover-shadow" style="transition: all 0.2s;">
                    <div class="card-body d-flex justify-content-between align-items-center py-1">
                        <div>
                            <h5 class="text-secondary mb-25 font-weight-bold">Total Piutang Belum Dibayar (Absolut)</h5>
                            <h3 class="text-bold-700 mb-0 text-danger" id="kpi-piutang">Rp 0</h3>
                        </div>
                        <div class="avatar bg-rgba-secondary p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-alert-circle text-secondary font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body py-1">
                    <h5 class="card-title font-weight-bold mb-1"><i class="feather icon-zap text-warning mr-50"></i>Pintasan Cepat (Quick Actions)</h5>
                    <div class="d-flex flex-wrap">
                        <a href="{{ route('transaksi.create') }}" class="btn btn-outline-primary mr-1 mb-1 font-weight-bold">
                            <i class="feather icon-plus-circle mr-50"></i> Tambah Kiloan
                        </a>
                        <a href="{{ route('transaksi-satuan.create') }}" class="btn btn-outline-success mr-1 mb-1 font-weight-bold">
                            <i class="feather icon-plus-circle mr-50"></i> Tambah Satuan
                        </a>
                        <a href="{{ route('kuota.create') }}" class="btn btn-outline-info mr-1 mb-1 font-weight-bold">
                            <i class="feather icon-plus-circle mr-50"></i> Beli Kuota Paket
                        </a>
                        <a href="{{ route('admin.aktivitas-karyawan.index') }}" class="btn btn-outline-warning mb-1 font-weight-bold">
                            <i class="feather icon-user-check mr-50"></i> Aktivitas Karyawan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-lg-8 col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header pb-0">
                    <h4 class="card-title font-weight-bold">Grafik Tren Pendapatan</h4>
                </div>
                <div class="card-body">
                    <div id="chart-revenue-trend"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12 d-flex">
            <div class="card shadow-sm border-0 w-100">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title font-weight-bold">Distribusi & Target</h4>
                    <select id="target-period-select" class="form-control" style="width: 120px; height: 32px; font-size: 12px; padding: 2px 8px; border-color: #7367F0; color: #7367F0; font-weight: bold;">
                        <option value="this_week">Mingguan</option>
                        <option value="this_month" selected>Bulanan</option>
                        <option value="this_year">Tahunan</option>
                    </select>
                </div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div id="chart-revenue-distribution"></div>
                    </div>
                    <hr class="my-1">
                    <div class="text-left mt-1">
                        <!-- Target Keuangan -->
                        <div class="mb-1">
                            <h6 class="font-weight-bold mb-25 target-header"><i class="feather icon-dollar-sign mr-25"></i>Target Keuangan (<span class="target-period-label">Bulan Ini</span>)</h6>
                            <div class="d-flex justify-content-between mb-25 small font-weight-bold target-subtext">
                                <span>Target: <span id="target-finance-val">Rp 0</span></span>
                                <span>Tercapai: <span id="achieved-finance-val">Rp 0</span></span>
                            </div>
                            <div class="progress" style="height: 14px; border-radius: 7px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success font-weight-bold text-dark" role="progressbar" id="finance-progress-bar" style="width: 0%; font-size: 9px; line-height: 14px; color: #000 !important;">0%</div>
                            </div>
                        </div>
                        <!-- Target Laundry -->
                        <div>
                            <h6 class="font-weight-bold mb-25 target-header"><i class="feather icon-box mr-25"></i>Target Laundry (<span class="target-period-label">Bulan Ini</span>)</h6>
                            <div class="d-flex justify-content-between mb-25 small font-weight-bold target-subtext">
                                <span>Target: <span id="target-laundry-val">0 kg</span></span>
                                <span>Tercapai: <span id="achieved-laundry-val">0 kg</span></span>
                            </div>
                            <div class="progress" style="height: 14px; border-radius: 7px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-info font-weight-bold text-dark" role="progressbar" id="laundry-progress-bar" style="width: 0%; font-size: 9px; line-height: 14px; color: #000 !important;">0%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Feeds Row -->
    <div class="row">
        <!-- 5 Order Terbaru -->
        <div class="col-md-6 col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h4 class="card-title font-weight-bold">5 Order Terbaru (Kiloan & Satuan)</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="min-height: 250px;">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Invoice</th>
                                    <th>Pelanggan</th>
                                    <th>Layanan</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="live-orders-body">
                                <tr>
                                    <td colspan="4" class="text-center">Memuat order...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- 5 Pengeluaran Terbaru -->
        <div class="col-md-6 col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h4 class="card-title font-weight-bold">5 Pengeluaran Terbaru (Beban Operasional)</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="min-height: 250px;">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Pengeluaran</th>
                                    <th>Kategori</th>
                                    <th>Total</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody id="live-expenses-body">
                                <tr>
                                    <td colspan="4" class="text-center">Memuat pengeluaran...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Load MomentJS untuk presets range tanggal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Set preset awal (bulan ini)
            setPresetDates('this_month');
            fetchDashboardData();

            $('#filter-preset').change(function() {
                var preset = $(this).val();
                if (preset === 'custom') {
                    $('#custom-date-container').removeClass('d-none').addClass('d-flex');
                } else {
                    $('#custom-date-container').removeClass('d-flex').addClass('d-none');
                    setPresetDates(preset);
                    fetchDashboardData();
                }
            });

            $('#start-date, #end-date').change(function() {
                fetchDashboardData();
            });

            $('#target-period-select').change(function() {
                fetchDashboardData();
            });
        });

        function setPresetDates(preset) {
            var start = '';
            var end = moment().format('YYYY-MM-DD');

            if (preset === 'today') {
                start = moment().format('YYYY-MM-DD');
            } else if (preset === 'this_week') {
                start = moment().startOf('week').format('YYYY-MM-DD');
            } else if (preset === 'this_month') {
                start = moment().startOf('month').format('YYYY-MM-DD');
            } else if (preset === 'this_year') {
                start = moment().startOf('year').format('YYYY-MM-DD');
            }

            $('#start-date').val(start);
            $('#end-date').val(end);
        }

        var trendChart = null;
        var distributionChart = null;

        function fetchDashboardData() {
            var startDate = $('#start-date').val();
            var endDate = $('#end-date').val();
            var targetPeriod = $('#target-period-select').val();

            $.get('{{ route("dashboard.data") }}', { start_date: startDate, end_date: endDate, target_period: targetPeriod }, function(res) {
                // Update KPI Cards
                $('#kpi-revenue').text(res.revenue);
                $('#kpi-weight').text(res.weight);
                $('#kpi-active-orders').text(res.active_orders);
                $('#kpi-completed-orders').text(res.completed_orders);
                $('#kpi-piutang').text(res.total_piutang);

                // Update Revenue & Weight Change status
                updateChangeBadge('#kpi-revenue-change', res.revenue_change);
                updateChangeBadge('#kpi-weight-change', res.weight_change);

                // Update Progress Target Dinamis
                $('#target-laundry-val').text(res.target_laundry_kg);
                $('#achieved-laundry-val').text(res.achieved_laundry_kg);
                $('#laundry-progress-bar').css('width', res.percent_laundry + '%').text(res.percent_laundry + '%');

                $('#target-finance-val').text(res.target_finance_rp);
                $('#achieved-finance-val').text(res.achieved_finance_rp);
                $('#finance-progress-bar').css('width', res.percent_finance + '%').text(res.percent_finance + '%');

                // Label periode target & warna text
                var targetPreset = $('#target-period-select').val();
                var label = 'Bulan Ini';
                var color = '#000000'; // Default black

                if (targetPreset === 'this_week') {
                    label = 'Minggu Ini';
                    color = '#7367F0'; // Purple / Blue
                } else if (targetPreset === 'this_month') {
                    label = 'Bulan Ini';
                    color = '#28C76F'; // Success Green
                } else if (targetPreset === 'this_year') {
                    label = 'Tahun Ini';
                    color = '#EA5455'; // Danger Red
                }

                $('.target-period-label').text(label);
                $('.target-header, .target-subtext').css('color', color);

                // Render Grafik Tren Pendapatan
                renderTrendChart(res.chart.labels, res.chart.series);
                
                // Render Grafik Donut Distribusi
                renderDistributionChart(res.chart.distribution);

                // Render Live Feeds
                renderLiveFeeds(res.recent_orders, res.recent_expenses);
            });
        }

        function updateChangeBadge(selector, change) {
            var $elem = $(selector);
            if (change > 0) {
                $elem.removeClass('text-danger text-muted').addClass('text-success').html(`<i class="feather icon-arrow-up"></i> +${change}% dibanding periode sebelumnya`);
            } else if (change < 0) {
                $elem.removeClass('text-success text-muted').addClass('text-danger').html(`<i class="feather icon-arrow-down"></i> ${change}% dibanding periode sebelumnya`);
            } else {
                $elem.removeClass('text-success text-danger').addClass('text-muted').html(`<i class="feather icon-minus"></i> 0% dibanding periode sebelumnya`);
            }
        }

        function renderTrendChart(labels, series) {
            var options = {
                chart: {
                    height: 300,
                    type: 'area',
                    toolbar: { show: false }
                },
                stroke: { curve: 'smooth', width: 3 },
                colors: ['#7367F0', '#FF9F43', '#28C76F'],
                dataLabels: { enabled: false },
                xaxis: { categories: labels },
                series: series,
                tooltip: { y: { formatter: function(val) { return 'Rp ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); } } }
            };

            if (trendChart) {
                trendChart.destroy();
            }
            trendChart = new ApexCharts(document.querySelector("#chart-revenue-trend"), options);
            trendChart.render();
        }

        function renderDistributionChart(distribution) {
            var options = {
                chart: {
                    type: 'donut',
                    height: 250
                },
                series: distribution,
                labels: ['Laundry Kiloan', 'Laundry Satuan', 'Penjualan Kuota'],
                colors: ['#7367F0', '#FF9F43', '#28C76F'],
                legend: { position: 'bottom' },
                tooltip: { y: { formatter: function(val) { return 'Rp ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); } } }
            };

            if (distributionChart) {
                distributionChart.destroy();
            }
            distributionChart = new ApexCharts(document.querySelector("#chart-revenue-distribution"), options);
            distributionChart.render();
        }

        function renderLiveFeeds(orders, expenses) {
            var ordersHtml = '';
            if (orders.length === 0) {
                ordersHtml = '<tr><td colspan="4" class="text-center">Tidak ada transaksi.</td></tr>';
            } else {
                orders.forEach(function(item) {
                    var badge = item.status_payment === 'Success' ? 'badge-success' : 'badge-danger';
                    ordersHtml += `<tr>
                        <td><a href="${item.url}" target="_blank" class="text-bold-600">${item.invoice}</a></td>
                        <td>${item.customer}</td>
                        <td>${item.layanan}</td>
                        <td>${item.total} <span class="badge ${badge} ml-25">${item.status_payment}</span></td>
                    </tr>`;
                });
            }
            $('#live-orders-body').html(ordersHtml);

            var expensesHtml = '';
            if (expenses.length === 0) {
                expensesHtml = '<tr><td colspan="4" class="text-center">Tidak ada pengeluaran.</td></tr>';
            } else {
                expenses.forEach(function(item) {
                    expensesHtml += `<tr>
                        <td><strong>${item.pengeluaran}</strong></td>
                        <td>${item.kategori}</td>
                        <td class="text-danger font-weight-bold">${item.total}</td>
                        <td>${item.tanggal}</td>
                    </tr>`;
                });
            }
            $('#live-expenses-body').html(expensesHtml);
        }
    </script>
@endsection
