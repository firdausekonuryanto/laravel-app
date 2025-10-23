@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Manajemen Pemasok (Supplier)</h2>
                </div>
                <div class="pull-right mb-3">
                    <a class="btn btn-success" href="{{ route('suppliers.create') }}"> Tambah Pemasok Baru</a>
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
                    <th>Nama Pemasok</th>
                    <th>Kontak (Telp)</th>
                    <th>Alamat</th>
                    <th width="200px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suppliers as $supplier)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->contact }}</td>
                        <td>{{ Str::limit($supplier->address, 50) }}</td>
                        <td>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
                                <a class="btn btn-info btn-sm"
                                    href="{{ route('suppliers.show', $supplier->id) }}">Detail</a>
                                <a class="btn btn-primary btn-sm"
                                    href="{{ route('suppliers.edit', $supplier->id) }}">Edit</a>

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pemasok: {{ $supplier->name }}?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data pemasok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $suppliers->links() }}
        </div>
    </div>
@endsection
