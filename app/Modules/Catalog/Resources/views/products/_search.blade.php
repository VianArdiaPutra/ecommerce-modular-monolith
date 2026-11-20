<form method="GET" action="{{ route('catalog.index') }}" class="search-bar" role="search">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
        <circle cx="11" cy="11" r="7" />
        <path d="m20 20-3.5-3.5" />
    </svg>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk di KLIMAQU"
        aria-label="Cari produk">
    @if (request()->filled('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
    @endif
    <button type="submit">Cari produk</button>
</form>
