<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentMethodsController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionDetailsController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\LoginController;
use App\Models\Supplier;
use App\Models\Transactions;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- Authentication Routes ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Authenticated Routes Group ---
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Product Categories
    Route::get('categories/data', [ProductCategoryController::class, 'getData'])->name('categories.data');
    Route::resource('categories', ProductCategoryController::class);

    // Suppliers
    Route::get('suppliers/data', [SupplierController::class, 'getData'])->name('suppliers.data');
    Route::resource('suppliers', SupplierController::class);

    // Customers
    Route::get('customers/data', [CustomersController::class, 'getData'])->name('customers.data');
    Route::resource('customers', CustomersController::class);

    // Payment Methods
    Route::resource('payment-methods', PaymentMethodsController::class);

    // Products
    Route::get('products/data', [ProductController::class, 'getData'])->name('products.data');
    Route::resource('products', ProductController::class);

    // Transactions
    Route::get('/transactions/{id}/print', [TransactionsController::class, 'print'])->name('transactions.print');
    Route::get('transactions/data', [TransactionsController::class, 'getData'])->name('transactions.data');
    Route::get('transactions/data-product', [TransactionsController::class, 'getDataProduct'])->name('transactions.dataProduct');
    Route::resource('transactions', TransactionsController::class);

    // Transaction Details
    Route::resource('transaction-details', TransactionDetailsController::class);
});

// --- Fallback Route (HARUS diletakkan paling akhir) ---
/*
| Rute ini akan menangkap semua permintaan yang tidak cocok dengan rute
| yang didefinisikan di atasnya.
*/
Route::fallback(function () {
    // Cek apakah user sudah login
    
    if (Auth::check()) {
        // Jika sudah login, redirect ke dashboard
        return redirect()->route('dashboard.index');
    } else {
        // Jika belum login, redirect ke halaman login
        return redirect()->route('login');
    }
});
