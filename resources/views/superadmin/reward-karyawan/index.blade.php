@extends('layouts.backend')
@section('title', 'Super Admin - Reward Karyawan')
@section('header', 'Reward Karyawan')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4 col-sm-6">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Total Reward Diberikan</h6>
                        <h4 class="text-success mb-0">Rp. {{ number_format($totalReward, 0, ',', '.') }}</h4>
                    </div>
                    <div class="avatar bg-rgba-success p-50">
                        <div class="avatar-content">
                            <i class="feather icon-award text-success font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Reward</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.reward-karyawan.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Karyawan</label>
                                    <select name="karyawan_id" class="form-control" required>
                                        <option value="">-- Pilih Karyawan --</option>
                                        @foreach ($karyawans as $k)
                                            <option value="{{ $k->id }}">{{ $k->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Jenis Reward</label>
                                    <select name="jenis_reward" class="form-control" required>
                                        <option value="Bonus">Bonus</option>
                                        <option value="Insentif">Insentif</option>
                                        <option value="Lembur">Lembur</option>
                                        <option value="Prestasi">Prestasi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Nominal (Rp)</label>
                                    <input type="number" name="nominal" class="form-control" required min="0">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <input type="text" name="keterangan" class="form-control" placeholder="Opsional">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Reward</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><h4 class="card-title">Rekap Per Karyawan</h4></div>
                <div class="card-body table-responsive">
                    <table class="table">
                        <thead><tr><th>Karyawan</th><th>Jumlah</th><th>Total</th></tr></thead>
                        <tbody>
                            @foreach ($rekapKaryawan as $r)
                                <tr>
                                    <td>{{ $r->karyawan->name ?? '-' }}</td>
                                    <td>{{ $r->jumlah }}x</td>
                                    <td>Rp. {{ number_format($r->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Riwayat Reward</h4>
                    <form method="GET" action="{{ route('superadmin.reward-karyawan.index') }}" class="form-inline">
                        <select name="karyawan_id" class="form-control mr-2">
                            <option value="">-- Semua --</option>
                            @foreach ($karyawans as $k)
                                <option value="{{ $k->id }}" {{ $karyawan_id == $k->id ? 'selected' : '' }}>{{ $k->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-info">Filter</button>
                    </form>
                </div>
                <div class="card-body table-responsive">
                    <table class="table zero-configuration">
                        <thead>
                            <tr><th>#</th><th>Tanggal</th><th>Karyawan</th><th>Jenis</th><th>Nominal</th><th>Ket</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($rewards as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ $item->karyawan->name ?? '-' }}</td>
                                    <td><span class="badge badge-info">{{ $item->jenis_reward }}</span></td>
                                    <td>Rp. {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                    <td>{{ $item->keterangan ?? '-' }}</td>
                                    <td>
                                        <form action="{{ route('superadmin.reward-karyawan.destroy', $item->id) }}" method="POST"
                                            style="display:inline-block;" onsubmit="return confirm('Hapus reward ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="feather icon-trash-2"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
