@extends('layouts.backend')
@section('title', 'Tambah Data Order')
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
    @if ($cek_harga && $harga_value != 0)
        <div class="card card-outline-info">
            <div class="card-header">
                <h4 class="card-title">Form Tambah Data Order</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('pelayanan.store') }}" method="POST">
                    @csrf
                    <div class="form-body">
                        <div class="row p-t-20">
                            <!-- Pilih Customer -->
                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label">Nama Customer</label>
                                    <select name="customer_id"
                                        class="form-control {{ $errors->has('customer_id') ? 'is-invalid' : '' }}" required>
                                        <option value="">-- Pilih Customer --</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('customer_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('customer_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- No Transaksi -->
                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label">No Transaksi</label>
                                    <input type="text" name="invoice" value="{{ $newID }}"
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
                                <div class="form-group has-success">
                                    <label class="control-label">Tanggal Transaksi</label>
                                    <input type="date" name="tgl_transaksi"
                                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                        class="form-control {{ $errors->has('tgl_transaksi') ? 'is-invalid' : '' }}"
                                        required>
                                    @if ($errors->has('tgl_transaksi'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('tgl_transaksi') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Berat Pakaian -->
                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label">Berat Pakaian</label>
                                    <input type="text"
                                        class="form-control form-control-danger {{ $errors->has('kg') ? 'is-invalid' : '' }}"
                                        name="kg" placeholder="Berat Pakaian (kg)" autocomplete="off" required>
                                    @if ($errors->has('kg'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('kg') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row p-t-20">
                            <!-- Jenis Pembayaran -->
                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label">Jenis Pembayaran</label>
                                    <select
                                        class="form-control custom-select {{ $errors->has('jenis_pembayaran') ? 'is-invalid' : '' }}"
                                        name="jenis_pembayaran" required>
                                        <option value="">-- Pilih Jenis Pembayaran --</option>
                                        <option value="Tunai">Tunai</option>
                                        <option value="Transfer">Transfer</option>
                                    </select>
                                    @if ($errors->has('jenis_pembayaran'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('jenis_pembayaran') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Jenis Layanan -->
                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label">Pilih Layanan</label>
                                    <select id="id" name="harga_id"
                                        class="form-control select2 {{ $errors->has('harga_id') ? 'is-invalid' : '' }}"
                                        required>
                                        <option value="">-- Jenis Layanan --</option>
                                        @foreach ($harga as $h)
                                            <option value="{{ $h->id }}" data-hari="{{ $h->hari }}">
                                                {{ $h->nama }} - {{ $h->jenis }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('harga_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('harga_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Jumlah Lembar Baju -->
                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label">Jumlah Lembar Baju</label>
                                    <input type="number" name="jumlah_lembar_baju" placeholder="Masukkan Angka"
                                        class="form-control {{ $errors->has('jumlah_lembar_baju') ? 'is-invalid' : '' }}"
                                        required>
                                    @if ($errors->has('jumlah_lembar_baju'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('jumlah_lembar_baju') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Karyawan -->
                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label">Karyawan</label>
                                    <select name="karyawan_id"
                                        class="form-control {{ $errors->has('karyawan_id') ? 'is-invalid' : '' }}"
                                        required>
                                        <option value="">-- Pilih Karyawan --</option>
                                        @foreach ($karyawans as $karyawan)
                                            <option value="{{ $karyawan->id }}">{{ $karyawan->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('karyawan_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('karyawan_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row p-t-20">
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

                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label">Perkiraan Hari</label>
                                    <input type="text" name="hari"
                                        class="form-control {{ $errors->has('hari') ? 'is-invalid' : '' }}" required>
                                    @if ($errors->has('hari'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('hari') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-3">
                                <span id="select-harga"></span>
                            </div>

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

                        <div class="row p-t-20">
                            <!-- Catatan -->
                            <div class="col-md-3">
                                <div class="form-group has-success">
                                    <label class="control-label">Catatan</label>
                                    <textarea name="catatan_admin" id="catatan_admin"
                                        class="form-control {{ $errors->has('catatan_admin') ? 'is-invalid' : '' }}" rows="3" required>{{ old('catatan_admin') }}</textarea>
                                    @if ($errors->has('catatan_admin'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('catatan_admin') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary mr-1 mb-1">Tambah</button>
                        <button type="reset" class="btn btn-outline-warning mr-1 mb-1">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <!-- Tampilkan pesan jika tidak ada harga aktif -->
        <div class="card">
            <div class="col text-center">
                <img src="{{ asset('backend/images/pages/empty.svg') }}"
                    style="height:500px; width:100%; margin-top:10px">
                <h2 class="mt-1">Data Harga Kosong / Tidak Aktif !</h2>
                <h4>Mohon hubungi Administrator :)</h4>
                <a href="{{ route('pelayanan.index') }}" class="btn btn-primary mt-3">Kembali</a>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var id = $("#id").val();
            $.get('{{ Url('listharga') }}', {
                '_token': $('meta[name=csrf-token]').attr('content'),
                id: id
            }, function(resp) {
                $("#select-harga").html(resp);
            });
        });

        $(document).on('change', '#id', function(e) {
            var id = $(this).val();
            $.get('{{ Url('listharga') }}', {
                '_token': $('meta[name=csrf-token]').attr('content'),
                id: id
            }, function(resp) {
                $("#select-harga").html(resp);
            });
        });

        $(document).on('change', '#id', function() {
            var selectedOption = $(this).find('option:selected');
            var hari = selectedOption.data('hari');

            if (hari !== undefined && hari !== null) {
                $('input[name="hari"]').val(hari);
            }
        });
    </script>
@endsection
