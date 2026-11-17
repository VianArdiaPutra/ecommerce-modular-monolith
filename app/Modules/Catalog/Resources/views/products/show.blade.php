@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="card">
        <h2>{{ $product->name }}</h2>
        <p class="muted">{{ $product->categories->pluck('name')->join(', ') }}</p>
        <p>{{ $product->description }}</p>
        <p class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
        <p class="muted">Stok: {{ $product->stock }}</p>

        @auth
            @if (!auth()->user()->isAdmin() && $product->stock > 0)
                <form method="POST" action="{{ route('cart.add') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <label>Jumlah</label>
                    <input type="number" name="quantity" value="1" min="1" style="width:100px">
                    <button class="btn" type="submit">Tambah ke Keranjang</button>
                </form>
            @endif
        @else
            <a class="btn" href="{{ route('login') }}">Masuk untuk membeli</a>
        @endauth

        <p><a href="{{ route('catalog.index') }}">&larr; Kembali ke katalog</a></p>
    </div>
@endsection
