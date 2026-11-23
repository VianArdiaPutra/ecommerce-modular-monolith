@php
    $wait = isset($exception) ? $exception->getHeaders()['Retry-After'] ?? null : null;
@endphp
@include('errors._page', [
    'code' => 429,
    'title' => 'Terlalu banyak percobaan',
    'message' => $wait
        ? 'Kamu terlalu sering mencoba dalam waktu singkat. Coba lagi dalam ' . $wait . ' detik.'
        : 'Kamu terlalu sering mencoba dalam waktu singkat. Tunggu sebentar lalu coba lagi.',
])
