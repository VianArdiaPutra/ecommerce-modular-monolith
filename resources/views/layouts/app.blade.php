<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'KLIMAQU') | KLIMAQU</title>
    <link rel="stylesheet" href="{{ asset('css/klimaqu.css') }}?v={{ filemtime(public_path('css/klimaqu.css')) }}">
    @stack('head')
</head>

<body class="@yield('body_class')">
    {{-- Halaman khusus layar penuh (login/register) mengisi section "bare" --}}
    @hasSection('bare')
        @yield('bare')
    @else
        <nav class="navbar">
            <div class="nav-inner">
                <a href="{{ route('catalog.index') }}" class="brand">KLIMAQU</a>
                <span class="spacer"></span>

                <a href="{{ route('catalog.index') }}"
                    class="{{ request()->routeIs('catalog.*') ? 'active' : '' }}">Katalog</a>
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">Admin</a>
                    @else
                        <a href="{{ route('cart.index') }}"
                            class="{{ request()->routeIs('cart.*', 'checkout.*') ? 'active' : '' }}">Keranjang</a>
                        <a href="{{ route('orders.index') }}"
                            class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">Pesanan</a>
                    @endif

                    <details class="profile-menu">
                        <summary class="profile-trigger {{ request()->routeIs('account', 'address.*') ? 'active' : '' }}"
                            aria-label="Menu profil">
                            <span class="profile-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        </summary>
                        <div class="profile-panel">
                            <div class="profile-head">
                                <span
                                    class="profile-avatar profile-avatar-lg">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                                <div>
                                    <strong class="profile-name">{{ auth()->user()->name }}</strong>
                                    <span
                                        class="profile-role">{{ auth()->user()->isAdmin() ? 'Admin' : 'Customer' }}</span>
                                </div>
                            </div>
                            <a href="{{ route('account') }}" class="profile-item">Profil</a>
                            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="margin:0">
                                @csrf
                                <button type="button" class="profile-item profile-item-danger"
                                    data-logout-open>Keluar</button>
                            </form>
                        </div>
                    </details>
                @else
                    <a href="{{ route('login') }}">Masuk</a>
                    <a href="{{ route('register') }}" class="nav-cta">Daftar</a>
                @endauth
            </div>
        </nav>

        <main>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="errors">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="site-footer footer-slim">
            <div class="footer-slim-inner">
                <div class="footer-slim-brand">
                    <strong>KLIMAQU</strong><span>Belanja hangat, semua ada.</span>
                </div>
                <div class="footer-slim-links">
                    <a href="{{ route('catalog.index') }}">Katalog</a>
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}">Admin</a>
                        @else
                            <a href="{{ route('cart.index') }}">Keranjang</a>
                            <a href="{{ route('orders.index') }}">Pesanan</a>
                        @endif
                        <a href="{{ route('account') }}">Profil</a>
                    @else
                        <a href="{{ route('login') }}">Masuk</a>
                        <a href="{{ route('register') }}">Daftar</a>
                    @endauth
                </div>
                <span>&copy; {{ date('Y') }} KLIMAQU</span>
            </div>
        </footer>

        @auth
            {{-- Dialog konfirmasi keluar: tombol "Ya, keluar" mengirim form #logout-form --}}
            <dialog id="logout-dialog" class="confirm-dialog" aria-labelledby="logout-title">
                <div class="confirm-body">
                    <h3 id="logout-title">Yakin ingin keluar?</h3>
                    <p>Kamu perlu masuk lagi untuk melanjutkan belanja.</p>
                    <div class="confirm-actions">
                        <button type="button" class="btn btn-outline" data-logout-cancel>Batal</button>
                        <button type="submit" form="logout-form" class="btn btn-danger">Ya, keluar</button>
                    </div>
                </div>
            </dialog>
            <script>
                (function() {
                    var menu = document.querySelector('.profile-menu');
                    var dialog = document.getElementById('logout-dialog');
                    var logoutBtn = document.querySelector('[data-logout-open]');

                    if (menu) {
                        // Klik di luar menu menutup dropdown
                        document.addEventListener('click', function(e) {
                            if (!menu.contains(e.target)) menu.removeAttribute('open');
                        });
                        // Tombol Esc menutup dropdown
                        document.addEventListener('keydown', function(e) {
                            if (e.key === 'Escape') menu.removeAttribute('open');
                        });
                    }

                    if (!dialog || !logoutBtn) return;

                    // Tombol Keluar: tutup dropdown lalu buka dialog konfirmasi
                    logoutBtn.addEventListener('click', function() {
                        if (menu) menu.removeAttribute('open');
                        dialog.showModal();
                    });

                    dialog.querySelector('[data-logout-cancel]').addEventListener('click', function() {
                        dialog.close();
                    });

                    // Klik area gelap di luar kotak menutup dialog
                    dialog.addEventListener('click', function(e) {
                        if (e.target === dialog) dialog.close();
                    });
                })
                ();
            </script>
        @endauth
    @endif
</body>

</html>
