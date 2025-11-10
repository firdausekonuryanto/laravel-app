<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:read-dashboard')->only('index');
    }

     public function index()
    {
        $title = 'Dashboard Admin';

        // ======================
        // 💰 Pendapatan & Transaksi
        // ======================
        $todayRevenue = Transactions::whereDate('created_at', today())->sum('grand_total');
        $todayTrans   = Transactions::whereDate('created_at', today())->count();

        $monthRevenue = Transactions::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('grand_total');
        $monthTrans   = Transactions::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $yearRevenue = Transactions::whereYear('created_at', now()->year)->sum('grand_total');
        $yearTrans   = Transactions::whereYear('created_at', now()->year)->count();

        // ======================
        // 📊 Grafik 1: Pendapatan per bulan (12 bulan)
        // ======================
        $monthlyRevenue = Transactions::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(grand_total) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get();

        $labelsMonthly = collect(range(1, 12))
            ->map(fn($m) => Carbon::create()->month($m)->translatedFormat('F'));
        $totalsMonthly = $labelsMonthly->map(function ($label, $index) use ($monthlyRevenue) {
            $data = $monthlyRevenue->firstWhere('month', $index + 1);
            return $data ? (float) $data->total : 0;
        });

        // ======================
        // 📈 Grafik 2: Pendapatan per hari (30 hari terakhir)
        // ======================
        $dailyRevenue = Transactions::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(grand_total) as total')
            )
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $labelsDaily = $dailyRevenue->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->translatedFormat('d M'));
        $totalsDaily = $dailyRevenue->pluck('total');

        // ======================
        // 🏆 Top 10 Produk Terlaris
        // ======================
        $topProducts = DB::table('transaction_details as td')
            ->join('transactions as t', 'td.transaction_id', '=', 't.id')
            ->join('products as p', 'td.product_id', '=', 'p.id')
            ->join('product_categories as c', 'p.category_id', '=', 'c.id')
            ->select(
                'p.name as product_name',
                'c.name as category_name',
                DB::raw('SUM(td.quantity) as total_sold')
            )
            ->where('t.status', 'paid')
            ->groupBy('p.id', 'p.name', 'c.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'title',
            'todayRevenue', 'todayTrans',
            'monthRevenue', 'monthTrans',
            'yearRevenue', 'yearTrans',
            'labelsMonthly', 'totalsMonthly',
            'labelsDaily', 'totalsDaily',
            'topProducts'
        ));
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
