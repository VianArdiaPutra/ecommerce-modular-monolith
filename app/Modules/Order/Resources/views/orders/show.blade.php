@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
    <div class="card">
        <h2>{{ $order->order_number }}</h2>
        <p>Status: <span class="badge">{{ $order->status }}</span></p>
        <p>Dikirim ke: <strong>{{ $order->shipping_name }}</strong> ({{ $order->shipping_phone }})<br>
            {{ $order->shipping_address }}</p>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right">Total</th>
                    <th>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <p><a href="{{ route('orders.index') }}">&larr; Kembali ke daftar pesanan</a></p>
    </div>
@endsection
