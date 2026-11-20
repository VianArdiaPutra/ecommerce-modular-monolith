@extends('layouts.app')

@section('title', 'Kelola Produk')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
@endpush

@section('content')
    <div class="page-header">
        <div>
            <h1>Kelola Produk</h1>
            <p class="muted">Tambah, ubah, dan hapus produk di katalog.</p>
        </div>
        <a class="btn" href="{{ route('admin.products.create') }}">+ Tambah Produk</a>
    </div>

    <div class="card">
        @if ($products->isEmpty())
            <p class="muted">Belum ada produk.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th class="num">Harga</th>
                            <th class="num">Stok</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td class="cell-name">{{ $product->name }}</td>
                                <td>
                                    <div class="product-cats" style="margin:0">
                                        @foreach ($product->categories as $category)
                                            <span class="badge">{{ $category->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="num">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="num">{{ $product->stock }}</td>
                                <td>
                                    <span class="badge {{ $product->is_active ? 'ok' : 'bad' }}">
                                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a class="btn-small" href="{{ route('admin.products.edit', $product) }}">Edit</a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                            class="inline" data-confirm-delete="{{ $product->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-small danger" type="submit">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @include('catalog::admin._confirm-delete')
@endsection
