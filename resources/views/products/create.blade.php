@extends('layouts.app')

@section('content')
    <div class="">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Tambah Produk Baru</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('products.index') }}"> Kembali</a>
                </div>
            </div>
        </div>

        {{-- Menampilkan Error Validasi --}}
        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <strong>Whoops!</strong> Ada masalah dengan input Anda.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM UTAMA --}}
        <form action="{{ route('products.store') }}" method="POST" class="mt-3">
            @csrf

            <div class="row">
                {{-- Kategori (foreignId: category_id) --}}
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Kategori:</strong> <span class="text-danger">*</span>
                        <select name="category_id" class="form-control">
                            <option value="">-- Pilih Kategori --</option>
                            {{-- Asumsi variabel $categories dikirim dari controller --}}
                            @isset($categories)
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                </div>

                {{-- Supplier (foreignId: supplier_id) --}}
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Supplier:</strong>
                        <select name="supplier_id" class="form-control">
                            <option value="">-- Pilih Supplier (Opsional) --</option>
                            {{-- Asumsi variabel $suppliers dikirim dari controller --}}
                            @isset($suppliers)
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                </div>

                {{-- Nama Produk (name) --}}
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Nama Produk:</strong> <span class="text-danger">*</span>
                        <input type="text" name="name" class="form-control" placeholder="Nama Produk"
                            value="{{ old('name') }}">
                    </div>
                </div>

                {{-- SKU (sku) --}}
                <div class="col-xs-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>SKU:</strong> <span class="text-danger">*</span>
                        <input type="text" name="sku" class="form-control" placeholder="SKU Unik"
                            value="{{ old('sku') }}">
                    </div>
                </div>

                <hr class="my-3">

                {{-- Harga Jual (price) --}}
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group">
                        <strong>Harga Jual (Rp):</strong> <span class="text-danger">*</span>
                        <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01"
                            value="{{ old('price') }}">
                    </div>
                </div>

                {{-- Harga Modal (cost_price) --}}
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group">
                        <strong>Harga Modal (Rp):</strong>
                        <input type="number" name="cost_price" class="form-control" placeholder="0.00" step="0.01"
                            value="{{ old('cost_price') }}">
                    </div>
                </div>

                {{-- Stok (stock) --}}
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group">
                        <strong>Stok Awal:</strong>
                        <input type="number" name="stock" class="form-control" placeholder="0"
                            value="{{ old('stock', 0) }}"> {{-- Default 0 --}}
                    </div>
                </div>

                {{-- Satuan (unit) --}}
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <div class="form-group">
                        <strong>Satuan (Unit):</strong>
                        <input type="text" name="unit" class="form-control" placeholder="pcs/kg/liter"
                            value="{{ old('unit', 'pcs') }}"> {{-- Default 'pcs' --}}
                    </div>
                </div>

                {{-- created_by field tidak perlu dimasukkan dalam form karena biasanya diisi di Controller --}}

                <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-3">
                    <button type="submit" class="btn btn-success">Simpan Produk</button>
                </div>
            </div>
        </form>
    </div>
@endsection
