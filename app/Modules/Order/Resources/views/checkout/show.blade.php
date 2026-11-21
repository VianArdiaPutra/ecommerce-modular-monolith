@extends('layouts.app')

@section('title', 'Checkout')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/order.css') }}?v={{ filemtime(public_path('css/order.css')) }}">
@endpush

@section('content')
    @php
        $hasAddress = $addresses !== [];
        $defaultAddress = collect($addresses)->firstWhere('isDefault', true);
        $selectedId = (int) old(
            'address_id',
            $defaultAddress ? $defaultAddress->id : ($hasAddress ? $addresses[0]->id : 0),
        );
    @endphp

    <div class="cl-title">
        <h1>Checkout</h1>
        <a class="od-back" href="{{ route('cart.index') }}">&larr; Kembali ke keranjang</a>
    </div>

    <div class="cl-layout">
        <section>
            <h2 class="co-head">Alamat pengiriman</h2>

            @if (!$hasAddress)
                <div class="co-empty">
                    <p>Kamu belum punya alamat pengiriman.</p>
                    <a class="cl-btn cl-btn-inline" href="{{ route('address.create') }}">Tambah alamat</a>
                </div>
            @else
                <form id="checkout-form" class="co-addrs" method="POST" action="{{ route('checkout.store') }}">
                    @csrf
                    @foreach ($addresses as $address)
                        <label class="co-addr">
                            <input type="radio" name="address_id" value="{{ $address->id }}"
                                data-label="{{ $address->label }} - {{ $address->recipientName }}"
                                @checked($selectedId === $address->id)>
                            <span class="co-addr-body">
                                <span class="co-addr-top">
                                    <strong>{{ $address->label }}</strong>
                                    @if ($address->isDefault)
                                        <span class="co-tag">Utama</span>
                                    @endif
                                </span>
                                <span>{{ $address->recipientName }} &middot; {{ $address->phone }}</span>
                                <span class="co-addr-text">{{ $address->formatted() }}</span>
                            </span>
                        </label>
                    @endforeach
                </form>
                <a class="co-add-link" href="{{ route('address.create') }}">+ Tambah alamat baru</a>
            @endif
        </section>

        <aside class="cl-summary">
            <h2>Ringkasan pesanan</h2>
            @foreach ($items as $item)
                <div class="co-line">
                    <span>{{ $item['name'] }} &times; {{ $item['quantity'] }}</span>
                    <span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                </div>
            @endforeach
            <div class="co-line">
                <span>Subtotal</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <div class="co-line">
                <span>Ongkir</span>
                <span class="co-free">Gratis</span>
            </div>
            <div class="cl-total">
                <span>Total</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            @if ($hasAddress)
                <button type="button" class="cl-btn" id="place-order">Buat pesanan</button>
            @else
                <p class="co-note">Tambahkan alamat dulu untuk membuat pesanan.</p>
            @endif
        </aside>
    </div>

    @if ($hasAddress)
        {{-- Dialog konfirmasi sebelum pesanan dibuat --}}
        <dialog id="order-dialog" class="confirm-dialog" aria-labelledby="order-title">
            <div class="confirm-body">
                <h3 id="order-title">Buat pesanan sekarang?</h3>
                <p>Pesanan senilai <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong> akan dikirim ke
                    <strong data-order-address></strong>.
                </p>
                <p>Pastikan alamat dan jumlah barang sudah benar.</p>
                <div class="confirm-actions">
                    <button type="button" class="btn btn-outline" data-order-cancel>Periksa lagi</button>
                    <button type="button" class="btn" data-order-ok>Ya, buat pesanan</button>
                </div>
            </div>
        </dialog>

        <script>
            (function() {
                var form = document.getElementById('checkout-form');
                var dialog = document.getElementById('order-dialog');
                var openBtn = document.getElementById('place-order');
                if (!form || !dialog || !openBtn) return;

                var addrEl = dialog.querySelector('[data-order-address]');
                var okBtn = dialog.querySelector('[data-order-ok]');
                var cancelBtn = dialog.querySelector('[data-order-cancel]');

                openBtn.addEventListener('click', function() {
                    var checked = form.querySelector('input[name="address_id"]:checked');
                    if (!checked) {
                        form.querySelector('input[name="address_id"]').focus();
                        return;
                    }
                    addrEl.textContent = checked.dataset.label;
                    dialog.showModal();
                });

                cancelBtn.addEventListener('click', function() {
                    dialog.close();
                });

                // Klik area gelap menutup dialog
                dialog.addEventListener('click', function(e) {
                    if (e.target === dialog) dialog.close();
                });

                // Kirim form; tombol dikunci supaya klik ganda tidak membuat dua pesanan
                okBtn.addEventListener('click', function() {
                    okBtn.disabled = true;
                    okBtn.textContent = 'Memproses...';
                    openBtn.disabled = true;
                    form.submit();
                });

                // Tombol Back dari halaman lain: kembalikan tombol ke keadaan semula
                window.addEventListener('pageshow', function(e) {
                    if (e.persisted) {
                        okBtn.disabled = false;
                        okBtn.textContent = 'Ya, buat pesanan';
                        openBtn.disabled = false;
                    }
                });
            })();
        </script>
    @endif
@endsection
