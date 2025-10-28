<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{
  public function index()
{
    return view('suppliers.index');
}

public function getData(Request $request)
{
    if ($request->ajax()) {
        $data = DB::table('suppliers')
            ->select('id', 'name', 'contact', 'address')
            ->orderBy('id', 'desc');

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('address', function ($row) {
                return $row->address;
            })
            ->addColumn('action', function ($row) {
                $showUrl = route('suppliers.show', $row->id);
                $editUrl = route('suppliers.edit', $row->id);
                $deleteUrl = route('suppliers.destroy', $row->id);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                return "
                    <a href='{$showUrl}' class='btn btn-info btn-sm'>Detail</a>
                    <a href='{$editUrl}' class='btn btn-primary btn-sm'>Edit</a>
                    <form action='{$deleteUrl}' method='POST' style='display:inline-block'>
                        {$csrf}
                        {$method}
                        <button type='submit' class='btn btn-danger btn-sm' onclick=\"return confirm('Apakah Anda yakin ingin menghapus pemasok ini?')\">Hapus</button>
                    </form>
                ";
            })
            ->rawColumns(['action'])
            ->make(true);
    }
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
