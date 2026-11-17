@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
    <div class="card">
        <h2>Tambah Produk</h2>
        <form method="POST" action="{{ route('admin.products.store') }}">
            @include('catalog::admin.products._form')
        </form>
    </div>
@endsection
