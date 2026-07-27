@extends('layouts.backend')
@section('title', 'Super Admin - Voucher Diskon')
@section('content')
    @include('partials.flash-message')
    <div class="col-lg-12">
        <div class="row">
            <!-- List Table -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title font-weight-bold">Daftar Voucher Diskon</h4>
                        <div class="table-responsive m-t-0">
                            <table id="myTable" class="table display table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kode Voucher</th>
                                        <th>Nama Voucher</th>
                                        <th>Nominal Diskon</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($diskons as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><span class="badge badge-light-primary font-weight-bold" style="font-size: 14px;">{{ $item->kode }}</span></td>
                                            <td>{{ $item->nama }}</td>
                                            <td class="text-success font-weight-bold">Rp. {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($item->status)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-danger">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-warning click-edit-diskon" data-toggle="modal"
                                                    data-id="{{ $item->id }}" data-kode="{{ $item->kode }}"
                                                    data-nama="{{ $item->nama }}" data-nominal="{{ $item->nominal }}"
                                                    data-status="{{ $item->status }}" data-target="#edit_diskon"
                                                    style="color:white">Edit</a>

                                                <button class="btn btn-sm btn-danger delete-diskon"
                                                    data-id="{{ $item->id }}" data-kode="{{ $item->kode }}"
                                                    data-toggle="modal" data-target="#deleteModal">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Tambah -->
            <div class="col-lg-4">
                <div class="card card-outline-info">
                    <div class="card-header border-bottom">
                        <h4 class="m-b-0 font-weight-bold">Form Tambah Voucher</h4>
                    </div>
                    <div class="card-body mt-1">
                        <form action="{{ route('diskon.store') }}" method="POST">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12 mb-1">
                                        <div class="form-group">
                                            <label class="control-label font-weight-bold">Kode Voucher</label>
                                            <input type="text" name="kode" value="{{ old('kode') }}"
                                                class="form-control @error('kode') is-invalid @enderror"
                                                placeholder="Contoh: HEMAT5K" required style="text-transform: uppercase;">
                                            @error('kode')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 mb-1">
                                        <div class="form-group">
                                            <label class="control-label font-weight-bold">Nama Voucher</label>
                                            <input type="text" name="nama" value="{{ old('nama') }}"
                                                class="form-control @error('nama') is-invalid @enderror"
                                                placeholder="Contoh: Diskon Awal Bulan" required>
                                            @error('nama')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 mb-1">
                                        <div class="form-group">
                                            <label class="control-label font-weight-bold">Nominal Diskon (Rp)</label>
                                            <input type="number" name="nominal" value="{{ old('nominal') }}"
                                                class="form-control @error('nominal') is-invalid @enderror"
                                                placeholder="Contoh: 5000" required>
                                            @error('nominal')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <div class="form-group">
                                            <label class="control-label font-weight-bold">Status</label>
                                            <select name="status" class="form-control" required>
                                                <option value="1">Aktif</option>
                                                <option value="0">Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions mt-1">
                                <button type="submit" class="btn btn-primary font-weight-bold"> <i class="fa fa-check"></i> Simpan</button>
                                <button type="reset" class="btn btn-outline-warning font-weight-bold">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="edit_diskon" tabindex="-1" role="dialog" aria-labelledby="editDiskonLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title font-weight-bold" id="editDiskonLabel">Edit Voucher Diskon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditDiskon" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Kode Voucher</label>
                            <input type="text" name="kode" id="edit_kode" class="form-control" required style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Nama Voucher</label>
                            <input type="text" name="nama" id="edit_nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Nominal Diskon (Rp)</label>
                            <input type="number" name="nominal" id="edit_nominal" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Status</label>
                            <select name="status" id="edit_status" class="form-control" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary font-weight-bold">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formDeleteDiskon" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title font-weight-bold" id="deleteModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus voucher diskon <strong id="namaDiskonHapus"></strong>?</p>
                    </div>
                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger font-weight-bold">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        // Edit modal filling
        $(document).on('click', '.click-edit-diskon', function() {
            var id = $(this).data('id');
            var kode = $(this).data('kode');
            var nama = $(this).data('nama');
            var nominal = $(this).data('nominal');
            var status = $(this).data('status');

            $('#edit_kode').val(kode);
            $('#edit_nama').val(nama);
            $('#edit_nominal').val(nominal);
            $('#edit_status').val(status);
            
            // Set action URL
            var actionUrl = "{{ route('diskon.index') }}/" + id;
            $('#formEditDiskon').attr('action', actionUrl);
        });

        // Delete modal filling
        $(document).on('click', '.delete-diskon', function() {
            var id = $(this).data('id');
            var kode = $(this).data('kode');

            $('#namaDiskonHapus').text(kode);
            
            // Set action URL
            var actionUrl = "{{ route('diskon.index') }}/" + id;
            $('#formDeleteDiskon').attr('action', actionUrl);
        });
    </script>
@endsection
