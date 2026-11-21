@extends('layouts.app')

@section('title', 'Pesanan Berhasil')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/order.css') }}?v={{ filemtime(public_path('css/order.css')) }}">
@endpush

@section('content')
    <section class="ok">
        <span class="ok-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="m5 12.5 4.5 4.5L19 7.5" />
            </svg>
        </span>

        <h1>Pesanan berhasil dibuat</h1>
        <p class="ok-lead">Terima kasih, {{ $order->shipping_name }}. Pesananmu sudah tercatat.</p>

        <div class="ok-card">
            <div class="ok-line">
                <span>Nomor pesanan</span>
                <strong class="ok-no">{{ $order->order_number }}</strong>
            </div>
            <div class="ok-line">
                <span>Status</span>
                <span>@include('order::orders._status', ['status' => $order->status])</span>
            </div>
            <div class="ok-line">
                <span>Total</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="ok-line">
                <span>Dikirim ke</span>
                <span>{{ $order->shipping_name }}<br>{{ $order->shipping_address }}</span>
            </div>
        </div>

        <h2 class="ok-steps-title">Langkah berikutnya</h2>
        <ol class="ok-steps">
            <li><strong>Catat nomor pesananmu.</strong> Nomor di atas adalah referensi pesananmu.</li>
            <li><strong>Pantau di menu Pesanan.</strong> Rincian barang, alamat, dan total bisa dilihat kapan saja.</li>
            <li><strong>Lanjut belanja.</strong> Produk lainnya menunggu di katalog.</li>
        </ol>

        <div class="ok-actions">
            <a class="cl-btn cl-btn-inline" href="{{ route('orders.index') }}">Lihat pesanan saya</a>
            <a href="{{ route('orders.show', $order->id) }}">Detail pesanan ini</a>
            <a href="{{ route('catalog.index') }}">Lanjut belanja</a>
        </div>
    </section>
@endsection
