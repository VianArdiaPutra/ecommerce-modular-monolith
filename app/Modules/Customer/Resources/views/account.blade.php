@extends('layouts.app')

@section('title', 'Akun Saya')

@section('content')
    <div class="card">
        <h2>Halo, {{ auth()->user()->name }}</h2>
        <p>Email: {{ auth()->user()->email }}</p>
        <p>Role: <strong>{{ auth()->user()->role }}</strong></p>
    </div>
@endsection
