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
Route::resource('categories', ProductCategoryController::class);
Route::resource('suppliers', SupplierController::class);
Route::resource('customers', CustomersController::class);
Route::resource('payment-methods', PaymentMethodsController::class);
Route::resource('products', ProductController::class);
Route::resource('transactions', TransactionsController::class);
Route::resource('transaction-details', TransactionDetailsController::class);