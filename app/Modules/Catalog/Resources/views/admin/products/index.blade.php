@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('content')
    <h2>Kelola Produk</h2>
    <a class="btn" href="{{ route('admin.products.create') }}">+ Tambah Produk</a>

    <div class="card" style="margin-top:16px">
        @if ($products->isEmpty())
            <p>Belum ada produk.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->categories->pluck('name')->join(', ') }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td><span class="badge">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline"
                                    onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <a class="btn-small" style="text-decoration:none"
                                        href="{{ route('admin.products.edit', $product) }}">Edit</a>
                                    <button class="btn-small danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
