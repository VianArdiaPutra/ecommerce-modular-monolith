@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <h2>Checkout</h2>

    <div class="card">
        <h3>Ringkasan Pesanan</h3>
        <table>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item['name'] }} &times; {{ $item['quantity'] }}</td>
                        <td style="text-align:right">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th style="text-align:right">Rp {{ number_format($total, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="card" style="margin-top:16px">
        <h3>Alamat Pengiriman</h3>
        @if ($addresses === [])
            <p>Anda belum punya alamat. <a href="{{ route('address.create') }}">Tambah alamat dulu</a>.</p>
        @else
            <form method="POST" action="{{ route('checkout.store') }}">
                @csrf
                @foreach ($addresses as $address)
                    <label class="radio">
                        <input type="radio" name="address_id" value="{{ $address->id }}" @checked($address->isDefault || old('address_id') == $address->id)>
                        <strong>{{ $address->label }}</strong> - {{ $address->recipientName }} ({{ $address->phone }})<br>
                        <span class="muted">{{ $address->formatted() }}</span>
                    </label>
                @endforeach
                <button class="btn" type="submit">Buat Pesanan</button>
            </form>
        @endif
    </div>
@endsection
