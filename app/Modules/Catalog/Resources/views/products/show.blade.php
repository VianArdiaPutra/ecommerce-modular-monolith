@extends('layouts.app')

@section('title', $product->name)
@push('head')
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}?v={{ filemtime(public_path('css/catalog.css')) }}">
@endpush

@section('content')
    <a class="back-link" href="{{ route('catalog.index') }}">&larr; Kembali ke katalog</a>

    <div class="card">
        <div class="detail">
            <div class="product-thumb large">{{ mb_strtoupper(mb_substr($product->name, 0, 1)) }}</div>

            <div class="detail-info">
                <div class="product-cats">
                    @foreach ($product->categories as $category)
                        <span class="badge">{{ $category->name }}</span>
                    @endforeach
                </div>

                <h1>{{ $product->name }}</h1>
                <p class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                @if ($product->stock > 0)
                    <span class="badge ok">Stok tersedia: {{ $product->stock }}</span>
                @else
                    <span class="badge bad">Stok habis</span>
                @endif

                <p class="desc">{{ $product->description }}</p>

                @auth
                    @if (!auth()->user()->isAdmin() && $product->stock > 0)
                        <form method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="qty-row">
                                <div class="qty-field">
                                    <label for="quantity">Jumlah</label>
                                    <input id="quantity" type="number" name="quantity" value="1" min="1">
                                </div>
                                <button class="btn" type="submit">Tambah ke Keranjang</button>
                            </div>
                        </form>
                    @endif
                @else
                    <a class="btn" href="{{ route('login') }}">Masuk untuk membeli</a>
                @endauth
            </div>
        </div>
    </div>
@endsection
