@extends('layouts.backend')
@section('title', 'Super Admin - Data Pengeluaran')
@section('header', 'Data Pengeluaran')
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
                            Data Pengeluaran
                            <a href="{{ route('pengeluaran.create') }}" class="btn btn-primary ml-2">Tambah</a>
                        </h4>

                        <form method="GET" action="{{ route('pengeluaran.index') }}" class="form-inline">
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
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>

                            <button type="submit" class="btn btn-info">Filter</button>
                        </form>
                    </div>

                    @if (request('hari') && request('bulan') && request('tahun'))
                        <p>Menampilkan data tanggal
                            <strong>{{ request('hari') }}
                                {{ DateTime::createFromFormat('!m', request('bulan'))->format('F') }}
                                {{ request('tahun') }}</strong>
                        </p>
                    @elseif (request('bulan') && request('tahun'))
                        <p>Menampilkan data bulan
                            <strong>{{ DateTime::createFromFormat('!m', request('bulan'))->format('F') }}</strong> tahun
                            <strong>{{ request('tahun') }}</strong>
                        </p>
                    @endif

                    <div class="table-responsive">
                        <table class="table zero-configuration">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Pengeluaran</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($pengeluaran as $item)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $item->pengeluaran }}</td>
                                        <td>{{ $item->kategori }}</td>
                                        <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>Rp. {{ number_format($item->total, 0, ',', '.') }}</td>
                                        <td>
                                            {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') : '-' }}
                                        </td>
                                        <td>{{ $item->keterangan ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus item ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('pengeluaran.edit', $item->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php $no++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <h5 class="text-danger">
                            Total Pengeluaran:
                            Rp. {{ number_format($pengeluaran->sum(fn($item) => (int) $item['total']), 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
