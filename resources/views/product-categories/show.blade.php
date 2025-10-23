@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Detail Kategori Produk</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('categories.index') }}"> Kembali</a>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="card p-4 shadow-sm">
                    {{-- Nama --}}
                    <div class="form-group mb-3">
                        <strong>Nama Kategori:</strong>
                        <p class="form-control-static">{{ $category->name }}</p>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="form-group mb-3">
                        <strong>Deskripsi:</strong>
                        <p class="form-control-static">{{ $category->description ?? '-' }}</p>
                    </div>

                    {{-- Data Lain --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <strong>Dibuat Pada:</strong>
                                <p class="form-control-static">{{ $category->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <strong>Diperbarui Pada:</strong>
                                <p class="form-control-static">{{ $category->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- Jumlah Produk Terkait (Opsional, memerlukan eager loading di controller) --}}
                    <div class="form-group mb-0">
                        <strong>Total Produk Terkait:</strong>
                        {{-- Untuk menampilkan ini, pastikan Anda menggunakan eager loading di controller show():
                            $category = ProductCategory::withCount('products')->findOrFail($id);
                        --}}
                        <p class="form-control-static">{{ $category->products_count ?? 'N/A' }} Produk</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
