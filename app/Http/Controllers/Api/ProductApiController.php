<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductApiController extends Controller
{
    public function index()
    {
        $products = DB::table('products')
            ->select('id', 'name', 'sku', 'price', 'stock', 'unit')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }
}
