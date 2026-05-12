@extends('layouts.backend')
@section('title', 'Dashboard Customer')
@section('style')
    <style>
        .progress-step {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }

        .step-done {
            background-color: #28a745;
        }

        .step-active {
            background-color: #ffc107;
        }

        .step-pending {
            background-color: #6c757d;
        }

        .progress-container {
            position: relative;
            width: 100%;
            padding-top: 30px;
            /* Jarak antara garis dan langkah */
        }

        .progress-line {
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 4px;
            background-color: #e0e0e0;
            z-index: 1;
        }

        .progress-line-fill {
            position: absolute;
            top: 15px;
            left: 0;
            height: 4px;
            background-color: #28a745;
            z-index: 2;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4 col-sm-4 col-12">
            <div class="card">
                <div class="card-header d-flex align-items-start pb-0">
                    <div>
                        <h2 class="text-bold-700 mb-0">{{ $masuk }}</h2>
                        <p>Total Laundry</p>
                    </div>
                    <div class="avatar bg-rgba-success p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-box text-success font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-12">
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
        <div class="col-lg-4 col-sm-4 col-12">
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

        <!-- Kuota Laundry per Kategori -->
        @foreach ($kuotaPerKategori as $kategori => $kuotas)
            <div class="col-lg-4 col-sm-4 col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-start pb-0">
                        <div>
                            <h2 class="text-bold-700 mb-0">{{ $kuotas->sum('kuota') ?? 0 }}</h2>
                            <p>Kuota ({{ $kategori }})</p>
                        </div>
                        <div class="avatar bg-rgba-info p-50 m-0">
                            <div class="avatar-content">
                                <i class="feather icon-database text-info font-medium-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">Progress Laundry Terbaru Anda</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">

                        @if ($transaksis->count())
                            @foreach ($transaksis as $transaksi)
                                @php
                                    $status_order = $transaksi->status_order;
                                    $progressWidth = 0;

                                    // Tentukan progress berdasarkan status_order
                                    if ($status_order == 'Antrian') {
                                        $progressWidth = 5; // Step 1
                                    } elseif ($status_order == 'Process') {
                                        $progressWidth = 35; // Step 2
                                    } elseif ($status_order == 'Done') {
                                        $progressWidth = 70; // Step 3
                                    } elseif ($status_order == 'Delivery') {
                                        $progressWidth = 100; // Step 4
                                    }
                                @endphp

                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="card-title mb-0">
                                            Progress Laundry - Kode Resi: {{ $transaksi->invoice }}
                                            @if ($transaksi->is_satuan)
                                                <span class="badge badge-info ml-1">Satuan</span>
                                            @else
                                                <span class="badge badge-primary ml-1">Biasa</span>
                                            @endif
                                        </h4>

                                        @if ($transaksi->status_order == 'Delivery')
                                            <form action="{{ route('customer.hide-transaction', $transaksi->id) }}"
                                                method="POST" onsubmit="return confirm('Sembunyikan transaksi ini?')"
                                                class="mb-0">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger">Sembunyikan</button>
                                            </form>
                                        @endif
                                    </div>

                                    <div class="card-body">
                                        <p class="mb-1"><strong>Tanggal Masuk:</strong>
                                            {{ \Carbon\Carbon::parse($transaksi->created_at)->format('d M Y') }}</p>
                                        <p class="mb-1"><strong>Tanggal Estimasi Selesai:</strong>
                                            {{ $transaksi->estimasi_selesai }}</p>

                                        <!-- Progress bar -->
                                        <div class="progress-container">
                                            <div class="progress-line"></div>
                                            <div class="progress-line-fill" style="width: {{ $progressWidth }}%;"></div>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <!-- Step 1: Antrian -->
                                                <div class="text-center" style="z-index: 3;">
                                                    <div
                                                        class="progress-step {{ $progressWidth >= 5 ? 'step-done' : 'step-pending' }}">
                                                        1</div>
                                                    <p class="mt-2">Antrian</p>
                                                </div>

                                                <!-- Step 2: Proses -->
                                                <div class="text-center" style="z-index: 3;">
                                                    <div
                                                        class="progress-step {{ $progressWidth >= 35 ? 'step-done' : 'step-pending' }}">
                                                        2</div>
                                                    <p class="mt-2">Proses</p>
                                                </div>

                                                <!-- Step 3: Delivery -->
                                                <div class="text-center" style="z-index: 3;">
                                                    <div
                                                        class="progress-step {{ $progressWidth >= 70 ? 'step-done' : 'step-pending' }}">
                                                        3</div>
                                                    <p class="mt-2">Delivery</p>
                                                </div>

                                                <!-- Step 4: Selesai -->
                                                <div class="text-center" style="z-index: 3;">
                                                    <div
                                                        class="progress-step {{ $progressWidth == 100 ? 'step-done' : 'step-pending' }}">
                                                        4</div>
                                                    <p class="mt-2">Selesai</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>Belum ada transaksi laundry yang sedang berlangsung.</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
