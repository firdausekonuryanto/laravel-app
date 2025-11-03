@extends('layouts.app')

@section('content')
    <div class="">
        <h2>Daftar Produk</h2>
        <a href="{{ route('products.create') }}" class="btn btn-success mb-3">Tambah Produk Baru</a>

        @if (session('success'))
            <div class="alert alert-success mt-3">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th width="150px">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(function() {
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('products.data') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'sku',
                        name: 'products.sku'
                    },
                    {
                        data: 'name',
                        name: 'products.name'
                    },
                    {
                        data: 'category_name',
                        name: 'product_categories.name'
                    },
                    {
                        data: 'price',
                        name: 'products.price'
                    },
                    {
                        data: 'stock',
                        name: 'products.stock',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'unit',
                        name: 'products.unit'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endpush
