<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('id', 'desc')->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'contact' => 'required|string|max:50',
                'address' => 'nullable|string|max:500',
            ],
            [
                'name.required' => 'Nama pemasok wajib diisi.',
                'contact.required' => 'Kontak wajib diisi.',
            ],
        );

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Pemasok berhasil ditambahkan!');
    }

    public function show(Supplier $supplier)
    {
        $supplier->loadCount('products');
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'contact' => 'required|string|max:50',
                'address' => 'nullable|string|max:500',
            ],
            [
                'name.required' => 'Nama pemasok wajib diisi.',
                'contact.required' => 'Kontak wajib diisi.',
            ],
        );

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')->with('success', 'Pemasok berhasil diperbarui!');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->products()->count() > 0) {
            return redirect()
                ->route('suppliers.index')
                ->with('error', 'Gagal menghapus pemasok. Ada ' . $supplier->products()->count() . ' produk yang masih terkait.');
        }

        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Pemasok berhasil dihapus!');
    }
}
