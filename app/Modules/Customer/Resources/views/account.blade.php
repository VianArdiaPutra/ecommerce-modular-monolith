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

        <div class="account-main">
            {{-- ===== Pengaturan akun ===== --}}
            <section class="card ac-settings">
                <h3>Pengaturan akun</h3>

                {{-- Ganti nama --}}
                <form id="ubah-nama" class="ac-block" method="POST" action="{{ route('account.name') }}" novalidate>
                    @csrf
                    @method('PATCH')
                    <div class="ac-head">
                        <h4>Nama</h4>
                        <p class="muted">Nama ditampilkan di menu profil dan pesananmu.</p>
                    </div>
                    <div class="ac-fields">
                        <div class="ac-field">
                            <label for="name">Nama lengkap</label>
                            <input id="name" type="text" name="name" autocomplete="name"
                                value="{{ old('name', auth()->user()->name) }}"
                                class="{{ $errors->getBag('updateName')->has('name') ? 'is-invalid' : '' }}">
                            @error('name', 'updateName')
                                <p class="ac-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="ac-actions">
                        <button class="btn" type="submit">Simpan nama</button>
                    </div>
                </form>

                {{-- Ganti email (wajib password lama) --}}
                <form id="ubah-email" class="ac-block" method="POST" action="{{ route('account.email') }}" novalidate>
                    @csrf
                    @method('PATCH')
                    <div class="ac-head">
                        <h4>Email</h4>
                        <p class="muted">Email dipakai untuk masuk. Masukkan password lama untuk mengganti.</p>
                    </div>
                    <div class="ac-fields">
                        <div class="ac-field">
                            <label for="email">Email baru</label>
                            <input id="email" type="email" name="email" autocomplete="email"
                                value="{{ old('email', auth()->user()->email) }}"
                                class="{{ $errors->getBag('updateEmail')->has('email') ? 'is-invalid' : '' }}">
                            @error('email', 'updateEmail')
                                <p class="ac-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="ac-field">
                            <label for="email-current-password">Password lama</label>
                            <input id="email-current-password" type="password" name="current_password"
                                autocomplete="current-password"
                                class="{{ $errors->getBag('updateEmail')->has('current_password') ? 'is-invalid' : '' }}">
                            @error('current_password', 'updateEmail')
                                <p class="ac-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="ac-actions">
                        <button class="btn" type="submit">Ganti email</button>
                    </div>
                </form>

                {{-- Ganti password (wajib password lama) --}}
                <form id="ubah-password" class="ac-block" method="POST" action="{{ route('account.password') }}"
                    novalidate>
                    @csrf
                    @method('PATCH')
                    <div class="ac-head">
                        <h4>Password</h4>
                        <p class="muted">Gunakan minimal 8 karakter dan berbeda dari password lama.</p>
                    </div>
                    <div class="ac-fields">
                        <div class="ac-field">
                            <label for="pw-current-password">Password lama</label>
                            <input id="pw-current-password" type="password" name="current_password"
                                autocomplete="current-password"
                                class="{{ $errors->getBag('updatePassword')->has('current_password') ? 'is-invalid' : '' }}">
                            @error('current_password', 'updatePassword')
                                <p class="ac-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="ac-field">
                            <label for="pw-new">Password baru</label>
                            <input id="pw-new" type="password" name="password" autocomplete="new-password"
                                class="{{ $errors->getBag('updatePassword')->has('password') ? 'is-invalid' : '' }}">
                            @error('password', 'updatePassword')
                                <p class="ac-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="ac-field">
                            <label for="pw-confirm">Ulangi password baru</label>
                            <input id="pw-confirm" type="password" name="password_confirmation" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="ac-actions">
                        <button class="btn" type="submit">Ganti password</button>
                    </div>
                </form>
            </section>

            {{-- ===== Alamat (customer) atau akses admin ===== --}}
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
    </div>

    <script>
        // Setelah validasi gagal, gulir ke form yang bermasalah supaya pesan errornya terlihat
        (function() {
            var firstError = document.querySelector('.ac-error');
            if (firstError) {
                var block = firstError.closest('.ac-block');
                if (block) block.scrollIntoView({
                    block: 'center'
                });
            }
        })();
    </script>
@endsection
