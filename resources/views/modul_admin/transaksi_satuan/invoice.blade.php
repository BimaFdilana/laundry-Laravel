<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $dataInvoice->invoice }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            font-size: 14px;
            line-height: 1.2;
        }

        h3,
        h4,
        h5,
        h6 {
            font-size: 18px;
            margin-bottom: 4px;
        }

        .table td,
        .table th {
            padding: 4px 6px;
            /* perkecil padding cell tabel */
            font-size: 12px;
        }

        .invoice-box {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        h3 span {
            float: right;
        }

        .table thead {
            background-color: #343a40;
            color: white;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .signature-box {
            margin-top: 30px;
            font-size: 14px;
        }

        .signature-box p {
            text-align: justify;
        }

        .signature-box ol {
            padding-left: 20px;
            margin-top: 5px;
        }

        .signature-box ol li {
            margin-left: -5px;
            margin-bottom: 6px;
            text-align: justify;
        }

        .btn-download {
            background-color: #28a745;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 5px;
            transition: all 0.3s ease;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-download:hover {
            background-color: #218838;
            color: white;
        }

        @media print {
            .btn-download {
                visibility: hidden;
                height: 0;
            }
        }
    </style>
</head>

<body>

    <div class="invoice-box">
        <div class="mb-2" style="text-align: right;">
            <img src="{{ asset('frontend/img/logo.jpeg') }}" alt="Logo" style="height: 55px;">
        </div>

        <h3>
            <b>INVOICE</b>
            <span>{{ $dataInvoice->invoice }}</span>
        </h3>
        <hr>

        <div class="row">
            <div class="col-md-6">
                <h5>Informasi Pelanggan</h5>
                <div class="info-section">
                    <p><strong>Nama:</strong> {{ optional($dataInvoice->customers)->name ?? '-' }}</p>
                    <p><strong>Alamat:</strong> {{ optional($dataInvoice->customers)->alamat ?? '-' }}</p>
                    <p><strong>No. Telp:</strong>
                        {{ optional($dataInvoice->customers)->no_telp == 0 ? '-' : optional($dataInvoice->customers)->no_telp ?? '-' }}
                    </p>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <h5>Detail Order</h5>
                <div class="info-section">
                    <p><strong>Tanggal Masuk:</strong>
                        {{ \Carbon\Carbon::parse($dataInvoice->tgl_transaksi)->format('d F Y') }}</p>
                    <p><strong>Tanggal Diambil:</strong>
                        @if (empty($dataInvoice->tgl_ambil))
                            <em>Belum Diambil</em>
                        @else
                            {{ \Carbon\Carbon::parse($dataInvoice->tgl_ambil)->format('d F Y') }}
                        @endif
                    </p>
                    <p><strong>Ket Delivery:</strong> {{ $dataInvoice->ket_delivery ?? '-' }}</p>
                </div>
            </div>

            <p>Jenis Pewangi: {{ $dataInvoice->jenis_pewangi ?? '-' }}</p>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jenis Layanan</th>
                        <th>Nama Barang</th>
                        <th>Pcs</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($dataInvoice->details as $key => $item)
                        @php $total += $item->subtotal; @endphp
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->satuan->jenis }}</td>
                            <td>{{ $item->satuan->nama }}</td>
                            <td>{{ $item->pcs }} pcs</td>
                            <td>{{ Rupiah::getRupiah($item->harga) }}</td>
                            <td>{{ Rupiah::getRupiah($item->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="signature-box">
                    @if ($dataInvoice->status_order == 'Antrian')
                        <h6><strong>Order Antrian</strong></h6>
                        <p>Segera Hubungi dan Konfirmasi ke admin jika:</p>
                        <ol>
                            <li>Ada perbedaan jumlah pakaian hasil hitungan petugas laundry kami</li>
                            <li>Ada pakaian luntur yang harus dipisahkan</li>
                            <li>Ada kondisi pakaian terdapat noda dan rusak</li>
                            <li>Terdapat benda berharga/uang yang tertinggal didalam pakaian</li>
                        </ol>
                    @elseif($dataInvoice->status_order == 'Process')
                        <h6><strong>Order Dalam Proses</strong></h6>
                        <p>Segera Hubungi dan Konfirmasi ke admin jika:</p>
                        <ol>
                            <li>Ada perbedaan jumlah pakaian hasil hitungan petugas laundry kami</li>
                            <li>Ada pakaian luntur yang harus dipisahkan</li>
                            <li>Ada kondisi pakaian terdapat noda dan rusak</li>
                            <li>Terdapat benda berharga/uang yang tertinggal didalam pakaian</li>
                        </ol>
                    @elseif($dataInvoice->status_order == 'Done')
                        <h6><strong>Order Selesai</strong></h6>
                        <ol>
                            <li>Terimakasih telah berlangganan di Laundry Camp, kami telah berusaha memberikan pelayanan
                                terbaik kepada seluruh pelanggan. Jika ada yang kurang memuaskan mohon hubungi kami
                                untuk
                                evaluasi dan peningkatan pelayanan Kami kedepan.</li>
                            <li>Kehilangan/kerusakan pakaian yang tidak diambil lebih dari 2 (dua) minggu tidak menjadi
                                tanggung jawab Laundry Camp.</li>
                        </ol>
                        <h6><strong>SERVE WITH LOVE ❤️</strong></h6>
                        <div class="mt-4">
                            <img src="{{ asset('frontend/img/cap.png') }}" alt="Logo" style="height: 200px;">
                        </div>
                    @elseif($dataInvoice->status_order == 'Delivery')
                        <h6><strong>Order Delivery</strong></h6>
                        <ol>
                            <li>Terimakasih telah berlangganan di Laundry Camp, kami telah berusaha memberikan pelayanan
                                terbaik kepada seluruh pelanggan. Jika ada yang kurang memuaskan mohon hubungi kami
                                untuk
                                evaluasi dan peningkatan pelayanan Kami kedepan.</li>
                            <li>Kehilangan/kerusakan pakaian yang tidak diambil lebih dari 2 (dua) minggu tidak menjadi
                                tanggung jawab Laundry Camp.</li>
                        </ol>
                        <h6><strong>SERVE WITH LOVE ❤️</strong></h6>
                        <div class="mt-4">
                            <img src="{{ asset('frontend/img/cap.png') }}" alt="Logo" style="height: 200px;">
                        </div>
                    @else
                        <p>Segera Hubungi dan Konfirmasi ke admin jika:</p>
                        <ol>
                            <li>Ada perbedaan jumlah pakaian hasil hitungan petugas laundry kami</li>
                            <li>Ada pakaian luntur yang harus dipisahkan</li>
                            <li>Ada kondisi pakaian terdapat noda dan rusak</li>
                            <li>Terdapat benda berharga/uang yang tertinggal didalam pakaian</li>
                        </ol>
                    @endif
                </div>
            </div>
            <div class="col-md-6 text-end">
                <p><strong>Total:</strong> {{ Rupiah::getRupiah($total) }}</p>
                <p><strong>Disc:</strong> {{ Rupiah::getRupiah($dataInvoice->disc) }}</p>
                <hr>
                <h4><strong>Total Bayar:</strong> {{ Rupiah::getRupiah($dataInvoice->harga_akhir) }}</h4>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-6 d-flex align-items-end">
                <span class="me-2" style="align-self: flex-end; margin-bottom: 4px; font-size: 14px;">powered
                    by</span>
                <a href="https://www.instagram.com/pt.imagi/" target="_blank">
                    <img src="{{ asset('frontend/img/imagi-white.jpeg') }}" alt="Logo" style="height: 50px;">
                </a>
            </div>

            <div class="col-6 d-flex align-items-end justify-content-end">
                <a href="#" class="btn btn-download me-2" id="downloadBtn">
                    <i class="fa fa-download"></i> Download Invoice
                </a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('downloadBtn').addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
    </script>

</body>

</html>
