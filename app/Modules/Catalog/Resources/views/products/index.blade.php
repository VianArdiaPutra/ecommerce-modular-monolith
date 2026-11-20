@extends('layouts.app')

@section('title', 'Katalog Produk')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}?v={{ filemtime(public_path('css/catalog.css')) }}">
@endpush

@section('content')
    @php
        $isFiltering = request()->filled('q') || request()->filled('category');
        $activeCategory = $categories->firstWhere('slug', request('category'));
    @endphp

    @unless ($isFiltering)
        {{-- ===== Hero + kolom cari (field q, route catalog.index) ===== --}}
        <section class="hero" style="--hero-image: url('{{ asset('images/hero.webp') }}')">
            <div class="hero-content">
                <h1>Belanja Hangat Semua Ada</h1>
                <p>Temukan produk terbaik untuk gaya hidupmu, hanya di KLIMAQU.</p>
                <form method="GET" action="{{ route('catalog.index') }}" class="hero-search" role="search">
                    <input type="text" name="q" placeholder="Cari produk di KLIMAQU..." aria-label="Cari produk">
                    <button class="btn" type="submit">Cari</button>
                </form>
            </div>
        </section>

        {{-- ===== Ubin kategori ===== --}}
        <section class="cat-section">
            <div class="section-head">
                <h2>Kategori</h2>
            </div>
            <div class="cat-grid">
                @foreach ($categories as $category)
                    @php $icon = 'images/kategori/' . $category->slug . '.webp'; @endphp
                    <a class="cat-tile" href="{{ route('catalog.index', ['category' => $category->slug]) }}">
                        <span class="cat-thumb">
                            @if (file_exists(public_path($icon)))
                                <img src="{{ asset($icon) }}" alt="" loading="lazy">
                            @else
                                <span class="cat-letter">{{ mb_strtoupper(mb_substr($category->name, 0, 1)) }}</span>
                            @endif
                        </span>
                        <span class="cat-name">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        <div class="section-head" id="produk">
            <h2>Semua produk</h2>
            <p class="muted">{{ $products->count() }} produk tersedia</p>
        </div>
    @else
        <div class="page-header">
            <div>
                <h1>{{ $activeCategory ? $activeCategory->name : 'Hasil pencarian' }}</h1>
                <p class="muted">
                    {{ $products->count() }} produk
                    ditemukan{{ request()->filled('q') ? ' untuk “' . request('q') . '”' : '' }}
                </p>
            </div>
            <a class="back-link" href="{{ route('catalog.index') }}">&larr; Kembali ke beranda</a>
        </div>

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
    @endunless

    @if ($products->isEmpty())
        <div class="card">Produk tidak ditemukan.</div>
    @else
        <div class="grid">
            @foreach ($products as $product)
                <div class="card product">
                    <a href="{{ route('catalog.show', $product) }}" class="product-thumb" aria-hidden="true"
                        tabindex="-1">{{ mb_strtoupper(mb_substr($product->name, 0, 1)) }}</a>

                    <div class="product-body">
                        <h3><a href="{{ route('catalog.show', $product) }}">{{ $product->name }}</a></h3>

                        <div class="product-cats">
                            @foreach ($product->categories as $category)
                                <span class="badge">{{ $category->name }}</span>
                            @endforeach
                        </div>

                        <p class="muted product-stock">Stok: {{ $product->stock }}</p>

                        <div class="product-foot">
                            <p class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                            @auth
                                @if (!auth()->user()->isAdmin())
                                    @if ($product->stock > 0)
                                        <form method="POST" action="{{ route('cart.add') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button class="cart-round" type="submit"
                                                aria-label="Tambah {{ $product->name }} ke keranjang"
                                                title="Tambah ke keranjang">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <circle cx="8" cy="21" r="1" />
                                                    <circle cx="19" cy="21" r="1" />
                                                    <path
                                                        d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bad">Stok habis</span>
                                    @endif
                                @endif
                            @else
                                <a class="btn-small" href="{{ route('login') }}">Masuk untuk membeli</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
