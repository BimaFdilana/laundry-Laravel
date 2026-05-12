@extends('layouts.backend')
@section('title', 'Super Admin - Data Inventaris')
@section('header', 'Data Inventaris')
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
                    <h4 class="card-title"> Data Inventaris
                        <a href="{{ route('inventaris.create') }}" class="btn btn-primary">Tambah</a>
                    </h4>

                    <div class="table-responsive">
                        <table class="table zero-configuration">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Barang</th>
                                    <th>Jenis Inventaris</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Satuan</th>
                                    <th>Harga</th>
                                    <th>Kondisi</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($inventaris as $item)
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>{{ $item->jenis }}</td>
                                        <td>{{ $item->kategori->nama }}</td>
                                        <td>{{ $item->stok }}</td>
                                        <td>{{ $item->satuan }}</td>
                                        <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td>{{ $item->kondisi }}</td>
                                        <td>
                                            <form action="{{ route('inventaris.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus item ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('inventaris.edit', $item->id) }}"
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
