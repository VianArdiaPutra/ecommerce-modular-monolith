@extends('layouts.app')

@section('title', 'Akun Saya')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/account.css') }}?v={{ filemtime(public_path('css/account.css')) }}">
@endpush

@section('content')
    <div class="page-header">
        <h1>Halo, {{ auth()->user()->name }}</h1>
    </div>

    <div class="account-layout">
        <aside class="card profile-card">
            <div class="avatar-lg">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</div>
            <h2>{{ auth()->user()->name }}</h2>
            <p class="muted">{{ auth()->user()->email }}</p>
            <span class="badge {{ auth()->user()->isAdmin() ? '' : 'ok' }}">Role: {{ auth()->user()->role }}</span>
        </aside>

        @if (!auth()->user()->isAdmin())
            <section class="card">
                <div class="section-bar">
                    <h3>Alamat Saya</h3>
                    <a class="btn" href="{{ route('address.create') }}">+ Tambah Alamat</a>
                </div>

                @php $addresses = auth()->user()->customer?->addresses ?? []; @endphp

                @if (count($addresses) === 0)
                    <p class="muted">Belum ada alamat.</p>
                @else
                    <div class="address-list">
                        @foreach ($addresses as $address)
                            <div class="address-card {{ $address->is_default ? 'is-default' : '' }}">
                                <h4>
                                    {{ $address->label }}
                                    @if ($address->is_default)
                                        <span class="badge ok">Utama</span>
                                    @endif
                                </h4>
                                <p><strong>{{ $address->recipient_name }}</strong> ({{ $address->phone }})</p>
                                <p class="muted">{{ $address->full_address }}, {{ $address->city }}
                                    {{ $address->postal_code }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        @else
            <section class="card">
                <h3>Akses admin</h3>
                <p class="muted">Kamu masuk sebagai admin. Kelola produk dan kategori dari dashboard.</p>
                <a class="btn" href="{{ route('admin.dashboard') }}">Buka dashboard admin</a>
            </section>
        @endif
    </div>
@endsection
