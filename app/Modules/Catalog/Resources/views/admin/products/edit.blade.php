@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
    <div class="card">
        <h2>Edit Produk</h2>
        <form method="POST" action="{{ route('admin.products.update', $product) }}">
            @method('PUT')
            @include('catalog::admin.products._form')
        </form>
    </div>
@endsection
