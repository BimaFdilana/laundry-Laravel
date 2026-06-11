@extends('layouts.backend')
@section('title', 'Tambah Transaksi Satuan')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <div class="card card-outline-info">
        <div class="card-header">
            <h4 class="card-title">Form Tambah Transaksi Satuan</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('transaksi-satuan.store') }}" method="POST">
                @csrf
                <div class="form-body">
                    <div class="row">
                        <!-- Customer -->
                        <div class="col-md-3">
                            <label>Nama Customer</label>
                            <select name="customer_id"
                                class="form-control {{ $errors->has('customer_id') ? 'is-invalid' : '' }}" required>
                                <option value="">-- Pilih Customer --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('customer_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('customer_id') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- No Transaksi -->
                        <div class="col-md-3">
                            <div class="form-group has-success">
                                <label class="control-label">No Transaksi</label>
                                <input type="text" name="invoice" value="{{ old('invoice', $invoice) }}"
                                    class="form-control {{ $errors->has('invoice') ? 'is-invalid' : '' }}" readonly>
                                @if ($errors->has('invoice'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('invoice') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Tanggal Transaksi -->
                        <div class="col-md-3">
                            <label for="tgl_transaksi">Tanggal Transaksi</label>
                            <input type="date" name="tgl_transaksi" id="tgl_transaksi"
                                class="form-control {{ $errors->has('tgl_transaksi') ? 'is-invalid' : '' }}"
                                value="{{ old('tgl_transaksi', date('Y-m-d')) }}" required>
                            @if ($errors->has('tgl_transaksi'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('tgl_transaksi') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- Karyawan -->
                        <div class="col-md-3">
                            <label>Pilih Karyawan</label>
                            <select name="karyawan_id"
                                class="form-control {{ $errors->has('karyawan_id') ? 'is-invalid' : '' }}" required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach ($karyawans as $karyawan)
                                    <option value="{{ $karyawan->id }}"
                                        {{ old('karyawan_id') == $karyawan->id ? 'selected' : '' }}>{{ $karyawan->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('karyawan_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('karyawan_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-3">
                        <!-- Catatan -->
                        <div class="col-md-3">
                            <label>Catatan</label>
                            <textarea name="catatan_admin" class="form-control {{ $errors->has('catatan_admin') ? 'is-invalid' : '' }}"
                                rows="3" required>{{ old('catatan_admin') }}</textarea>
                            @if ($errors->has('catatan_admin'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('catatan_admin') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- Jenis Pembayaran -->
                        <div class="col-md-3">
                            <label>Jenis Pembayaran</label>
                            <select name="jenis_pembayaran"
                                class="form-control {{ $errors->has('jenis_pembayaran') ? 'is-invalid' : '' }}" required>
                                <option value="">-- Pilih --</option>
                                <option value="Tunai" {{ old('jenis_pembayaran') == 'Tunai' ? 'selected' : '' }}>Tunai
                                </option>
                                <option value="Transfer" {{ old('jenis_pembayaran') == 'Transfer' ? 'selected' : '' }}>
                                    Transfer</option>
                            </select>
                            @if ($errors->has('jenis_pembayaran'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('jenis_pembayaran') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- Status Bayar -->
                        <div class="col-md-3">
                            <label>Status Bayar</label>
                            <select name="status_bayar"
                                class="form-control {{ $errors->has('status_bayar') ? 'is-invalid' : '' }}" required>
                                <option value="belum_bayar" {{ old('status_bayar') == 'belum_bayar' ? 'selected' : '' }}>
                                    Belum Bayar</option>
                                <option value="lunas" {{ old('status_bayar') == 'lunas' ? 'selected' : '' }}>Lunas
                                </option>
                            </select>
                            @if ($errors->has('status_bayar'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('status_bayar') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- Jenis Pewangi -->
                        <div class="col-md-3">
                            <label for="jenis_pewangi">Jenis Pewangi</label>
                            <input type="text" name="jenis_pewangi" id="jenis_pewangi"
                                class="form-control {{ $errors->has('jenis_pewangi') ? 'is-invalid' : '' }}"
                                value="{{ old('jenis_pewangi') }}" placeholder="Contoh: Lavender, Mawar" required>

                            @if ($errors->has('jenis_pewangi'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('jenis_pewangi') }}</strong>
                                </span>
                            @endif
                        </div>

                        <!-- Disc -->
                        <div class="col-md-3">
                            <div class="form-group has-success">
                                <label class="control-label">Diskon</label>
                                <input type="number" name="disc" placeholder="Contoh: 40000"
                                    class="form-control {{ $errors->has('disc') ? 'is-invalid' : '' }}" required>
                                @if ($errors->has('disc'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('disc') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5 class="mt-4">Detail Barang</h5>
                    <table class="table table-bordered" id="barang-table">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Hari</th>
                                <th>Harga</th>
                                <th><button type="button" id="addRow" class="btn btn-success btn-sm">+</button></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="details[0][satuan_id]"
                                        class="form-control {{ $errors->has('details.0.satuan_id') ? 'is-invalid' : '' }}"
                                        required onchange="getSatuanDetails(0)">
                                        <option value="">-- Pilih Barang Satuan --</option>
                                        @foreach ($satuans as $item)
                                            <option value="{{ $item->id }}" data-hari="{{ $item->hari }}"
                                                data-harga="{{ $item->harga }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('details.0.satuan_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('details.0.satuan_id') }}</strong>
                                        </span>
                                    @endif
                                </td>
                                <td><input type="text" name="details[0][pcs]"
                                        class="form-control {{ $errors->has('details.0.pcs') ? 'is-invalid' : '' }}"
                                        required></td>
                                <td><span id="hari-0"></span></td>
                                <td><span id="harga-0"></span></td>
                                <td><button type="button" class="btn btn-danger btn-sm removeRow">-</button></td>
                            </tr>

                        </tbody>
                    </table>

                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                        <a href="{{ route('pelayanan.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let rowCount = 1;

        // Tambah baris baru
        $('#addRow').click(function() {
            let html = `<tr>
            <td>
                <select name="details[${rowCount}][satuan_id]" class="form-control" required onchange="getSatuanDetails(${rowCount})">
                    <option value="">-- Pilih Barang Satuan --</option>
                    @foreach ($satuans as $item)
                        <option value="{{ $item->id }}" data-hari="{{ $item->hari }}" data-harga="{{ $item->harga }}">{{ $item->nama }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="details[${rowCount}][pcs]" class="form-control" required></td>
            <td><span id="hari-${rowCount}"></span></td>
            <td><span id="harga-${rowCount}"></span></td>
            <td><button type="button" class="btn btn-danger btn-sm removeRow">-</button></td>
        </tr>`;

            $('#barang-table tbody').append(html);
            rowCount++;
        });

        // Tampilkan info hari dan harga berdasarkan pilihan
        function getSatuanDetails(row) {
            let selectedOption = $(`select[name="details[${row}][satuan_id]"] option:selected`);
            let hari = selectedOption.data('hari');
            let harga = selectedOption.data('harga');

            $(`#hari-${row}`).text(hari);
            $(`#harga-${row}`).text(harga);
        }

        // Hapus baris
        $(document).on('click', '.removeRow', function() {
            $(this).closest('tr').remove();
        });
    </script>
@endsection
