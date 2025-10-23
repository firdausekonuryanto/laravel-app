@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Transactions</h2>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary mb-3">Add Transaction</a>
        {{-- <a href="{{ route('transactions.run') }}" class="btn btn-warning mb-3">Generate Dummy Data</a> --}}

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>User</th>
                    <th>Total Qty</th>
                    <th>Grand Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $t)
                    <tr>
                        <td>{{ $t->invoice_number }}</td>
                        <td>{{ $t->customer_name ?? '-' }}</td>
                        <td>{{ $t->user_name }}</td>
                        <td>{{ $t->total_qty }}</td>
                        <td>{{ number_format($t->grand_total) }}</td>
                        <td>{{ $t->status }}</td>
                        <td>
                            <a href="{{ route('transactions.show', $t->id) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route('transactions.edit', $t->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('transactions.destroy', $t->id) }}" method="POST"
                                style="display:inline-block">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this transaction?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
