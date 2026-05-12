@extends('layouts.backend')
@section('title', 'Super Admin - Data Paket Laundry')
@section('content')
    @include('partials.flash-message')
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"> Data Paket Laundry</h4>
                        <div class="table-responsive m-t-0">
                            <table id="myTable" class="table display table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kg</th>
                                        <th>Harga</th>
                                        <th>Kategori</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paket as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->kg }}</td>
                                            <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                            <td>{{ $item->kategori }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-warning click-paket" data-toggle="modal"
                                                    data-id="{{ $item->id }}" data-id-kg="{{ $item->kg }}"
                                                    data-id-harga="{{ $item->harga }}"
                                                    data-id-kategori="{{ $item->kategori }}" data-target="#edit_paket"
                                                    style="color:white">Edit</a>

                                                <button class="btn btn-sm btn-danger delete-paket"
                                                    data-id="{{ $item->id }}" data-kg="{{ $item->kg }}"
                                                    data-toggle="modal" data-target="#deleteModal">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @include('superadmin.paket.editpaket')
                    </div>
                </div>
            </div>

            <!-- Modal Hapus -->
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form id="formDeletePaket" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <p>Yakin ingin menghapus paket <strong id="kgPaketHapus"></strong>?</p>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Hapus</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-black">Form Tambah Data Paket</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('paket.store') }}" method="POST">
                            @csrf
                            <div class="form-body">
                                <div class="row p-t-20">
                                    {{-- Kg Paket --}}
                                    <div class="col-lg-12 col-xl-12">
                                        <div class="form-group has-success">
                                            <label class="control-label">Kg Paket</label>
                                            <input type="text" name="kg" value="{{ old('kg') }}"
                                                class="form-control @error('kg') is-invalid @enderror"
                                                placeholder="Kg Paket" required autocomplete="off">
                                            @error('kg')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Harga --}}
                                    <div class="col-lg-12 col-xl-12">
                                        <div class="form-group has-success">
                                            <label class="control-label">Harga</label>
                                            <input type="number" name="harga" value="{{ old('harga') }}"
                                                class="form-control @error('harga') is-invalid @enderror"
                                                placeholder="Harga Paket" required>
                                            <small class="form-control-feedback">Tuliskan tanpa tanda ',' atau '.'</small>
                                            @error('harga')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Kategori --}}
                                    <div class="col-lg-12 col-xl-12">
                                        <div class="form-group has-success">
                                            <label class="control-label">Kategori</label>
                                            <select name="kategori"
                                                class="form-control @error('kategori') is-invalid @enderror" required>
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach ($hargas->unique('jenis') as $harga)
                                                    <option value="{{ $harga->jenis }}"
                                                        {{ old('kategori') == $harga->jenis ? 'selected' : '' }}>
                                                        {{ $harga->jenis }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('kategori')
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
        $(document).on('click', '.click-paket', function() {
            const id = $(this).data('id');
            const kg = $(this).data('id-kg');
            const harga = $(this).data('id-harga');
            const kategori = $(this).data('id-kategori');

            $('#edit_id_paket').val(id);
            $('#edit_kg').val(kg);
            $('#edit_harga').val(harga);
            $('#edit_kategori').val(kategori);

            // Set form action ke route update dengan ID paket
            $('#formEditPaket').attr('action', `{{ url('superadmin/paket') }}/${id}`);
        });

        $(document).ready(function() {
            $('#myTable').DataTable();
        });

        // Delete Paket
        $(document).on('click', '.delete-paket', function() {
            const id = $(this).data('id');
            const kg = $(this).data('kg');
            $('#kgPaketHapus').text(kg);
            $('#formDeletePaket').attr('action', `{{ url('superadmin/paket') }}/${id}`);
        });
    </script>
@endsection
