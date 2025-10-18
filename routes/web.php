<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// ... rute lainnya

Route::resource('products', ProductController::class);