@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
        }

        table.dataTable {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border-color: #333;
        }

        table.dataTable thead th {
            background-color: #222;
            color: #fff;
        }

        table.dataTable tbody tr:nth-child(even) {
            background-color: #1b1b1b;
        }

        table.dataTable tbody tr:nth-child(odd) {
            background-color: #141414;
        }

        table.dataTable tbody tr:hover {
            background-color: #2a2a2a;
        }

        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            background-color: #1b1b1b;
            color: #e0e0e0;
            border: 1px solid #333;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #2a2a2a;
            color: #e0e0e0 !important;
            border: 1px solid #333;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #007bff !important;
            color: #fff !important;
        }

        .btn {
            border: none;
        }

        .btn-info {
            background-color: #007bff;
            color: white;
        }

        .btn-warning {
            background-color: #f39c12;
            color: white;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }
    </style>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-light">Manajemen Pelanggan (Customer)</h2>
            <a class="btn btn-success" href="{{ route('customers.create') }}">Tambah Pelanggan Baru</a>
        </div>

        <table class="table table-bordered table-dark table-striped yajra-datatable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>Kontak</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th width="200px">Aksi</th>
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
                ajax: "{{ route('customers.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'customers.name'
                    },
                    {
                        data: 'phone',
                        name: 'customers.phone'
                    },
                    {
                        data: 'email',
                        name: 'customers.email'
                    },
                    {
                        data: 'address',
                        name: 'customers.address'
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
