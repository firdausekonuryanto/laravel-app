@extends('layouts.app')

@section('content')
    <div class="">
        <h2>Manajemen Pemasok (Supplier)</h2>
        <a href="{{ route('suppliers.create') }}" class="btn btn-success mb-3">Tambah Pemasok Baru</a>

        @if (session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pemasok</th>
                    <th>Kontak (Telp)</th>
                    <th>Alamat</th>
                    <th width="150px">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(function() {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('suppliers.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'suppliers.name'
                    },
                    {
                        data: 'contact',
                        name: 'suppliers.contact'
                    },
                    {
                        data: 'address',
                        name: 'suppliers.address'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endpush
