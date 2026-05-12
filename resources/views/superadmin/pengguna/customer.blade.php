@extends('layouts.backend')
@section('title', 'Super Admin - Data Customer')
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
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"> Data Customer
                        <a href="{{ route('supercustomer.create') }}" class="btn btn-primary">Tambah</a>
                    </h4>

                    <div class="table-responsive">
                        <table class="table zero-configuration">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Customer</th>
                                    <th>Inisial</th>
                                    <th>Email</th>
                                    <th>Alamat</th>
                                    <th>No Telp</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Status</th>
                                    <th>Tgl Bergabung</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($customer as $item)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->customer->inisial ?? '-' }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->alamat }}</td>
                                        <td>{{ $item->no_telp }}</td>
                                        <td>
                                            @if ($item->kelamin == 'Laki-laki')
                                                <span class="label label-success">Laki-laki</span>
                                            @else
                                                <span class="label label-info">Perempuan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->status == 'Active')
                                                <span class="label label-success">Aktif</span>
                                            @else
                                                <span class="label label-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td data-order="{{ $item->created_at }}">{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                        <td>
                                            <form action="{{ route('supercustomer.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('supercustomer.show', $item->id) }}"
                                                    class="btn btn-sm btn-info">Show</a>
                                                <a href="{{ route('supercustomer.edit', $item->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php $no++; ?>
                                @endforeach
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
