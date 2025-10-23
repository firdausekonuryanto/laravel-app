<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Events\ProductCreated;
use App\Models\ProductCategory;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'creator'])
            ->orderBy('id', 'desc')
            ->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        $suppliers = Supplier::all();

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'name' => 'required|max:255',
            'sku' => 'required|unique:products,sku|max:255',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'unit' => 'nullable|max:50',
        ]);

        $data = $request->all();
        $data['created_by'] = 1;

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
