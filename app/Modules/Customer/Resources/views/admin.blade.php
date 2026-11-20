@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
@endpush

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard Admin</h1>
            <p class="muted">Halaman ini hanya bisa dibuka oleh role <strong>admin</strong>.</p>
        </div>
    </div>

    <div class="admin-tiles">
        <a class="admin-tile" href="{{ route('admin.products.index') }}">
            <h3>Kelola Produk</h3>
            <p>Tambah, ubah, dan hapus produk di katalog.</p>
            <span class="go">Buka</span>
        </a>
        <a class="admin-tile" href="{{ route('admin.categories.index') }}">
            <h3>Kelola Kategori</h3>
            <p>Atur kategori yang dipakai untuk mengelompokkan produk.</p>
            <span class="go">Buka</span>
        </a>
    </div>
@endsection
