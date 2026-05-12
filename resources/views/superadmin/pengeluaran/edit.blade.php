@extends('layouts.backend')
@section('title', 'Form Edit Pengeluaran')
@section('header', 'Edit Pengeluaran')
@section('content')
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Pengeluaran</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('pengeluaran.update', $pengeluaran->id) }}" method="POST"
                        class="form form-vertical">
                        @csrf
                        @method('PUT')
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <input type="date" id="tanggal" name="tanggal"
                                            class="form-control @error('tanggal') is-invalid @enderror"
                                            value="{{ old('tanggal', \Carbon\Carbon::parse($pengeluaran->tanggal)->format('Y-m-d')) }}">
                                        @error('tanggal')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="pengeluaran">Nama Pengeluaran</label>
                                        <input type="text" id="pengeluaran" name="pengeluaran"
                                            class="form-control @error('pengeluaran') is-invalid @enderror"
                                            value="{{ old('pengeluaran', $pengeluaran->pengeluaran) }}"
                                            placeholder="Contoh: Beli Deterjen">
                                        @error('pengeluaran')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="kategori">Kategori</label>
                                        <input type="text" id="kategori" name="kategori"
                                            class="form-control @error('kategori') is-invalid @enderror"
                                            value="{{ old('kategori', $pengeluaran->kategori) }}"
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
                                            value="{{ old('harga', $pengeluaran->harga) }}">
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
                                            value="{{ old('jumlah', $pengeluaran->jumlah) }}">
                                        @error('jumlah')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Total hanya untuk ditampilkan --}}
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="total">Total (Otomatis)</label>
                                        <input type="text" id="total" class="form-control" disabled
                                            value="Rp. {{ number_format($pengeluaran->harga * $pengeluaran->jumlah, 0, ',', '.') }}">
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea id="keterangan" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                            rows="3" placeholder="Tambahkan keterangan tambahan jika ada">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
                                        @error('keterangan')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary mr-1">Update</button>
                                    <a href="{{ route('pengeluaran.index') }}" class="btn btn-outline-warning">Batal</a>
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
