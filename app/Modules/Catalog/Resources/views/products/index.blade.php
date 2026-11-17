@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')
    <h2>Katalog Produk</h2>

    <form method="GET" action="{{ route('catalog.index') }}" class="filter">
        <input type="text" name="q" placeholder="Cari produk..." value="{{ request('q') }}">
        <select name="category">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <button class="btn" type="submit">Cari</button>
    </form>

    @if ($products->isEmpty())
        <div class="card">Produk tidak ditemukan.</div>
    @else
        <div class="grid">
            @foreach ($products as $product)
                <div class="card product">
                    <h3><a href="{{ route('catalog.show', $product) }}">{{ $product->name }}</a></h3>
                    <p class="muted">{{ $product->categories->pluck('name')->join(', ') }}</p>
                    <p class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <p class="muted">Stok: {{ $product->stock }}</p>

                    @auth
                        @if (!auth()->user()->isAdmin())
                            @if ($product->stock > 0)
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button class="btn" type="submit">+ Keranjang</button>
                                </form>
                            @else
                                <span class="badge">Stok habis</span>
                            @endif
                        @endif
                    @else
                        <a class="btn" href="{{ route('login') }}">Masuk untuk membeli</a>
                    @endauth
                </div>
            @endforeach
        </div>
    @endif
@endsection
