@extends('layouts.app')

@section('content')
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
