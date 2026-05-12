@extends('layouts.backend')
@section('title', 'Super Admin - Data Kategori')
@section('header', 'Data Kategori')
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
                    <h4 class="card-title"> Data Kategori
                        <a href="{{ route('kategori.create') }}" class="btn btn-primary">Tambah</a>
                    </h4>

                    <div class="table-responsive">
                        <table class="table zero-configuration">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Kategori</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($kategori as $item)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>
                                            <form action="{{ route('kategori.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus item ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('kategori.edit', $item->id) }}"
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
