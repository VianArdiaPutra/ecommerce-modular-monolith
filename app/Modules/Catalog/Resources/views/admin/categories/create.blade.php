@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <div class="card">
        <h2>Tambah Kategori</h2>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @include('catalog::admin.categories._form')
        </form>
    </div>
@endsection
