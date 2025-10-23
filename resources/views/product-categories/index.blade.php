@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Manajemen Kategori Produk</h2>
                </div>
                <div class="pull-right mb-3">
                    <a class="btn btn-success" href="{{ route('categories.create') }}"> Tambah Kategori Baru</a>
                </div>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success mt-3">
                <p>{{ $message }}</p>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger mt-3">
                <p>{{ $message }}</p>
            </div>
        @endif

        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th width="200px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ Str::limit($category->description, 50) }}</td>
                        <td>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                <a class="btn btn-info btn-sm"
                                    href="{{ route('categories.show', $category->id) }}">Detail</a>
                                <a class="btn btn-primary btn-sm"
                                    href="{{ route('categories.edit', $category->id) }}">Edit</a>

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus kategori: {{ $category->name }}? Ini akan mempengaruhi produk yang terkait.')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
