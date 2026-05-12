@extends('layouts.backend')
@section('title', 'Super Admin - Data Layanan Laundry Satuan')
@section('content')
    @include('partials.flash-message')
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"> Data Layanan Laundry Satuan</h4>
                        <div class="table-responsive m-t-0">
                            <table id="myTable" class="table display table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Jenis</th>
                                        <th>Hari</th>
                                        <th>Pcs</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($satuan as $item)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->jenis }}</td>
                                            <td>{{ $item->hari }}</td>
                                            <td>{{ $item->pcs}} pcs</td>
                                            <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($item->status == '1')
                                                    <span class="label label-primary">Aktif</span>
                                                @else
                                                    <span class="label label-warning">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-warning" data-toggle="modal"
                                                    data-id="{{ $item->id }}" data-id-nama="{{ $item->nama }}"
                                                    data-id-jenis="{{ $item->jenis }}" data-id-pcs="{{ $item->pcs }}"
                                                    data-id-harga="{{ $item->harga }}" data-id-hari="{{ $item->hari }}"
                                                    data-id-status="{{ $item->status }}" id="click_satuan"
                                                    data-target="#edit_satuan" style="color:white">Edit</a>
                                            </td>
                                        </tr>
                                        @php $no++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @include('superadmin.laundri.editsatuan')
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-black">Form Tambah Data Layanan Satuan</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('satuan-store') }}" method="POST">
                            @csrf
                            <div class="form-body">
                                <div class="row p-t-20">
                                    <div class="col-lg-12 col-xl-12">
                                        <div class="form-group has-success">
                                            <label class="control-label">Nama Barang Satuan</label>
                                            <input type="text" name="nama" value="{{ old('nama') }}"
                                                class="form-control @error('nama') is-invalid @enderror"
                                                placeholder="Tambahkan Nama Barang" required autocomplete="off">
                                            @error('jenis')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-xl-12">
                                        <div class="form-group has-success">
                                            <label class="control-label">Jenis Layanan Satuan</label>
                                            <input type="text" name="jenis" value="{{ old('jenis') }}"
                                                class="form-control @error('jenis') is-invalid @enderror"
                                                placeholder="Tambahkan Jenis Layanan Satuan" required autocomplete="off">
                                            @error('jenis')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-xl-12">
                                        <div class="form-group has-success">
                                            <label class="control-label">Pcs</label>
                                            <input type="text" class="form-control" value="1" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-xl-12">
                                        <div class="form-group has-success">
                                            <label class="control-label">Harga Per-Pcs</label>
                                            <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                                name="harga" value="{{ old('harga') }}" placeholder="Harga Per-Pcs"
                                                required>
                                            <small class="form-control-feedback">Tuliskan tanpa tanda ',' dan
                                                '.'</small>
                                            @error('harga')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-xl-12">
                                        <div class="form-group has-success">
                                            <label class="control-label">Lama Hari</label>
                                            <input type="text" name="hari" value="{{ old('hari') }}"
                                                class="form-control @error('hari') is-invalid @enderror"
                                                placeholder="Lama Hari" required>
                                            @error('hari')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                                <button type="reset" class="btn btn-danger">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).on('click', '#click_satuan', function() {
            const id = $(this).data('id');
            const nama = $(this).data('id-nama');
            const jenis = $(this).data('id-jenis');
            const pcs = $(this).data('id-pcs');
            const hari = $(this).data('id-hari');
            const harga = $(this).data('id-harga');
            const status = $(this).data('id-status');

            $('#id_satuan').val(id);
            $('#nama').val(nama);
            $('#jenis').val(jenis.trim());
            $('#pcs').val(pcs);
            $('#hari').val(hari);
            $('#harga').val(harga);
            $('#status').val(status);
        });

        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
@endsection
