@extends('layouts.app')

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

        {{-- Tabel Daftar Produk --}}
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th width="200px">Aksi</th> {{-- Kurangi lebar agar lebih ringkas --}}
                </tr>
            </thead>
            <tbody>
                {{-- Pastikan data produk dikirim dari controller dengan eager loading:
                    $products = Product::with(['category', 'supplier'])->paginate(10);
                --}}
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $product->sku }}</strong></td>
                        <td>{{ $product->name }}</td>
                        {{-- Mengakses relasi Category --}}
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        {{-- Menampilkan Harga --}}
                        <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                        {{-- Menampilkan Stok --}}
                        <td>
                            <span class="badge {{ $product->stock > 10 ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        {{-- Menampilkan Satuan --}}
                        <td>{{ $product->unit }}</td>
                        <td>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                <a class="btn btn-info btn-sm" href="{{ route('products.show', $product->id) }}">Detail</a>
                                <a class="btn btn-primary btn-sm"
                                    href="{{ route('products.edit', $product->id) }}">Edit</a>

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus produk {{ $product->name }}?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
