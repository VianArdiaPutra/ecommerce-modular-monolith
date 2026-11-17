@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
    <h2>Kelola Kategori</h2>
    <a class="btn" href="{{ route('admin.categories.create') }}">+ Tambah Kategori</a>

    <div class="card" style="margin-top:16px">
        @if ($categories->isEmpty())
            <p>Belum ada kategori.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jumlah Produk</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->products_count }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline"
                                    onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <a class="btn-small" style="text-decoration:none"
                                        href="{{ route('admin.categories.edit', $category) }}">Edit</a>
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
