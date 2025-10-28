@extends('layouts.app')

@section('content')
    <div class="">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Tambah Pemasok Baru</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('suppliers.index') }}"> Kembali</a>
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

        <form action="{{ route('suppliers.store') }}" method="POST" class="mt-3">
            @csrf

            <div class="row">
                {{-- Nama Pemasok --}}
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group mb-3">
                        <strong>Nama Pemasok: <span class="text-danger">*</span></strong>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: PT Sumber Rezeki">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Kontak --}}
                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group mb-3">
                        <strong>Kontak (Telp/WA): <span class="text-danger">*</span></strong>
                        <input type="text" name="contact" value="{{ old('contact') }}"
                            class="form-control @error('contact') is-invalid @enderror" placeholder="Contoh: 0812XXXXXXXX">
                        @error('contact')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group mb-3">
                        <strong>Alamat Lengkap:</strong>
                        <textarea class="form-control" style="height:100px" name="address" placeholder="Alamat lengkap perusahaan pemasok">{{ old('address') }}</textarea>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-success">Simpan Pemasok</button>
                </div>
            </div>

        </form>
    </div>
@endsection
