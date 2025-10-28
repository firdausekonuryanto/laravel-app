<?php

use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentMethodsController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionDetailsController;
use App\Http\Controllers\TransactionsController;
use App\Models\Supplier;
use App\Models\Transactions;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('categories/data', [ProductCategoryController::class, 'getData'])->name('categories.data');
Route::resource('categories', ProductCategoryController::class);
Route::get('suppliers/data', [SupplierController::class, 'getData'])->name('suppliers.data');
Route::resource('suppliers', SupplierController::class);
Route::get('customers/data', [CustomersController::class, 'getData'])->name('customers.data');
Route::resource('customers', CustomersController::class);
Route::resource('payment-methods', PaymentMethodsController::class);
Route::get('products/data', [ProductController::class, 'getData'])->name('products.data');
Route::resource('products', ProductController::class);
Route::get('transactions/data', [TransactionsController::class, 'getData'])->name('transactions.data');
Route::get('transactions/data-product', [TransactionsController::class, 'getDataProduct'])->name('transactions.dataProduct');
Route::resource('transactions', TransactionsController::class);
Route::resource('transaction-details', TransactionDetailsController::class);