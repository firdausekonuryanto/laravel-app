<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables; // <--- TAMBAHKAN INI

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:customers.create')->only('create', 'store');
        $this->middleware('permission:customers.read')->only('index', 'getData');
        $this->middleware('permission:customers.update')->only('edit', 'update');
        $this->middleware('permission:customers.delete')->only('destroy');
    }

    public function index()
    {
        return view('product-categories.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = ProductCategory::select(['id', 'name', 'description'])->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('description', function ($row) {
                    // Potong deskripsi agar tidak terlalu panjang
                    return $row->description;
                })
                ->addColumn('action', function ($row) {
                    $showUrl = route('categories.show', $row->id);
                    $editUrl = route('categories.edit', $row->id);
                    $deleteUrl = route('categories.destroy', $row->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    return "
                        <a href='{$showUrl}' class='btn btn-info btn-sm'>Detail</a>
                        <a href='{$editUrl}' class='btn btn-primary btn-sm'>Edit</a>
                        <form action='{$deleteUrl}' method='POST' style='display:inline-block'>
                            {$csrf}
                            {$method}
                            <button class='btn btn-danger btn-sm' onclick=\"return confirm('Hapus kategori ini?')\">Hapus</button>
                        </form>
                    ";
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('product-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255|unique:product_categories,name',
                'description' => 'nullable|string|max:500',
            ],
            [
                'name.required' => 'Nama kategori wajib diisi.',
                'name.unique' => 'Nama kategori ini sudah ada.',
            ],
        );

        ProductCategory::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Kategori produk berhasil ditambahkan!');
    }

    public function show(ProductCategory $category)
    {
        return view('product-categories.show', compact('category'));
    }

    public function edit(ProductCategory $category)
    {
        return view('product-categories.edit', compact('category'));
    }

    public function update(Request $request, ProductCategory $category)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255|unique:product_categories,name,' . $category->id,
                'description' => 'nullable|string|max:500',
            ],
            [
                'name.required' => 'Nama kategori wajib diisi.',
                'name.unique' => 'Nama kategori ini sudah ada.',
            ],
        );

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Kategori produk berhasil diperbarui!');
    }

    public function destroy(ProductCategory $category)
    {
        try {
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Kategori produk berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Gagal menghapus kategori. Pastikan tidak ada produk yang menggunakan kategori ini.');
        }
    }
}
