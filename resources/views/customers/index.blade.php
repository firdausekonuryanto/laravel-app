@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Manajemen Pelanggan (Customer)</h2>
                </div>
                <div class="pull-right mb-3">
                    <a class="btn btn-success" href="{{ route('customers.create') }}"> Tambah Pelanggan Baru</a>
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
                    <th>Nama Pelanggan</th>
                    <th>Kontak (Telp)</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th width="200px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->email ?? '-' }}</td>
                        <td>{{ Str::limit($customer->address, 50) }}</td>
                        <td>
                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST">
                                <a class="btn btn-info btn-sm"
                                    href="{{ route('customers.show', $customer->id) }}">Detail</a>
                                <a class="btn btn-primary btn-sm"
                                    href="{{ route('customers.edit', $customer->id) }}">Edit</a>

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan: {{ $customer->name }}?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data pelanggan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
