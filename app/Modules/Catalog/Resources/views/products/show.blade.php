@extends('layouts.app')

@section('title', $product->name)

@push('head')
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}?v={{ filemtime(public_path('css/catalog.css')) }}">
@endpush

@section('content')
    @php
        $mainCategory = $product->categories->first();
        $slug = $mainCategory ? $mainCategory->slug : 'lainnya';
        $user = auth()->user();
        $canBuy = $user && !$user->isAdmin();
    @endphp

    <nav class="pd-crumb" aria-label="Breadcrumb">
        <a href="{{ route('catalog.index') }}">Katalog</a>
        @if ($mainCategory)
            <span>/</span>
            <a href="{{ route('catalog.index', ['category' => $mainCategory->slug]) }}">{{ $mainCategory->name }}</a>
        @endif
        <span>/</span>
        <span class="current">{{ $product->name }}</span>
    </nav>

    <section class="pd">
        <div class="pd-media">
            <div class="p-thumb tone-{{ $slug }}">
                @include('catalog::products._icon', ['slug' => $slug, 'name' => $product->name])
            </div>
        </div>

        <div class="pd-info">
            <p class="pd-cat">{{ $product->categories->pluck('name')->join(', ') }}</p>
            <h1 class="pd-title">{{ $product->name }}</h1>
            <p class="pd-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

            @if ($product->stock <= 0)
                <p class="p-stock is-out">Stok habis</p>
            @elseif ($product->stock <= 5)
                <p class="p-stock is-low">Sisa {{ $product->stock }}</p>
            @else
                <p class="p-stock">Stok tersedia: {{ $product->stock }}</p>
            @endif

            @if ($product->description)
                <p class="pd-desc">{{ $product->description }}</p>
            @endif

            @auth
                @if ($canBuy)
                    @if ($product->stock > 0)
                        <form class="pd-buy" method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="qty-stepper">
                                <button type="button" data-step="-1" aria-label="Kurangi jumlah">&minus;</button>
                                <input id="quantity" type="number" name="quantity" value="1" min="1"
                                    aria-label="Jumlah">
                                <button type="button" data-step="1" aria-label="Tambah jumlah">+</button>
                            </div>
                            <button class="p-btn lg" type="submit">+ Tambah ke keranjang</button>
                        </form>
                    @else
                        <div class="pd-buy">
                            <span class="p-btn lg is-off">Stok habis</span>
                        </div>
                    @endif
                @endif
            @else
                <div class="pd-buy">
                    <a class="p-btn lg" href="{{ route('login') }}">Masuk untuk membeli</a>
                </div>
            @endauth

            @if ($product->categories->isNotEmpty())
                <div class="pd-meta">
                    <span>Kategori</span>
                    <div class="chip-row">
                        @foreach ($product->categories as $category)
                            <a class="chip"
                                href="{{ route('catalog.index', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <script>
        (function() {
            var input = document.getElementById('quantity');
            if (!input) return;

            document.querySelectorAll('[data-step]').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var value = parseInt(input.value, 10) || 1;
                    value += parseInt(btn.dataset.step, 10);
                    if (value < 1) value = 1;
                    input.value = value;
                });
            });
        })();
    </script>
@endsection
