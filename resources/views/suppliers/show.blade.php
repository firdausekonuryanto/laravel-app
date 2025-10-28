@extends('layouts.app')

@section('content')
    <div class="">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Detail Pemasok</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('suppliers.index') }}"> Kembali</a>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-xs-12 col-sm-12 col-md-8 offset-md-2">
                <div class="card p-4 shadow-sm">
                    <h4 class="card-title mb-4">{{ $supplier->name }}</h4>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <strong>Kontak (Telp/WA):</strong>
                                <p class="form-control-static">{{ $supplier->contact }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <strong>Alamat:</strong>
                        <p class="form-control-static">{{ $supplier->address ?? '-' }}</p>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <strong>Total Produk Dipasok:</strong>
                                {{-- products_count dihitung di controller menggunakan withCount --}}
                                <p class="form-control-static">
                                    <span class="badge bg-primary">{{ $supplier->products_count }}</span> Produk
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <strong>Terakhir Diperbarui:</strong>
                                <p class="form-control-static">{{ $supplier->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
