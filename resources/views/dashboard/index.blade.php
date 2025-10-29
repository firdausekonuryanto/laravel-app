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
        <div class="">
            <div class="row">
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-10">
                                    <div class="d-flex align-items-center align-self-start">
                                        <h4 class="mb-0">Rp. 452.340</h4>
                                        <p class="text-success ml-2 mb-0 font-weight-medium">@45 Trans</p>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="icon icon-box-success ">
                                        <span class="mdi mdi-arrow-top-right icon-item"></span>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal">Revenue Today</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-10">
                                    <div class="d-flex align-items-center align-self-start">
                                        <h4 class="mb-0">Rp. 5.750.340</h4>
                                        <p class="text-success ml-2 mb-0 font-weight-medium">@145 Tans</p>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="icon icon-box-success">
                                        <span class="mdi mdi-arrow-top-right icon-item"></span>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal">Revenue In Month</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <div class="d-flex align-items-center align-self-start">
                                        <h4 class="mb-0">Rp. 85.000.000</h4>
                                        <p class="text-danger ml-2 mb-0 font-weight-medium">345 trans</p>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="icon icon-box-danger">
                                        <span class="mdi mdi-arrow-bottom-left icon-item"></span>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal">Revenue Year On Year</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">$31.53</h3>
                                        <p class="text-success ml-2 mb-0 font-weight-medium">+3.5%</p>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="icon icon-box-success ">
                                        <span class="mdi mdi-arrow-top-right icon-item"></span>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal">Expense current</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h5>Total Categories</h5>
                            <div class="row">
                                <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                    <div class="d-flex d-sm-block d-md-flex align-items-center">
                                        <h2 class="mb-0">6</h2>
                                        <p class="text-success ml-2 mb-0 font-weight-medium">+3.5%</p>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">11.38% Since last month</h6>
                                </div>
                                <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                    <i class="icon-lg mdi mdi-codepen text-primary ml-auto"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h5>Total Fix Customer</h5>
                            <div class="row">
                                <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                    <div class="d-flex d-sm-block d-md-flex align-items-center">
                                        <h2 class="mb-0">140</h2>
                                        <p class="text-success ml-2 mb-0 font-weight-medium">+8.3%</p>
                                    </div>
                                    <h6 class="text-muted font-weight-normal"> 9.61% Since last month</h6>
                                </div>
                                <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                    <i class="icon-lg mdi mdi-wallet-travel text-danger ml-auto"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h5>Total Supplier</h5>
                            <div class="row">
                                <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                    <div class="d-flex d-sm-block d-md-flex align-items-center">
                                        <h2 class="mb-0">3</h2>
                                        <p class="text-danger ml-2 mb-0 font-weight-medium">-2.1% </p>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">2.27% Since last month</h6>
                                </div>
                                <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                    <i class="icon-lg mdi mdi-monitor text-success ml-auto"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Last Transactions</h4>
                            <div class="table-responsive">
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
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->

        <!-- partial -->
    </div>
@endsection


@push('scripts')
    <script type="text/javascript">
        $(function() {

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('dashboard.data') }}", // Panggil route getData
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
                ],

            });

        });
    </script>
@endpush
