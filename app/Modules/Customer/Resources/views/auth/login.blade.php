@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <div class="card">
        <h2>Masuk</h2>
        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}">

            <label>Password</label>
            <input type="password" name="password">

            <button class="btn" type="submit">Masuk</button>
        </form>
    </div>
@endsection
