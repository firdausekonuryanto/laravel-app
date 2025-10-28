@extends('layouts.app')

@section('content')
    <style>
        /* Ubah warna dasar tabel */
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
        <h2>Transactions</h2>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary mb-3">Add Transaction</a>
        {{-- <a href="{{ route('transactions.run') }}" class="btn btn-warning mb-3">Generate Dummy Data</a> --}}

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- BERI ID PADA TABEL --}}
        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>User</th>
                    <th>Total Qty</th>
                    <th>Grand Total</th>
                    <th>Status</th>
                    <th width="150px">Action</th> {{-- Tambahkan lebar untuk kolom Action --}}
                </tr>
            </thead>
            <tbody>
                {{-- Data akan diisi oleh Datatables, hapus loop @foreach --}}
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    {{-- Pastikan layout Anda memiliki @stack('scripts') di akhir body atau sebelum penutup </body> --}}

    <script type="text/javascript">
        $(function() {

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('transactions.data') }}", // Panggil route getData
                columns: [
                    // Kolom yang akan dirender oleh Datatables. Urutan harus sesuai <th> di atas.
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, // Untuk kolom No (dari addIndexColumn)
                    {
                        data: 'invoice_number',
                        name: 'transactions.invoice_number'
                    },
                    {
                        data: 'customer_name',
                        name: 'customers.name'
                    },
                    {
                        data: 'user_name',
                        name: 'users.name'
                    },
                    {
                        data: 'total_qty',
                        name: 'transactions.total_qty'
                    },
                    {
                        data: 'grand_total',
                        name: 'transactions.grand_total'
                    },
                    {
                        data: 'status',
                        name: 'transactions.status'
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
