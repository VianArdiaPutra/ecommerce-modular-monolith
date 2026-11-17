<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Toko Online')</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, sans-serif;
            margin: 0;
            background: #f4f5f7;
            color: #222;
        }

        nav {
            background: #1f2937;
            color: #fff;
            padding: 12px 24px;
            display: flex;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
        }

        nav a,
        nav button {
            color: #fff;
            text-decoration: none;
            background: none;
            border: 0;
            cursor: pointer;
            font-size: 1rem;
        }

        nav .spacer {
            flex: 1;
        }

        main {
            max-width: 960px;
            margin: 32px auto;
            padding: 0 16px;
        }

        .card {
            background: #fff;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: 600;
        }

        label.radio {
            font-weight: 400;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type=radio] {
            width: auto;
            margin-right: 8px;
        }

        .btn {
            display: inline-block;
            margin-top: 12px;
            padding: 10px 16px;
            background: #2563eb;
            color: #fff;
            border: 0;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-small {
            padding: 6px 10px;
            background: #2563eb;
            color: #fff;
            border: 0;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-small.danger {
            background: #dc2626;
        }

        form.inline {
            display: flex;
            gap: 6px;
            align-items: center;
            margin: 0;
        }

        form.inline input {
            margin: 0;
        }

        .alert {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 16px;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        ul.errors {
            margin: 0;
            padding-left: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 16px;
        }

        .product h3 {
            margin: 0 0 8px;
        }

        .product a {
            color: #111;
            text-decoration: none;
        }

        .price {
            font-weight: 700;
            color: #166534;
            margin: 8px 0;
        }

        .muted {
            color: #6b7280;
            font-size: .9rem;
            margin: 4px 0;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            background: #e5e7eb;
            border-radius: 999px;
            font-size: .8rem;
        }

        .filter {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
        }

        .filter input,
        .filter select {
            margin: 0;
        }

        .filter .btn {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #eee;
            text-align: left;
            vertical-align: middle;
        }

        table {
            margin-bottom: 8px;
        }
    </style>
</head>

<body>
    <nav>
        <strong>Toko Online</strong>
        <a href="{{ route('catalog.index') }}">Katalog</a>
        <span class="spacer"></span>
        @auth
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}">Admin</a>
            @else
                <a href="{{ route('cart.index') }}">Keranjang</a>
                <a href="{{ route('orders.index') }}">Pesanan</a>
            @endif
            <a href="{{ route('account') }}">Akun</a>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit">Keluar ({{ auth()->user()->name }})</button>
            </form>
        @else
            <a href="{{ route('login') }}">Masuk</a>
            <a href="{{ route('register') }}">Daftar</a>
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
</body>

</html>
