@extends('layouts.backend')
@section('title', 'Admin - Data Customer')
@section('header', 'Data Customer')

@section('styles')
    <!-- Tidak ada Leaflet CSS karena fitur lokasi dihapus -->
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @elseif($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <!-- Tabel Kuota Laundry Tanpa Pengelompokan Kategori -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Kuota Laundry Customer
                        <a href="{{ route('kuota.create') }}" class="btn btn-primary">Tambah</a>
                    </h4>
                    <div class="table-responsive">
                        <table id="myTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Customer</th>
                                    <th>Kategori</th>
                                    <th>Kuota</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($flatKuota as $index => $data)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $data['customer']->name }}</td>
                                        <td>{{ $data['kuota']->kategori }}</td>
                                        <td>{{ $data['kuota']->kuota }} kg</td>
                                        <td>
                                            <a href="{{ route('kuota.edit', $data['kuota']->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <button class="btn btn-sm btn-info btn-detail-history" 
                                                data-user-id="{{ $data['customer']->id }}" 
                                                data-customer-name="{{ $data['customer']->name }}">Detail</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No data available in table</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Penggunaan Kuota -->
    <div class="modal fade" id="modalDetailKuota" tabindex="-1" role="dialog" aria-labelledby="modalDetailKuotaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailKuotaLabel">Riwayat Penggunaan Kuota - <span id="detail-customer-name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="table-history-kuota">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Tipe</th>
                                    <th>Jumlah</th>
                                    <th>Kategori</th>
                                    <th>Keterangan</th>
                                    <th>Invoice</th>
                                </tr>
                            </thead>
                            <tbody id="history-kuota-body">
                                <!-- Data akan diisi via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#myTable').DataTable();
        });

        // Handler tombol Detail Penggunaan Kuota
        $(document).on('click', '.btn-detail-history', function() {
            var userId = $(this).data('user-id');
            var customerName = $(this).data('customer-name');
            $('#detail-customer-name').text(customerName);
            
            // Tampilkan loading di modal body
            $('#history-kuota-body').html('<tr><td colspan="6" class="text-center">Memuat data...</td></tr>');
            $('#modalDetailKuota').modal('show');
            
            // AJAX request ke history route
            $.get('/kuota/' + userId + '/history', function(data) {
                var html = '';
                if (data.length === 0) {
                    html = '<tr><td colspan="6" class="text-center">Tidak ada riwayat penggunaan kuota.</td></tr>';
                } else {
                    data.forEach(function(log) {
                        var badgeClass = log.tipe === 'Penambahan' ? 'badge-success' : 'badge-danger';
                        var invoiceCell = '-';
                        if (log.invoice) {
                            invoiceCell = `<a href="${log.invoice_url}" class="btn btn-sm btn-success" target="_blank">${log.invoice}</a>`;
                        }
                        
                        html += `<tr>
                            <td>${log.waktu}</td>
                            <td><span class="badge ${badgeClass}">${log.tipe}</span></td>
                            <td style="font-weight: bold; color: ${log.tipe === 'Penambahan' ? '#28a745' : '#dc3545'};">
                                ${log.tipe === 'Penambahan' ? '+' : '-'}${log.jumlah}
                            </td>
                            <td>${log.kategori}</td>
                            <td>${log.keterangan || '-'}</td>
                            <td>${invoiceCell}</td>
                        </tr>`;
                    });
                }
                $('#history-kuota-body').html(html);
            }).fail(function() {
                $('#history-kuota-body').html('<tr><td colspan="6" class="text-center text-danger">Gagal memuat data. Silakan coba lagi.</td></tr>');
            });
        });
    </script>
@endsection
