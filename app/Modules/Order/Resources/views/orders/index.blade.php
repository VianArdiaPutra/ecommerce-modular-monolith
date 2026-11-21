@extends('layouts.app')

@section('title', 'Pesanan Saya')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/order.css') }}?v={{ filemtime(public_path('css/order.css')) }}">
@endpush

@section('content')
    <div class="cl-title">
        <h1>Pesanan saya</h1>
        @if (!$orders->isEmpty())
            <span>{{ $orders->count() }} pesanan</span>
        @endif
    </div>

    @if ($orders->isEmpty())
        <div class="cl-empty">
            <h2>Belum ada pesanan</h2>
            <p>Pesananmu akan muncul di sini setelah checkout.</p>
            <a class="cl-btn cl-btn-inline" href="{{ route('catalog.index') }}">Mulai belanja</a>
        </div>
    @else
        <div class="od-cols">
            <span>No. pesanan</span>
            <span>Tanggal</span>
            <span>Status</span>
            <span class="r">Total</span>
        </div>

        @foreach ($orders as $order)
            <a class="od-row" href="{{ route('orders.show', $order->id) }}">
                <span class="od-no">{{ $order->order_number }}</span>
                <span class="od-date">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                <span>@include('order::orders._status', ['status' => $order->status])</span>
                <span class="od-total">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </a>
        @endforeach
    @endif
@endsection
