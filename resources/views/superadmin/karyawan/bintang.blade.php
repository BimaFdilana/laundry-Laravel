@extends('layouts.backend')

@section('title', 'Bintang Karyawan')
@section('header', 'Bintang Karyawan')
@section('style')
    <style>
        .star {
            font-size: 30px;
            cursor: pointer;
            color: gray;
            transition: color 0.2s;
        }

        .star.active {
            color: gold;
        }
    </style>
@endsection

@section('content')

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Bintang Karyawan</h4>
                <form action="{{ route('bintang.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="karyawan">Pilih Karyawan</label>
                        <select name="karyawan_id" id="karyawan" class="form-control" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach ($karyawan as $k)
                                <option value="{{ $k->id }}">{{ $k->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="bintang">Bintang</label>
                        <div id="star-rating" class="mt-1">
                            <i class="feather icon-star star" data-value="1"></i>
                            <i class="feather icon-star star" data-value="2"></i>
                            <i class="feather icon-star star" data-value="3"></i>
                            <i class="feather icon-star star" data-value="4"></i>
                            <i class="feather icon-star star" data-value="5"></i>
                        </div>
                        <input type="hidden" name="bintang" id="bintang" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-12 mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Bintang Karyawan</h4>
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Nama Karyawan</th>
                                <th>Bintang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bintangKaryawan as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                    <td>{{ $item->karyawan->name ?? 'Tidak Diketahui' }}</td>
                                    <td>{{ str_repeat('⭐', $item->bintang) }}</td>
                                    <td>
                                        <form action="{{ route('bintang.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title">Rata-rata Bintang Karyawan perbulan</h4>
                <div class="table-responsive">
                    <table id="myTable2" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Bulan</th>
                                <th>Nama Karyawan</th>
                                <th>Rata-rata Bintang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rataBintangPerBulanPerKaryawan as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $data->bulan)->format('F Y') }}</td>
                                    <td>{{ $data->karyawan->name ?? 'Tidak Diketahui' }}</td>
                                    <td>
                                        @php
                                            $fullStars = floor($data->rata_rata);
                                            $hasHalfStar = $data->rata_rata - $fullStars >= 0.5;
                                        @endphp

                                        {!! str_repeat('⭐', $fullStars) !!}
                                        @if ($hasHalfStar)
                                            ½
                                        @endif

                                        <span>({{ number_format($data->rata_rata, 2) }})</span>
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
        $(document).ready(function() {
            $('#myTable').DataTable();
        });

        $(document).ready(function() {
            $('#myTable2').DataTable();
        });

        document.addEventListener("DOMContentLoaded", function() {
            const stars = document.querySelectorAll(".star");
            const bintangInput = document.getElementById("bintang");

            stars.forEach(star => {
                star.addEventListener("click", function() {
                    let rating = this.getAttribute("data-value");
                    bintangInput.value = rating;

                    // Reset semua bintang jadi abu-abu
                    stars.forEach(s => s.classList.remove("active"));

                    // Ubah warna bintang sesuai rating yang dipilih
                    for (let i = 0; i < rating; i++) {
                        stars[i].classList.add("active");
                    }
                });
            });
        });
    </script>
@endsection
