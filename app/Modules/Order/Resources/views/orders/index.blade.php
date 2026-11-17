@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
    <h2>Pesanan Saya</h2>

    @if ($orders->isEmpty())
        <div class="card">Belum ada pesanan.</div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td><a href="{{ route('orders.show', $order->id) }}">{{ $order->order_number }}</a></td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td><span class="badge">{{ $order->status }}</span></td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
