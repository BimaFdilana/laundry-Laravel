@extends('layouts.backend')
@section('title', 'Admin - Data Gift')
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
                    <h4 class="card-title"> Data Gift
                        <a href="{{ route('gift.create') }}" class="btn btn-primary">Tambah</a>
                    </h4>
                    <div class="table-responsive m-t-0">
                        <table id="myTable" class="table display table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Nama Gift</th>
                                    <th>Keterangan</th>
                                    <th>Berlaku Sampai</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gifts as $key => $gift)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $gift->user->name ?? 'Tidak ditemukan' }}</td>
                                        <td>{{ $gift->gift }}</td>
                                        <td>{{ $gift->keterangan }}</td>
                                        <td>
                                            @if ($gift->expired_at)
                                                {{ \Carbon\Carbon::parse($gift->expired_at)->format('d-m-Y') }}
                                            @else
                                                <span class="text-muted">Tidak ditentukan</span>
                                            @endif
                                        </td>
                                        <td align="center">
                                            <a href="{{ route('gift.edit', $gift->id) }}" class="btn btn-sm btn-warning"
                                                style="color:white">Edit</a>
                                            <form action="{{ route('gift.destroy', $gift->id) }}" method="POST"
                                                style="display:inline-block;"
                                                onsubmit="return confirm('Yakin ingin menghapus gift ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
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
