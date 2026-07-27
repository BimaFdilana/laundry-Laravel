@extends('layouts.backend')
@section('title', 'Admin - Tambah Kuota Customer')
@section('header', 'Kuota Customer')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="card-title">Form Tambah Kuota Laundry</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('kuota.store') }}" method="POST">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-6 col-xl-6 col-12">
                                    <div class="form-group">
                                        <label for="user_id">Nama Customer</label>
                                        <div class="position-relative">
                                            <select name="user_id" id="user_id"
                                                class="form-control @error('user_id') is-invalid @enderror" required>
                                                <option value="">-- Pilih Customer --</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-6 col-12">
                                    <div class="form-group">
                                        <label for="kategori">Kategori Laundry</label>
                                        <div class="position-relative">
                                            <select name="kategori" id="kategori"
                                                class="form-control @error('kategori') is-invalid @enderror" required>
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach (collect($pakets)->pluck('kategori')->unique() as $kategori)
                                                    <option value="{{ $kategori }}"
                                                        {{ old('kategori') == $kategori ? 'selected' : '' }}>
                                                        {{ $kategori }}
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

                                <div class="col-lg-6 col-xl-6 col-12">
                                    <div class="form-group">
                                        <label for="kuota">Jumlah Kuota</label>
                                        <div class="position-relative">
                                            <select name="kuota" id="kuota"
                                                class="form-control @error('kuota') is-invalid @enderror" required>
                                                <option value="">-- Pilih Jumlah Kuota --</option>
                                            </select>
                                            @error('kuota')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-6 col-12">
                                    <div class="form-group">
                                        <label for="harga">Harga</label>
                                        <div class="position-relative">
                                            <input type="number" name="harga" id="harga"
                                                class="form-control @error('harga') is-invalid @enderror"
                                                value="{{ old('harga') }}" min="0" required>
                                            @error('harga')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-6 col-12">
                                    <div class="form-group">
                                        <label for="voucher_select">Pilih Voucher Diskon</label>
                                        <div class="position-relative">
                                            <select id="voucher_select" class="form-control">
                                                <option value="0" data-nominal="0">-- Tanpa Voucher --</option>
                                                @foreach($diskons as $diskon)
                                                    <option value="{{ $diskon->id }}" data-nominal="{{ (int)$diskon->nominal }}">{{ $diskon->kode }} - (Rp. {{ number_format($diskon->nominal, 0, ',', '.') }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-6 col-12">
                                    <div class="form-group">
                                        <label for="diskon">Diskon</label>
                                        <div class="position-relative">
                                            <input type="number" name="diskon" id="diskon"
                                                class="form-control @error('diskon') is-invalid @enderror"
                                                value="{{ old('diskon', 0) }}" min="0">
                                            @error('diskon')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-xl-12 col-12">
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <div class="position-relative">
                                            <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                                rows="3">{{ old('keterangan') }}</textarea>
                                            @error('keterangan')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('kuota.index') }}" class="btn btn-danger">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            const pakets = @json($pakets);
            const oldKategori = "{{ old('kategori') }}";
            const oldKuota = "{{ old('kuota') }}";

            function populateKuota(kategoriVal, selectedKuotaVal) {
                const kuotaSelect = $('#kuota');
                kuotaSelect.html('<option value="">-- Pilih Jumlah Kuota --</option>');

                if (kategoriVal) {
                    const filtered = pakets.filter(p => p.kategori === kategoriVal);
                    filtered.forEach(p => {
                        const isSelected = selectedKuotaVal == p.kg ? 'selected' : '';
                        kuotaSelect.append(`<option value="${p.kg}" data-harga="${p.harga}" ${isSelected}>${parseFloat(p.kg)} kg</option>`);
                    });
                }
            }

            $('#kategori').on('change', function() {
                const kategoriVal = $(this).val();
                populateKuota(kategoriVal, '');
                $('#harga').val('');
            });

            $('#kuota').on('change', function() {
                const selectedOpt = $(this).find('option:selected');
                const harga = selectedOpt.data('harga');
                if (harga !== undefined && $(this).val() !== '') {
                    $('#harga').val(Math.round(harga));
                } else {
                    $('#harga').val('');
                }
            });

            // Handle old values if validation failed
            if (oldKategori) {
                populateKuota(oldKategori, oldKuota);
                if (oldKuota) {
                    const matchedPaket = pakets.find(p => p.kategori === oldKategori && p.kg == oldKuota);
                    if (matchedPaket) {
                        $('#harga').val(Math.round(matchedPaket.harga));
                    }
                }
            }
            // Voucher select handler
            $('#voucher_select').on('change', function() {
                const nominal = $(this).find('option:selected').data('nominal');
                $('#diskon').val(nominal);
            });
        });
    </script>
@endsection
