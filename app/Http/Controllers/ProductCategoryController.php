<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::orderBy('id', 'desc')->paginate(10);
        return view('product-categories.index', compact('categories'));
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
