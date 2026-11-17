@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="card">
        <h2>Dashboard Admin</h2>
        <p>Halaman ini hanya bisa dibuka oleh role <strong>admin</strong>.</p>
        <a class="btn" href="{{ route('admin.products.index') }}">Kelola Produk</a>
        <a class="btn" href="{{ route('admin.categories.index') }}">Kelola Kategori</a>
    </div>
@endsection
