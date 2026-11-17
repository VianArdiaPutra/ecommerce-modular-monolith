@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
    <h2>Keranjang Belanja</h2>

    @if ($items === [])
        <div class="card">
            Keranjang masih kosong. <a href="{{ route('catalog.index') }}">Lihat katalog</a>
        </div>
    @else
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td>
                                <form method="POST" action="{{ route('cart.update', $item['product_id']) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0"
                                        style="width:80px" onchange="this.form.submit()">
                                </form>
                            </td>
                            <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            <td>
                                <form method="POST" action="{{ route('cart.remove', $item['product_id']) }}"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-small danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" style="text-align:right">Total</th>
                        <th colspan="2">Rp {{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
            <a class="btn" href="{{ route('checkout.show') }}">Lanjut ke Checkout</a>
        </div>
    @endif
@endsection
