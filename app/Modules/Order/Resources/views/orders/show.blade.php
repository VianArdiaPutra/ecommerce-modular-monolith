@extends('layouts.app')

@section('title', 'Detail Pesanan')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/order.css') }}?v={{ filemtime(public_path('css/order.css')) }}">
@endpush

@section('content')
    <a class="back-link" href="{{ route('orders.index') }}">&larr; Kembali ke daftar pesanan</a>

    <div class="card">
        <div class="order-head">
            <h1>{{ $order->order_number }}</h1>
            <span class="badge">{{ $order->status }}</span>
        </div>

        <div class="order-info">
            <h4>Dikirim ke</h4>
            <p><strong>{{ $order->shipping_name }}</strong> ({{ $order->shipping_phone }})</p>
            <p>{{ $order->shipping_address }}</p>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="num">Harga Satuan</th>
                        <th class="num">Jumlah</th>
                        <th class="num">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>
                                <div class="item-cell">
                                    <span
                                        class="item-thumb">{{ mb_strtoupper(mb_substr($item->product_name, 0, 1)) }}</span>
                                    <span>{{ $item->product_name }}</span>
                                </div>
                            </td>
                            <td class="num">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="num">{{ $item->quantity }}</td>
                            <td class="num">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-total">
                    <tr>
                        <th colspan="3" class="num">Total</th>
                        <th class="num">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
