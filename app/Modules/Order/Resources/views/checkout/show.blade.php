@extends('layouts.app')

@section('title', 'Checkout')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/order.css') }}?v={{ filemtime(public_path('css/order.css')) }}">
@endpush

@section('content')
    <div class="page-header">
        <h1>Checkout</h1>
    </div>

    <div class="cart-layout">
        <div class="card">
            <h3>Alamat Pengiriman</h3>

            @if ($addresses === [])
                <p>Anda belum punya alamat.</p>
                <a class="btn" href="{{ route('address.create') }}">Tambah alamat dulu</a>
            @else
                <form method="POST" action="{{ route('checkout.store') }}">
                    @csrf
                    @foreach ($addresses as $address)
                        <label class="radio address-option">
                            <input type="radio" name="address_id" value="{{ $address->id }}"
                                @checked($address->isDefault || old('address_id') == $address->id)>
                            <span>
                                <strong>{{ $address->label }}</strong> &middot; {{ $address->recipientName }}
                                ({{ $address->phone }})
                                <br>
                                <span class="muted">{{ $address->formatted() }}</span>
                            </span>
                        </label>
                    @endforeach

                    <div class="checkout-submit">
                        <button class="btn" type="submit">Buat Pesanan</button>
                    </div>
                </form>
            @endif
        </div>

        <aside class="card summary">
            <h3>Ringkasan Pesanan</h3>
            @foreach ($items as $item)
                <div class="summary-row">
                    <span>{{ $item['name'] }} &times; {{ $item['quantity'] }}</span>
                    <span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                </div>
            @endforeach
            <div class="total-box">
                <span>Total</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </aside>
    </div>
@endsection
