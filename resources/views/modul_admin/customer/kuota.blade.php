@extends('layouts.backend')
@section('title', 'Admin - Data Customer')
@section('header', 'Data Customer')

@section('styles')
    <!-- Tidak ada Leaflet CSS karena fitur lokasi dihapus -->
@endsection

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @elseif($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <!-- Tabel Kuota Laundry Tanpa Pengelompokan Kategori -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Kuota Laundry Customer
                        <a href="{{ route('kuota.create') }}" class="btn btn-primary">Tambah</a>
                    </h4>
                    <div class="table-responsive">
                        <table id="myTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Customer</th>
                                    <th>Kategori</th>
                                    <th>Kuota</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($flatKuota as $index => $data)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $data['customer']->name }}</td>
                                        <td>{{ $data['kuota']->kategori }}</td>
                                        <td>{{ $data['kuota']->kuota }} kg</td>
                                        <td>
                                            <a href="{{ route('kuota.edit', $data['kuota']->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No data available in table</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
@endsection
