<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard Admn';
        return view('dashboard.index', compact('title'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('transactions')
                ->join('users', 'transactions.user_id', '=', 'users.id')
                ->leftJoin('customers', 'transactions.customer_id', '=', 'customers.id')
                ->leftJoin('payment_methods', 'transactions.payment_method_id', '=', 'payment_methods.id')
                ->select(
                    'transactions.id', // Pastikan ID ada untuk tombol aksi
                    'transactions.invoice_number',
                    'customers.name as customer_name',
                    'users.name as user_name',
                    'transactions.total_qty',
                    'transactions.grand_total',
                    'transactions.status',
                )
                ->orderBy('transactions.id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn() // Menambahkan kolom penomoran (opsional)
                ->editColumn('grand_total', function ($row) {
                    // Format grand_total menjadi mata uang
                    return number_format($row->grand_total, 0, ',', '.');
                })
                ->addColumn('action', function ($row) {
                    // Membuat tombol aksi (Show, Edit, Delete)
                    $showUrl = route('transactions.show', $row->id);
                    $editUrl = route('transactions.edit', $row->id);
                    $deleteUrl = route('transactions.destroy', $row->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    $btn = "<a href='{$showUrl}' class='btn btn-info btn-sm'>Show</a> ";
                    return $btn;
                })
                ->rawColumns(['action']) // Pastikan kolom 'action' diizinkan mengandung HTML
                ->make(true);
        }
    }
}
