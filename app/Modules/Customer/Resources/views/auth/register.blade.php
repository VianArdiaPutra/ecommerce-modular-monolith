@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
    <div class="card">
        <h2>Daftar Akun</h2>
        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name') }}">

            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}">

            <label>No. Telepon (opsional)</label>
            <input type="text" name="phone" value="{{ old('phone') }}">

            <label>Password</label>
            <input type="password" name="password">

            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation">

            <button class="btn" type="submit">Daftar</button>
        </form>
    </div>
@endsection
