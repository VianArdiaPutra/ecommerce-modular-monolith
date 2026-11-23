<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code }} {{ $title }} | KLIMAQU</title>
    <link rel="stylesheet" href="{{ asset('css/klimaqu.css') }}?v={{ filemtime(public_path('css/klimaqu.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/errors.css') }}?v={{ filemtime(public_path('css/errors.css')) }}">
</head>

<body>
    <main class="err-wrap">
        <p class="err-brand">KLIMAQU</p>

        <div class="err-card" role="alert">
            <div class="err-top">
                <span class="err-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                        stroke-linecap="round">
                        <path d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </span>
            </div>

            <div class="err-body">
                <h1>{{ $title }}</h1>
                <p class="err-code">Kode kesalahan {{ $code }}</p>
                <p class="err-msg">{{ $message }}</p>
                <a class="btn btn-danger" href="{{ route('catalog.index') }}">Ke katalog</a>
                <a class="err-link" href="{{ route('login') }}">Halaman masuk</a>
            </div>
        </div>
    </main>
</body>

</html>
