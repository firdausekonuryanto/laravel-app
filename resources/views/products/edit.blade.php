@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Edit Produk: <span class="text-primary">{{ $product->name }}</span></h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('products.index') }}"> Kembali ke Daftar</a>
                </div>
            </div>
        </div>

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

        <form action="{{ route('products.update', $product->id) }}" method="POST" class="mt-3">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Kolom Kiri: Detail Dasar --}}
                <div class="col-md-6">
                    <div class="card p-3 shadow-sm mb-3">
                        <h5 class="card-title">Informasi Dasar Produk</h5>

                        {{-- Nama Produk --}}
                        <div class="form-group mb-3">
                            <strong>Nama Produk: <span class="text-danger">*</span></strong>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Contoh: Kopi Bubuk Instan">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SKU --}}
                        <div class="form-group mb-3">
                            <strong>SKU/Kode Produk: <span class="text-danger">*</span></strong>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                                class="form-control @error('sku') is-invalid @enderror" placeholder="Contoh: KOP001">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Satuan Unit --}}
                        <div class="form-group mb-3">
                            <strong>Satuan Unit: <span class="text-danger">*</span></strong>
                            {{-- Anda mungkin perlu menambahkan daftar unit standar di sini (pcs, box, dll.) --}}
                            <input type="text" name="unit" value="{{ old('unit', $product->unit) }}"
                                class="form-control @error('unit') is-invalid @enderror"
                                placeholder="Contoh: pcs, botol, pack">
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Deskripsi Produk --}}
                        <div class="form-group mb-3">
                            <strong>Deskripsi:</strong>
                            <textarea class="form-control @error('description') is-invalid @enderror" style="height:120px" name="description"
                                placeholder="Deskripsi lengkap produk">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Harga, Stok, dan Relasi --}}
                <div class="col-md-6">
                    <div class="card p-3 shadow-sm mb-3">
                        <h5 class="card-title">Harga, Stok, dan Relasi</h5>

                        {{-- Kategori (Dropdown) --}}
                        <div class="form-group mb-3">
                            <strong>Kategori: <span class="text-danger">*</span></strong>
                            <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                <option value="">-- Pilih Kategori --</option>
                                {{-- Pastikan $categories dikirim dari controller --}}
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Supplier (Dropdown) --}}
                        <div class="form-group mb-3">
                            <strong>Supplier:</strong>
                            <select name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror">
                                <option value="">-- Pilih Supplier (Opsional) --</option>
                                {{-- Pastikan $suppliers dikirim dari controller --}}
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        {{-- Harga Beli (Cost Price) --}}
                        <div class="form-group mb-3">
                            <strong>Harga Beli (Modal): <span class="text-danger">*</span></strong>
                            <input type="number" name="cost_price"
                                class="form-control @error('cost_price') is-invalid @enderror" placeholder="0"
                                step="1" min="0" value="{{ old('cost_price', $product->cost_price) }}">
                            @error('cost_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Harga Jual (Price) --}}
                        <div class="form-group mb-3">
                            <strong>Harga Jual: <span class="text-danger">*</span></strong>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                placeholder="0" step="1" min="0" value="{{ old('price', $product->price) }}">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Stok --}}
                        <div class="form-group mb-3">
                            <strong>Stok Saat Ini: <span class="text-danger">*</span></strong>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                placeholder="0" min="0" value="{{ old('stock', $product->stock) }}">
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fa fa-save"></i> Perbarui Produk
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
