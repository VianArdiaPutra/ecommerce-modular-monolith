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
            <a href="{{ route('catalog.index') }}" class="brand">
                KLIMAQU
            </a>
            <a href="{{ route('catalog.index') }}"
                class="{{ request()->routeIs('catalog.*') ? 'active' : '' }}">Katalog</a>
            <span class="spacer"></span>
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
                <a href="{{ route('account') }}"
                    class="{{ request()->routeIs('account', 'address.*') ? 'active' : '' }}">Akun</a>
                <form id="logout-form" method="POST" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="button" class="nav-logout" data-logout-open>Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}">Masuk</a>
                <a href="{{ route('register') }}" class="nav-cta">Daftar</a>
            @endauth
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

        <footer class="site-footer">
            <strong>KLIMAQU</strong> &middot; Rumah hangat, mulai dari sini.
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
                    var dialog = document.getElementById('logout-dialog');
                    var openBtn = document.querySelector('[data-logout-open]');
                    if (!dialog || !openBtn) return;

                    openBtn.addEventListener('click', function() {
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
