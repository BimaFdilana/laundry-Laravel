@extends('layouts.backend')
@section('title', 'Super Admin - Aktivitas Karyawan')
@section('header', 'Aktivitas Karyawan')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Input Aktivitas Karyawan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.aktivitas-karyawan.store') }}" method="POST">
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
                                    <label>Jenis Aktivitas</label>
                                    <select name="jenis_aktivitas" class="form-control" required>
                                        <option value="cuci">Cuci</option>
                                        <option value="gosok">Gosok</option>
                                        <option value="packing">Packing</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tipe Transaksi</label>
                                    <select name="transaksi_type" class="form-control" id="transaksi_type" required>
                                        <option value="reguler">Reguler</option>
                                        <option value="satuan">Satuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Transaksi (Invoice - Customer)</label>
                                    <select name="transaksi_id" class="form-control" id="transaksi_id" required>
                                        <option value="">-- Pilih Transaksi --</option>
                                        @foreach ($transaksiList as $t)
                                            <option value="{{ $t->id }}" class="opt-reguler">{{ $t->invoice }} - {{ $t->customer }}</option>
                                        @endforeach
                                        @foreach ($satuanList as $t)
                                            <option value="{{ $t->id }}" class="opt-satuan" style="display:none;">{{ $t->invoice }} - {{ $t->customer }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Jumlah Item</label>
                                    <input type="number" name="jumlah_item" class="form-control" placeholder="Opsional" min="1">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Aktivitas</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Riwayat Aktivitas</h4>
                    <form method="GET" action="{{ route('superadmin.aktivitas-karyawan.index') }}" class="form-inline">
                        <select name="karyawan_id" class="form-control mr-2">
                            <option value="">-- Semua Karyawan --</option>
                            @foreach ($karyawans as $k)
                                <option value="{{ $k->id }}" {{ $karyawan_id == $k->id ? 'selected' : '' }}>{{ $k->name }}</option>
                            @endforeach
                        </select>
                        <input type="date" name="tanggal" class="form-control mr-2" value="{{ $tanggal }}">
                        <button type="submit" class="btn btn-info">Filter</button>
                        @if ($karyawan_id || $tanggal)
                            <a href="{{ route('superadmin.aktivitas-karyawan.index') }}" class="btn btn-secondary ml-1">Reset</a>
                        @endif
                    </form>
                </div>
                <div class="card-body table-responsive">
                    <table class="table zero-configuration">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Karyawan</th>
                                <th>Jenis</th>
                                <th>Transaksi</th>
                                <th>Customer</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($aktivitas as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ $item->jam_mulai }}</td>
                                    <td>
                                        @if ($item->jam_selesai)
                                            {{ $item->jam_selesai }}
                                        @else
                                            <span class="badge badge-warning">Dalam Proses</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->karyawan->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $item->jenis_aktivitas == 'cuci' ? 'badge-primary' : ($item->jenis_aktivitas == 'gosok' ? 'badge-info' : 'badge-success') }}">
                                            {{ ucfirst($item->jenis_aktivitas) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($item->transaksi)
                                            {{ $item->transaksi->invoice }}
                                        @elseif ($item->transaksiSatuan)
                                            {{ $item->transaksiSatuan->invoice }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->transaksi)
                                            {{ $item->transaksi->customer }}
                                        @elseif ($item->transaksiSatuan)
                                            {{ $item->transaksiSatuan->customer }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $item->jumlah_item ?? '-' }}</td>
                                    <td>
                                        @if (!$item->jam_selesai)
                                            <form action="{{ route('superadmin.aktivitas-karyawan.selesai', $item->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Tandai Selesai">
                                                    <i class="feather icon-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('superadmin.aktivitas-karyawan.destroy', $item->id) }}" method="POST" style="display:inline-block;"
                                            onsubmit="return confirm('Hapus aktivitas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="feather icon-trash-2"></i>
                                            </button>
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

@section('scripts')
    <script>
        document.getElementById('transaksi_type').addEventListener('change', function () {
            var type = this.value;
            var opts = document.querySelectorAll('#transaksi_id option');
            opts.forEach(function (opt) {
                if (opt.value === '') return;
                if (type === 'reguler') {
                    opt.style.display = opt.classList.contains('opt-reguler') ? '' : 'none';
                } else {
                    opt.style.display = opt.classList.contains('opt-satuan') ? '' : 'none';
                }
            });
            document.getElementById('transaksi_id').value = '';
        });
    </script>
@endsection
