@php
    $isRegister = $mode === 'register';
@endphp

<div class="auth {{ $isRegister ? 'is-register' : '' }}" id="auth"
    style="--auth-photo: url('{{ asset('images/everyday_objects_background.webp') }}')"
    data-login-url="{{ route('login', [], false) }}" data-register-url="{{ route('register', [], false) }}">

    {{-- ===== Nama brand + teks besar (bergeser) ===== --}}
    <section class="auth-hero">
        <div class="auth-hero-brand">KLIMAQU</div>

        <div class="auth-hero-texts">
            <div class="auth-hero-text" data-for="login">
                <h1>Selamat datang kembali</h1>
                <p>Masuk untuk melanjutkan belanja, melihat keranjang, dan memantau pesananmu.</p>
            </div>
            <div class="auth-hero-text" data-for="register">
                <h1>Mulai cerita rumahmu bersama kami</h1>
                <p>Buat akun KLIMAQU dan temukan elektronik, fashion, buku, dan banyak lagi di satu tempat.</p>
            </div>
        </div>

        <p class="auth-hero-slogan">Belanja hangat, semua ada.</p>
    </section>

    {{-- ===== Kartu form (bergeser) ===== --}}
    <section class="auth-form-panel">
        <div class="auth-forms">

            {{-- Form LOGIN --}}
            <div class="auth-form" data-for="login" @if ($isRegister) inert @endif>
                <div class="auth-card">
                    <h2>Masuk</h2>
                    <p class="auth-lead">Senang melihatmu lagi.</p>

                    @unless ($isRegister)
                        @include('customer::auth._alerts')
                    @endunless

                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf
                        <label for="login-email">Email</label>
                        <input id="login-email" type="email" name="email" autocomplete="email"
                            value="{{ $isRegister ? '' : old('email') }}">

                        <label for="login-password">Password</label>
                        <div class="pw-field">
                            <input id="login-password" type="password" name="password" autocomplete="current-password">
                            @include('customer::auth._pw-toggle', ['target' => 'login-password'])
                        </div>

                        <button class="btn" type="submit">Masuk</button>
                    </form>

                    <p class="auth-switch">Belum punya akun?
                        <a href="{{ route('register') }}" data-switch="register">Daftar sekarang</a>
                    </p>
                </div>
            </div>

            {{-- Form REGISTER --}}
            <div class="auth-form" data-for="register" @unless ($isRegister) inert @endunless>
                <div class="auth-card">
                    <h2>Daftar Akun</h2>
                    <p class="auth-lead">Gratis dan cuma sebentar.</p>

                    @if ($isRegister)
                        @include('customer::auth._alerts')
                    @endif

                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf
                        <label for="reg-name">Nama</label>
                        <input id="reg-name" type="text" name="name" autocomplete="name"
                            value="{{ $isRegister ? old('name') : '' }}">

                        <label for="reg-email">Email</label>
                        <input id="reg-email" type="email" name="email" autocomplete="email"
                            value="{{ $isRegister ? old('email') : '' }}">

                        <label for="reg-phone">No. Telepon (opsional)</label>
                        <input id="reg-phone" type="text" name="phone" autocomplete="tel"
                            value="{{ $isRegister ? old('phone') : '' }}">

                        <label for="reg-password">Password</label>
                        <div class="pw-field">
                            <input id="reg-password" type="password" name="password" autocomplete="new-password">
                            @include('customer::auth._pw-toggle', ['target' => 'reg-password'])
                        </div>

                        <label for="reg-password-confirm">Konfirmasi Password</label>
                        <div class="pw-field">
                            <input id="reg-password-confirm" type="password" name="password_confirmation"
                                autocomplete="new-password">
                            @include('customer::auth._pw-toggle', ['target' => 'reg-password-confirm'])
                        </div>

                        <button class="btn" type="submit">Daftar</button>
                    </form>

                    <p class="auth-switch">Sudah punya akun?
                        <a href="{{ route('login') }}" data-switch="login">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>

        <p class="auth-back"><a href="{{ route('catalog.index') }}">&larr; Lihat katalog tanpa masuk</a></p>
    </section>
</div>

<script>
    (function() {
        var root = document.getElementById('auth');
        if (!root) return;

        var urls = {
            login: root.dataset.loginUrl,
            register: root.dataset.registerUrl
        };
        var titles = {
            login: 'Masuk | KLIMAQU',
            register: 'Daftar | KLIMAQU'
        };
        var forms = {
            login: root.querySelector('.auth-form[data-for="login"]'),
            register: root.querySelector('.auth-form[data-for="register"]')
        };

        function setMode(mode, focusFirst) {
            root.classList.toggle('is-register', mode === 'register');
            forms.login.inert = mode !== 'login';
            forms.register.inert = mode !== 'register';
            document.title = titles[mode];
            if (focusFirst) {
                var first = forms[mode].querySelector('input');
                setTimeout(function() {
                    if (first) first.focus({
                        preventScroll: true
                    });
                }, 500);
            }
        }

        function modeFromPath() {
            return location.pathname.replace(/\/+$/, '').slice(-9) === '/register' ? 'register' : 'login';
        }

        // Pindah panel tanpa memuat ulang halaman
        root.querySelectorAll('[data-switch]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                var mode = link.dataset.switch;
                if (root.classList.contains('is-register') === (mode === 'register')) return;
                e.preventDefault();
                history.pushState({
                    mode: mode
                }, '', urls[mode]);
                setMode(mode, true);
            });
        });

        // Tombol back/forward browser
        window.addEventListener('popstate', function() {
            setMode(modeFromPath(), false);
        });

        // Ikon mata: tampilkan/sembunyikan password
        root.querySelectorAll('[data-toggle-pw]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var input = document.getElementById(btn.getAttribute('aria-controls'));
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.setAttribute('aria-pressed', show ? 'true' : 'false');
                btn.setAttribute('aria-label', show ? 'Sembunyikan password' :
                    'Tampilkan password');
            });
        });

        // Aktifkan animasi hanya setelah render pertama
        requestAnimationFrame(function() {
            requestAnimationFrame(function() {
                root.classList.add('is-ready');
            });
        });
    })();
</script>
