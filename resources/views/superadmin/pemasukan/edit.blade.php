@extends('layouts.backend')
@section('title', 'Form Edit Pemasukan')
@section('header', 'Edit Pemasukan')
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Pemasukan</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('pemasukan.update', $pemasukan->id) }}" method="POST" class="form form-vertical">
                        @csrf
                        @method('PUT')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <input type="date" id="tanggal" name="tanggal"
                                            class="form-control @error('tanggal') is-invalid @enderror"
                                            value="{{ old('tanggal', \Carbon\Carbon::parse($pemasukan->tanggal)->format('Y-m-d')) }}">
                                        @error('tanggal')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="pemasukan">Nama Pemasukan</label>
                                        <input type="text" id="pemasukan" name="pemasukan"
                                            class="form-control @error('pemasukan') is-invalid @enderror"
                                            value="{{ old('pemasukan', $pemasukan->pemasukan) }}"
                                            placeholder="Contoh: Beli Deterjen">
                                        @error('pemasukan')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="kategori">Kategori</label>
                                        <input type="text" id="kategori" name="kategori"
                                            class="form-control @error('kategori') is-invalid @enderror"
                                            value="{{ old('kategori', $pemasukan->kategori) }}"
                                            placeholder="Contoh: Bahan, Perlengkapan, dll">
                                        @error('kategori')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="harga">Harga Satuan</label>
                                        <input type="number" id="harga" name="harga" step="0.01" min="0"
                                            class="form-control @error('harga') is-invalid @enderror"
                                            value="{{ old('harga', $pemasukan->harga) }}">
                                        @error('harga')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="jumlah">Jumlah</label>
                                        <input type="number" id="jumlah" name="jumlah" min="1"
                                            class="form-control @error('jumlah') is-invalid @enderror"
                                            value="{{ old('jumlah', $pemasukan->jumlah) }}">
                                        @error('jumlah')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="total">Total</label>
                                        <input type="text" id="total" name="total" class="form-control"
                                            value="{{ old('total', $pemasukan->total) }}">
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea id="keterangan" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                            rows="3" placeholder="Tambahkan keterangan tambahan jika ada">{{ old('keterangan', $pemasukan->keterangan) }}</textarea>
                                        @error('keterangan')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary mr-1">Update</button>
                                    <a href="{{ route('pemasukan.index') }}" class="btn btn-outline-warning">Batal</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const hargaInput = document.getElementById('harga');
            const jumlahInput = document.getElementById('jumlah');
            const totalDisplay = document.getElementById('total');

            function updateTotal() {
                const harga = parseFloat(hargaInput.value) || 0;
                const jumlah = parseInt(jumlahInput.value) || 0;
                const total = harga * jumlah;
                totalDisplay.value = 'Rp. ' + total.toLocaleString('id-ID');
            }

            hargaInput.addEventListener('input', updateTotal);
            jumlahInput.addEventListener('input', updateTotal);
        </script>
    @endpush
@endsection
