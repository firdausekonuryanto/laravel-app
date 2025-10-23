@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Edit Kategori: <span class="text-primary">{{ $category->name }}</span></h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('categories.index') }}"> Kembali</a>
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

        <form action="{{ route('categories.update', $category->id) }}" method="POST" class="mt-3">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group mb-3">
                        <strong>Nama Kategori: <span class="text-danger">*</span></strong>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}"
                            class="form-control @error('name') is-invalid @enderror" placeholder="Nama Kategori">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group mb-3">
                        <strong>Deskripsi:</strong>
                        <textarea class="form-control" style="height:150px" name="description" placeholder="Deskripsi">{{ old('description', $category->description) }}</textarea>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-success">Perbarui Kategori</button>
                </div>
            </div>

        </form>
    </div>
@endsection
