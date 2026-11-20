@extends('layouts.app')

@section('title', 'Kelola Kategori')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
@endpush

@section('content')
    <div class="page-header">
        <div>
            <h1>Kelola Kategori</h1>
            <p class="muted">Kategori dipakai untuk mengelompokkan produk di katalog.</p>
        </div>
        <a class="btn" href="{{ route('admin.categories.create') }}">+ Tambah Kategori</a>
    </div>

    <div class="card">
        @if ($categories->isEmpty())
            <p class="muted">Belum ada kategori.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th class="num">Jumlah Produk</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td class="cell-name">{{ $category->name }}</td>
                                <td class="num">{{ $category->products_count }}</td>
                                <td>
                                    <div class="row-actions">
                                        <a class="btn-small" href="{{ route('admin.categories.edit', $category) }}">Edit</a>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                            class="inline" data-confirm-delete="{{ $category->name }}">
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
