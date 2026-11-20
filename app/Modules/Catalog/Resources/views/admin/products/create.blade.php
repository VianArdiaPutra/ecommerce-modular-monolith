@extends('layouts.app')

@section('title', 'Tambah Produk')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
@endpush

@section('content')
    <a class="back-link" href="{{ route('admin.products.index') }}">&larr; Kembali ke daftar produk</a>

    <div class="card form-card">
        <h1>Tambah Produk</h1>
        <form method="POST" action="{{ route('admin.products.store') }}">
            @include('catalog::admin.products._form')
        </form>
    </div>
@endsection
