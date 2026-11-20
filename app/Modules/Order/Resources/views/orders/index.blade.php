@extends('layouts.app')

@section('title', 'Pesanan Saya')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/order.css') }}?v={{ filemtime(public_path('css/order.css')) }}">
@endpush

@section('content')
    <div class="page-header">
        <h1>Pesanan Saya</h1>
    </div>

    @if ($orders->isEmpty())
        <div class="card empty-state">
            <p>Belum ada pesanan.</p>
            <a class="btn" href="{{ route('catalog.index') }}">Mulai belanja</a>
        </div>
    @else
        <div class="card table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="num">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td><a href="{{ route('orders.show', $order->id) }}">{{ $order->order_number }}</a></td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td><span class="badge">{{ $order->status }}</span></td>
                            <td class="num">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
