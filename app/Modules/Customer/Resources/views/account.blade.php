@extends('layouts.app')

@section('title', 'Akun Saya')

@section('content')
    <div class="card">
        <h2>Halo, {{ auth()->user()->name }}</h2>
        <p>Email: {{ auth()->user()->email }}</p>
        <p>Role: <strong>{{ auth()->user()->role }}</strong></p>
    </div>

    @if (!auth()->user()->isAdmin())
        <div class="card" style="margin-top:16px">
            <h3>Alamat Saya</h3>
            @forelse (auth()->user()->customer?->addresses ?? [] as $address)
                <p>
                    <strong>{{ $address->label }}</strong>
                    @if ($address->is_default)
                        <span class="badge">Utama</span>
                    @endif
                    <br>
                    {{ $address->recipient_name }} ({{ $address->phone }})<br>
                    {{ $address->full_address }}, {{ $address->city }} {{ $address->postal_code }}
                </p>
            @empty
                <p class="muted">Belum ada alamat.</p>
            @endforelse
            <a class="btn" href="{{ route('address.create') }}">+ Tambah Alamat</a>
        </div>
    @endif
@endsection
