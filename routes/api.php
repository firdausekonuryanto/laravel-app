<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\TransactionApiController;
use App\Http\Controllers\Api\SyncController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/products', [ProductApiController::class, 'index']);
Route::get('/transactions', [TransactionApiController::class, 'index']);
Route::get('/transactions/create', [TransactionApiController::class, 'create']);
Route::get('/transactions/store', [TransactionApiController::class, 'store']);
Route::post('/transactions', [TransactionApiController::class, 'store']);

Route::get('/sync-data', [SyncController::class, 'index']);