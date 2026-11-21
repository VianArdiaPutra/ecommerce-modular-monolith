@extends('layouts.app')

@section('title', 'Detail Pesanan')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/order.css') }}?v={{ filemtime(public_path('css/order.css')) }}">
@endpush

@section('content')
    <a class="od-back od-crumb" href="{{ route('orders.index') }}">&larr; Semua pesanan</a>

    <div class="cl-title">
        <h1>{{ $order->order_number }}</h1>
    </div>

    <div class="od-meta">
        @include('order::orders._status', ['status' => $order->status])
        <span>Dibuat {{ $order->created_at->format('d/m/Y H:i') }}</span>
    </div>

    <div class="cl-layout">
        <section aria-label="Isi pesanan">
            <div class="od-icols">
                <span>Produk</span>
                <span class="r">Jumlah</span>
                <span class="r">Subtotal</span>
            </div>

            @foreach ($order->items as $item)
                <div class="od-item">
                    @php $photo = 'images/produk/' . \Illuminate\Support\Str::slug($item->product_name) . '.webp'; @endphp
                    <span class="cl-thumb cl-tone-{{ $loop->index % 4 }}">
                        @if (file_exists(public_path($photo)))
                            <img src="{{ asset($photo) }}" alt="">
                        @else
                            {{ mb_strtoupper(mb_substr($item->product_name, 0, 1)) }}
                        @endif
                    </span>
                    <div class="cl-name">
                        <strong>{{ $item->product_name }}</strong>
                        <span>Rp {{ number_format($item->unit_price, 0, ',', '.') }} per item</span>
                    </div>
                    <span class="od-qty" style="text-align:right">&times; {{ $item->quantity }}</span>
                    <span class="cl-sub">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </section>

        <aside class="cl-summary">
            <h2>Ringkasan</h2>

            <div class="od-ship">
                <p class="od-label">Dikirim ke</p>
                <p><strong>{{ $order->shipping_name }}</strong></p>
                <p>{{ $order->shipping_phone }}</p>
                <p>{{ $order->shipping_address }}</p>
            </div>

            <div class="cl-sum-row">
                <span>Jenis produk</span>
                <span>{{ $order->items->count() }}</span>
            </div>
            <div class="cl-sum-row">
                <span>Total item</span>
                <span>{{ $order->items->sum('quantity') }}</span>
            </div>
            <div class="cl-total">
                <span>Total</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </aside>
    </div>
@endsection
