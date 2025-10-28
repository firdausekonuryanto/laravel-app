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

    <div class="">
        <h2>Daftar Produk</h2>
        <a href="{{ route('products.create') }}" class="btn btn-success mb-3">Tambah Produk Baru</a>

        @if (session('success'))
            <div class="alert alert-success mt-3">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Satuan</th>
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
                ajax: "{{ route('products.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'sku',
                        name: 'products.sku'
                    },
                    {
                        data: 'name',
                        name: 'products.name'
                    },
                    {
                        data: 'category_name',
                        name: 'product_categories.name'
                    },
                    {
                        data: 'price',
                        name: 'products.price'
                    },
                    {
                        data: 'stock',
                        name: 'products.stock',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'unit',
                        name: 'products.unit'
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
