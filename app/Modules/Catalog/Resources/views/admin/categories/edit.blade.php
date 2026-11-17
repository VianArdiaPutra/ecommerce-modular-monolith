@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <div class="card">
        <h2>Edit Kategori</h2>
        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @method('PUT')
            @include('catalog::admin.categories._form')
        </form>
    </div>
@endsection
