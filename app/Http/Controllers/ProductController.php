<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Events\ProductCreated;
use App\Models\ProductCategory;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create products')->only('create, store');
        $this->middleware('permission:read products')->only('index, getData');
        $this->middleware('permission:update products')->only('edit, update');
        $this->middleware('permission:delete products')->only('destroy');
    }

    public function index()
    {
        return view('products.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('products')->leftJoin('product_categories', 'products.category_id', '=', 'product_categories.id')->leftJoin('users', 'products.created_by', '=', 'users.id')->select('products.id', 'products.sku', 'products.name', 'product_categories.name as category_name', 'products.price', 'products.stock', 'products.unit')->orderBy('products.id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('price', function ($row) {
                    return 'Rp' . number_format($row->price, 0, ',', '.');
                })
                ->editColumn('stock', function ($row) {
                    $badgeClass = $row->stock > 10 ? 'bg-success' : 'bg-danger';
                    return "<span class='badge {$badgeClass}'>{$row->stock}</span>";
                })
                ->addColumn('action', function ($row) {
                    $showUrl = route('products.show', $row->id);
                    $editUrl = route('products.edit', $row->id);
                    $deleteUrl = route('products.destroy', $row->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    return "
                    <a href='{$showUrl}' class='btn btn-info btn-sm'>Detail</a>
                    <a href='{$editUrl}' class='btn btn-primary btn-sm'>Edit</a>
                    <form action='{$deleteUrl}' method='POST' style='display:inline-block'>
                        {$csrf}
                        {$method}
                        <button type='submit' class='btn btn-danger btn-sm' onclick=\"return confirm('Apakah Anda yakin ingin menghapus produk ini?')\">Hapus</button>
                    </form>
                ";
                })
                ->rawColumns(['action', 'stock'])
                ->make(true);
        }
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
