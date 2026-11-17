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
            max-width: 720px;
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

        input {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn {
            margin-top: 16px;
            padding: 10px 16px;
            background: #2563eb;
            color: #fff;
            border: 0;
            border-radius: 4px;
            cursor: pointer;
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
    </style>
</head>

<body>
    <nav>
        <strong>Toko Online</strong>
        <span class="spacer"></span>
        @auth
            <a href="{{ route('account') }}">Akun</a>
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}">Admin</a>
            @endif
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
