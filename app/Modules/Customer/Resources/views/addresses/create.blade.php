@extends('layouts.app')

@section('title', 'Tambah Alamat')

@section('content')
    <div class="card">
        <h2>Tambah Alamat</h2>
        <form method="POST" action="{{ route('address.store') }}">
            @csrf
            <label>Label (Rumah, Kantor, dll.)</label>
            <input type="text" name="label" value="{{ old('label') }}">

            <label>Nama Penerima</label>
            <input type="text" name="recipient_name" value="{{ old('recipient_name') }}">

            <label>No. Telepon</label>
            <input type="text" name="phone" value="{{ old('phone') }}">

            <label>Alamat Lengkap</label>
            <input type="text" name="full_address" value="{{ old('full_address') }}">

            <label>Kota</label>
            <input type="text" name="city" value="{{ old('city') }}">

            <label>Kode Pos</label>
            <input type="text" name="postal_code" value="{{ old('postal_code') }}">

            <button class="btn" type="submit">Simpan Alamat</button>
        </form>
    </div>
@endsection
