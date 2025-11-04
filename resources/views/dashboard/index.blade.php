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

        div.dataTables_length {
            float: left;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        div.dataTables_length label {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #ddd;
        }

        div.dataTables_length select {
            background-color: #1e1e1e;
            color: #fff;
            border: 1px solid #333;
            border-radius: 6px;
            padding: 3px 6px;
        }

        div.dataTables_filter {
            float: right;
            text-align: right;
        }

        div.dataTables_filter input {
            background-color: #1e1e1e;
            color: #fff;
            border: 1px solid #333;
            border-radius: 6px;
            padding: 4px 8px;
        }

        div.dataTables_wrapper div.dataTables_length,
        div.dataTables_wrapper div.dataTables_filter {
            margin-bottom: 1rem;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="">
        <div class="">
            {{-- Revenue Summary --}}
            <div class="row">
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-10">
                                    <div class="d-flex align-items-center align-self-start">
                                        <h4 class="mb-0">Rp. {{ number_format($todayRevenue, 0, ',', '.') }}</h4>
                                        <p class="text-success ml-2 mb-0 font-weight-medium">@ {{ $todayTrans }} Trans
                                        </p>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="icon icon-box-success">
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
                                        <h4 class="mb-0">Rp. {{ number_format($monthRevenue, 0, ',', '.') }}</h4>
                                        <p class="text-success ml-2 mb-0 font-weight-medium">@ {{ $monthTrans }} Trans</p>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="icon icon-box-success">
                                        <span class="mdi mdi-arrow-top-right icon-item"></span>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal">Revenue This Month</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <div class="d-flex align-items-center align-self-start">
                                        <h4 class="mb-0">Rp. {{ number_format($yearRevenue, 0, ',', '.') }}</h4>
                                        <p class="text-danger ml-2 mb-0 font-weight-medium">@ {{ $yearTrans }} Trans</p>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="icon icon-box-danger">
                                        <span class="mdi mdi-arrow-bottom-left icon-item"></span>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal">Revenue This Year</h6>
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
                            <h6 class="text-muted font-weight-normal">Expense Current</h6>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 📊 Revenue Charts --}}
            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Monthly Revenue ({{ now()->year }})</h4>
                            <canvas id="monthlyRevenueChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Daily Revenue (Last 30 Days)</h4>
                            <canvas id="dailyRevenueChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <p>Role: {{ implode(', ', auth()->user()->getRoleNames()->toArray()) }}</p>
            <ul>
                @foreach (auth()->user()->getAllPermissions() as $perm)
                    <li>{{ $perm->name }}</li>
                @endforeach
            </ul> --}}

            {{-- 🧾 Last Transactions Table --}}
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
                                            <th width="150px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        (function() {
            // Aktifkan log hanya di mode development
            const isDev = "{{ app()->environment('local') }}" === "1";
            Pusher.logToConsole = isDev;

            // Inisialisasi Pusher
            const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
                forceTLS: true,
            });

            // Subscribe ke channel 'transactions'
            const channel = pusher.subscribe("transactions");

            // Event listener: transaksi baru
            channel.bind("transaction.created", function(data) {
                const transaction = data.transaction;
                if (!transaction) return;

                console.log("💰 Transaksi baru diterima:", transaction);

                // 🔔 Tampilkan notifikasi Toast SweetAlert2
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 7000,
                    timerProgressBar: true,
                    background: "#1e1e1e",
                    color: "#fff",
                    customClass: {
                        popup: "shadow-lg rounded-3xl",
                    },
                });

                // Format waktu lokal
                const time = new Date(transaction.created_at).toLocaleTimeString("id-ID");
                const amount = parseInt(transaction.total_price || 0).toLocaleString("id-ID");

                Toast.fire({
                    icon: "success",
                    title: "💰 Transaksi Baru",
                    html: `
                    <b>Kasir:</b> ${transaction.user?.name || "Tidak diketahui"}<br>
                    <b>Nomor Invoice:</b> ${transaction.invoice_number || "-"}<br>
                    <b>Jumlah:</b> Rp ${amount}<br>
                    <b>Waktu:</b> ${time}
                `,
                });

                // 🔄 Reload DataTable jika ada
                const dataTable = $(".yajra-datatable").DataTable?.();
                if (dataTable) dataTable.ajax.reload();
            });

            // Log status koneksi
            pusher.connection.bind("state_change", function(states) {
                console.log(`Pusher status: ${states.previous} -> ${states.current}`);
            });

            // Log error koneksi
            pusher.connection.bind("error", function(err) {
                console.error("Pusher error:", err);
            });
        })();
    </script>
@endsection

@push('scripts')
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script type="text/javascript">
        // --- Monthly Revenue Chart ---
        const ctxMonthly = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(ctxMonthly, {
            type: 'bar',
            data: {
                labels: @json($labelsMonthly),
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: @json($totalsMonthly),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // --- Daily Revenue Chart ---
        const ctxDaily = document.getElementById('dailyRevenueChart').getContext('2d');
        new Chart(ctxDaily, {
            type: 'line',
            data: {
                labels: @json($labelsDaily),
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: @json($totalsDaily),
                    borderColor: 'rgba(255, 206, 86, 1)',
                    backgroundColor: 'rgba(255, 206, 86, 0.3)',
                    tension: 0.3,
                    fill: true,
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // --- DataTable ---
        $(function() {
            $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('dashboard.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
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
