<?php 

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Customers;
use App\Models\PaymentMethod;
use App\Models\PaymentMethods;
use App\Models\User;

class SyncController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'products' => Product::all(),
                'categories' => ProductCategory::all(),
                'suppliers' => Supplier::all(),
                'customers' => Customers::all(),
                'payment_methods' => PaymentMethods::all(),
                'users' => User::select('id', 'name', 'role')->get(),
            ]
        ]);
    }
}
