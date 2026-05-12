@extends('layouts.backend')
@section('title', 'Super Admin - Data Pemasukan')
@section('header', 'Data Pemasukan')
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
    @php use Carbon\Carbon; @endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">
                            Data Pemasukan
                            <a href="{{ route('pemasukan.create') }}" class="btn btn-primary ml-2">Tambah</a>
                        </h4>

                        <form method="GET" action="{{ route('pemasukan.index') }}" class="form-inline">
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

                    @if (request('tahun'))
                        <p>
                            Menampilkan data
                            @if (request('hari'))
                                tanggal <strong>{{ request('hari') }}</strong>
                            @endif
                            @if (request('bulan'))
                                bulan
                                <strong>{{ DateTime::createFromFormat('!m', request('bulan'))->format('F') }}</strong>
                            @endif
                            tahun <strong>{{ request('tahun') }}</strong>
                        </p>
                    @endif

                    <div class="table-responsive">
                        <table class="table zero-configuration">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Sumber</th>
                                    <th>Keterangan</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($pemasukan as $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                                        <td>{{ $item['sumber'] }}</td>
                                        <td>{{ $item['keterangan'] ?: '-' }}</td>
                                        <td>{{ $item['jumlah'] }}</td>
                                        <td>Rp. {{ number_format($item['total'], 0, ',', '.') }}</td>
                                        <td>
                                            @if (isset($item['tipe']) && $item['tipe'] === 'manual')
                                                <a href="{{ route('pemasukan.edit', $item['id']) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>

                                                <form action="{{ route('pemasukan.destroy', $item['id']) }}" method="POST"
                                                    style="display:inline-block;"
                                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <h5 class="text-success">
                            Total Pemasukan (Lunas):
                            Rp. {{ number_format($totalPemasukanBersih, 0, ',', '.') }}
                        </h5>
                        <p class="text-muted">
                            Termasuk dari Pemasukan Manual: <strong>Rp.
                                {{ number_format($totalPemasukanManual, 0, ',', '.') }}</strong>
                        </p>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Pemasukan Transaksi Reguler</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table zero-configuration">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksiList as $item)
                                <tr>
                                    <td>{{ $item->invoice }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $item->customer ?? '-' }}</td>
                                    <td>{{ Rupiah::getRupiah($item->harga_akhir) }}</td>
                                    @php
                                        $badgeClass = match ($item->status_payment) {
                                            'Success' => 'badge-success',
                                            'Pending' => 'badge-danger',
                                            default => 'badge-secondary',
                                        };

                                        $badgeText = match ($item->status_payment) {
                                            'Success' => 'Lunas',
                                            'Pending' => 'Belum Lunas',
                                            default => $item->status_payment,
                                        };
                                    @endphp
                                    <td>
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $badgeText }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right" style="color: white;"><strong>Total Pemasukan
                                        Transaksi</strong>
                                </td>
                                <td colspan="2"><strong class="text-success">{{ Rupiah::getRupiah($totalTransaksi) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right" style="color: white;"><strong>Total Utang
                                        (Pending)</strong></td>
                                <td colspan="2"><strong class="text-danger">{{ Rupiah::getRupiah($utangTransaksi) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header">
                    <h4 class="card-title">Detail Pemasukan Transaksi Satuan</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table zero-configuration">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($satuanList as $item)
                                <tr>
                                    <td>{{ $item->invoice }}</td>
                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $item->customer ?? '-' }}</td>
                                    <td>{{ Rupiah::getRupiah($item->harga_akhir) }}</td>
                                    @php
                                        $badgeClass = match ($item->status_payment) {
                                            'Success' => 'badge-success',
                                            'Pending' => 'badge-danger',
                                            default => 'badge-secondary',
                                        };

                                        $badgeText = match ($item->status_payment) {
                                            'Success' => 'Lunas',
                                            'Pending' => 'Belum Lunas',
                                            default => $item->status_payment,
                                        };
                                    @endphp
                                    <td>
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $badgeText }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right" style="color: white;"><strong>Total Pemasukan
                                        Transaksi</strong>
                                </td>
                                <td colspan="2"><strong class="text-success">{{ Rupiah::getRupiah($totalSatuan) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right" style="color: white;"><strong>Total Utang
                                        (Pending)</strong></td>
                                <td colspan="2"><strong class="text-danger">{{ Rupiah::getRupiah($utangSatuan) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header">
                    <h4 class="card-title">Detail Pemasukan Pembelian Paket Laundry (Kuota)</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table zero-configuration">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Customer</th>
                                <th>Paket</th>
                                <th>Jumlah (kg)</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Kuota Manual --}}
                            @foreach ($kuotaList as $item)
                                @php
                                    $keterangan = strtolower($item->keterangan ?? '');
                                    $status = Str::contains($keterangan, 'nyusul') ? 'Pending' : 'Success';
                                    $badgeClass = $status == 'Pending' ? 'badge-danger' : 'badge-success';
                                    $badgeText = $status == 'Pending' ? 'Belum Lunas' : 'Lunas';
                                @endphp
                                <tr>
                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $item->pemasukan ?? '-' }}</td>
                                    <td>{{ $item->kategori }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{{ Rupiah::getRupiah($item->total) }}</td>
                                    <td>
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $badgeText }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Kuota dari Purchase Request --}}
                            @foreach ($purchaseKuotaList as $item)
                                <tr>
                                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $item->user->name ?? '-' }}</td>
                                    <td>{{ $item->package_category }}</td>
                                    <td>{{ $item->package_kg }}</td>
                                    <td>{{ Rupiah::getRupiah($item->package_price) }}</td>
                                    <td>
                                        <span class="badge badge-success">Lunas</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right" style="color: white;">
                                    <strong>Total Pemasukan Paket Laundry (Lunas)</strong>
                                </td>
                                <td colspan="2"><strong class="text-success">{{ Rupiah::getRupiah($totalKuotaLunas) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right" style="color: white;">
                                    <strong>Total Utang Paket Laundry</strong>
                                </td>
                                <td colspan="2"><strong class="text-danger">{{ Rupiah::getRupiah($totalKuotaPending) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
