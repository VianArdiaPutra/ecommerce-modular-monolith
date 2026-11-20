@extends('layouts.app')

@section('title', 'Daftar')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ filemtime(public_path('css/auth.css')) }}">
@endpush

@section('bare')
    @include('customer::auth._split', ['mode' => 'register'])
@endsection
