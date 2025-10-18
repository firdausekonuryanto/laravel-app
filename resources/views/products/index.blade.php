@extends('layouts.app') // Asumsikan Anda menggunakan layout

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Daftar Produk</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-success" href="{{ route('products.create') }}"> Tambah Produk Baru</a>
                </div>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success mt-3">
                <p>{{ $message }}</p>
            </div>
        @endif

        <table class="table table-bordered mt-3">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok</th>
                <th width="280px">Aksi</th>
            </tr>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->name }}</td>
                    <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST">

                            <a class="btn btn-info btn-sm" href="{{ route('products.show', $product->id) }}">Detail</a>

                            <a class="btn btn-primary btn-sm" href="{{ route('products.edit', $product->id) }}">Edit</a>

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
