@extends('layouts.app')

@section('title', 'Edit Kategori')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
@endpush

@section('content')
    <a class="back-link" href="{{ route('admin.categories.index') }}">&larr; Kembali ke daftar kategori</a>

    <div class="card form-card">
        <h1>Edit Kategori</h1>
        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @method('PUT')
            @include('catalog::admin.categories._form')
        </form>
    </div>
@endsection
