<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CustomersController extends Controller
{
    public function index()
    {
        return view('customers.index');
    }
    public function getData(Request $request)
{
    if ($request->ajax()) {
        $data = Customers::select(['id', 'name', 'phone', 'email', 'address'])
            ->orderBy('id', 'desc');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '
                    <a href="'.route('customers.show', $row->id).'" class="btn btn-info btn-sm">Detail</a>
                    <a href="'.route('customers.edit', $row->id).'" class="btn btn-primary btn-sm">Edit</a>
                    <form action="'.route('customers.destroy', $row->id).'" method="POST" style="display:inline;">
                        '.csrf_field().method_field('DELETE').'
                        <button type="submit" class="btn btn-danger btn-sm" 
                            onclick="return confirm(\'Apakah Anda yakin ingin menghapus pelanggan '.$row->name.'?\')">Hapus</button>
                    </form>';
                return $btn;
            })
            ->editColumn('address', function($row){
                return \Illuminate\Support\Str::limit($row->address, 50);
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:50',
                'address' => 'nullable|string|max:500',
                'email' => 'nullable|email|unique:customers,email',
            ],
            [
                'name.required' => 'Nama pelanggan wajib diisi.',
                'phone.required' => 'Kontak (Nomor Telepon) wajib diisi.',
                'email.unique' => 'Email ini sudah terdaftar untuk pelanggan lain.',
            ],
        );

        Customers::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    public function show(Customers $customer)
    {
        $customer->loadCount('transactions');
        return view('customers.show', compact('customer'));
    }

    public function edit(Customers $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customers $customer)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:50',
                'address' => 'nullable|string|max:500',
                'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            ],
            [
                'name.required' => 'Nama pelanggan wajib diisi.',
                'phone.required' => 'Kontak (Nomor Telepon) wajib diisi.',
                'email.unique' => 'Email ini sudah terdaftar untuk pelanggan lain.',
            ],
        );

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil diperbarui!');
    }

    public function destroy(Customers $customer)
    {
        if ($customer->transactions()->count() > 0) {
            return redirect()
                ->route('customers.index')
                ->with('error', 'Gagal menghapus pelanggan. Ada ' . $customer->transactions()->count() . ' transaksi yang masih terkait.');
        }

        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus!');
    }
}
