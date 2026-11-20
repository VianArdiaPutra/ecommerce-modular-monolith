@extends('layouts.app')

@section('title', 'Katalog Produk')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}?v={{ filemtime(public_path('css/catalog.css')) }}">
@endpush

@section('content')
    @php
        $isFiltering = request()->filled('q') || request()->filled('category');
        $activeCategory = $categories->firstWhere('slug', request('category'));
        $user = auth()->user();
        $canBuy = $user && !$user->isAdmin();
        $limit = 8;
    @endphp

    @unless ($isFiltering)
        <section class="catalog-hero">
            <div class="hero-main">
                <h1>Belanja hangat, semua ada.</h1>
                <p class="hero-lead">
                    {{ $products->count() }} produk dari {{ $categories->count() }} kategori.
                    Pilih, masukkan ke keranjang, lalu pesan.
                </p>
                @include('catalog::products._search')
            </div>

            <nav class="cat-list" aria-label="Kategori">
                <div class="cat-list-head">
                    <span>Kategori</span>
                    <span>{{ $categories->count() }}</span>
                </div>
                @foreach ($categories as $category)
                    <a class="cat-row" href="{{ route('catalog.index', ['category' => $category->slug]) }}">
                        <span class="num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="name">{{ $category->name }}</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </a>
                @endforeach
            </nav>
        </section>
    @else
        <section class="results-head">
            <a class="crumb" href="{{ route('catalog.index') }}">&larr; Semua produk</a>
            <h1>{{ $activeCategory ? $activeCategory->name : 'Hasil pencarian' }}</h1>
            <p>{{ $products->count() }} produk ditemukan</p>
            @if (request()->filled('q'))
                <p>Kata kunci: &ldquo;{{ request('q') }}&rdquo;</p>
            @endif

            @include('catalog::products._search')

            <div class="chip-row">
                <a class="chip {{ request()->filled('category') ? '' : 'active' }}"
                    href="{{ route('catalog.index', array_filter(['q' => request('q')])) }}">Semua</a>
                @foreach ($categories as $category)
                    <a class="chip {{ request('category') === $category->slug ? 'active' : '' }}"
                        href="{{ route('catalog.index', array_filter(['category' => $category->slug, 'q' => request('q')])) }}">{{ $category->name }}</a>
                @endforeach
            </div>
        </section>
    @endunless

    @if ($products->isEmpty())
        <div class="empty-state">
            <h2>Produk tidak ditemukan</h2>
            <p>Coba kata kunci lain, atau lihat <a href="{{ route('catalog.index') }}">semua produk</a>.</p>
        </div>
    @else
        <div class="products-head" id="produk">
            <h2>{{ $isFiltering ? 'Produk' : 'Semua produk' }}</h2>
            <div class="products-tools">
                <span>{{ $products->count() }} produk tersedia</span>
                <select class="sort-select" id="sort-select" aria-label="Urutkan produk">
                    <option value="name">Urutkan: Nama A-Z</option>
                    <option value="newest">Urutkan: Terbaru</option>
                    <option value="price-asc">Urutkan: Harga terendah</option>
                    <option value="price-desc">Urutkan: Harga tertinggi</option>
                </select>
            </div>
        </div>

        <div class="p-grid" id="p-grid">
            @foreach ($products as $product)
                @php
                    $mainCategory = $product->categories->first();
                    $slug = $mainCategory ? $mainCategory->slug : 'lainnya';
                    $catNames = $product->categories->pluck('name')->join(', ');
                @endphp
                <article class="p-card" data-name="{{ $product->name }}" data-price="{{ (int) $product->price }}"
                    data-created="{{ $product->created_at ? $product->created_at->timestamp : 0 }}">
                    <a class="p-thumb tone-{{ $slug }}" href="{{ route('catalog.show', $product) }}"
                        tabindex="-1" aria-hidden="true">
                        @include('catalog::products._icon', ['slug' => $slug, 'name' => $product->name])
                    </a>

                    <div class="p-info">
                        <h3 class="p-name"><a href="{{ route('catalog.show', $product) }}">{{ $product->name }}</a></h3>
                        <p class="p-cat">{{ $catNames }}</p>
                        @if ($product->stock <= 0)
                            <p class="p-stock is-out">Stok habis</p>
                        @elseif ($product->stock <= 5)
                            <p class="p-stock is-low">Sisa {{ $product->stock }}</p>
                        @else
                            <p class="p-stock">Stok {{ $product->stock }}</p>
                        @endif

                        <div class="p-foot">
                            <span class="p-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>

                            @auth
                                @if ($canBuy)
                                    @if ($product->stock > 0)
                                        <form method="POST" action="{{ route('cart.add') }}">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button class="p-btn" type="submit">+ Tambah</button>
                                        </form>
                                    @else
                                        <span class="p-btn is-off">Habis</span>
                                    @endif
                                @endif
                            @else
                                <a class="p-btn" href="{{ route('login') }}" title="Masuk untuk membeli">+ Tambah</a>
                            @endauth
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        @if ($products->count() > $limit)
            <div class="more-wrap">
                <button type="button" class="more-btn" id="more-btn" hidden>
                    Tampilkan {{ $products->count() - $limit }} produk lagi
                </button>
            </div>
        @endif

        <script>
            (function() {
                var grid = document.getElementById('p-grid');
                if (!grid) return;

                var select = document.getElementById('sort-select');
                var moreBtn = document.getElementById('more-btn');
                var LIMIT = {{ $limit }};
                var expanded = false;

                function cards() {
                    return Array.prototype.slice.call(grid.children);
                }

                function applyLimit() {
                    var all = cards();
                    all.forEach(function(card, i) {
                        card.classList.toggle('is-hidden', !expanded && i >= LIMIT);
                    });
                    if (moreBtn) {
                        moreBtn.hidden = expanded || all.length <= LIMIT;
                    }
                }

                function sortCards(mode) {
                    var items = cards();
                    items.sort(function(a, b) {
                        if (mode === 'price-asc') return a.dataset.price - b.dataset.price;
                        if (mode === 'price-desc') return b.dataset.price - a.dataset.price;
                        if (mode === 'newest') {
                            return (b.dataset.created - a.dataset.created) ||
                                a.dataset.name.localeCompare(b.dataset.name, 'id');
                        }
                        return a.dataset.name.localeCompare(b.dataset.name, 'id');
                    });
                    items.forEach(function(card) {
                        grid.appendChild(card);
                    });
                    applyLimit();
                }

                if (select) {
                    select.addEventListener('change', function() {
                        sortCards(select.value);
                    });
                }
                if (moreBtn) {
                    moreBtn.addEventListener('click', function() {
                        expanded = true;
                        applyLimit();
                    });
                }

                sortCards(select ? select.value : 'name');
            })();
        </script>
    @endif
@endsection
