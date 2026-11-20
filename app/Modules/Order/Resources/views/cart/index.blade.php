@extends('layouts.app')

@section('title', 'Keranjang')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/order.css') }}?v={{ filemtime(public_path('css/order.css')) }}">
@endpush

@section('content')
    <div class="page-header">
        <h1>Keranjang Belanja</h1>
    </div>

    @if ($items === [])
        <div class="card empty-state">
            <p>Keranjang masih kosong.</p>
            <a class="btn" href="{{ route('catalog.index') }}">Lihat katalog</a>
        </div>
    @else
        <div class="cart-layout">
            <div class="card table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="num">Harga</th>
                            <th>Jumlah</th>
                            <th class="num">Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    <div class="item-cell">
                                        <span class="item-thumb">{{ mb_strtoupper(mb_substr($item['name'], 0, 1)) }}</span>
                                        <span>{{ $item['name'] }}</span>
                                    </div>
                                </td>
                                <td class="num">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('cart.update', $item['product_id']) }}"
                                        class="inline" data-qty-form="{{ $item['name'] }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0"
                                            class="qty-input" aria-label="Jumlah {{ $item['name'] }}">
                                        <button type="submit" class="btn-small qty-ok">OK</button>
                                    </form>
                                </td>
                                <td class="num">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                <td class="num">
                                    <form method="POST" action="{{ route('cart.remove', $item['product_id']) }}"
                                        class="inline" data-confirm-delete="{{ $item['name'] }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-small danger" type="submit">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <aside class="card summary">
                <h3>Ringkasan</h3>
                <div class="summary-row">
                    <span>Jumlah jenis produk</span>
                    <span>{{ count($items) }}</span>
                </div>
                <div class="total-box">
                    <span>Total</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <a class="btn" href="{{ route('checkout.show') }}">Lanjut ke Checkout</a>
            </aside>
        </div>

        {{-- Dialog konfirmasi hapus (dipakai tombol Hapus dan jumlah 0) --}}
        <dialog id="confirm-dialog" class="confirm-dialog" aria-labelledby="confirm-title">
            <div class="confirm-body">
                <h3 id="confirm-title">Yakin ingin menghapus?</h3>
                <p data-confirm-text></p>
                <div class="confirm-actions">
                    <button type="button" class="btn btn-outline" data-confirm-cancel>Batal</button>
                    <button type="button" class="btn btn-danger" data-confirm-ok>Ya, hapus</button>
                </div>
            </div>
        </dialog>

        <script>
            (function() {
                var dialog = document.getElementById('confirm-dialog');
                if (!dialog) return;

                var textEl = dialog.querySelector('[data-confirm-text]');
                var okBtn = dialog.querySelector('[data-confirm-ok]');
                var cancelBtn = dialog.querySelector('[data-confirm-cancel]');
                var pending = null; // { form, onCancel }

                function ask(message, form, onCancel) {
                    pending = {
                        form: form,
                        onCancel: onCancel
                    };
                    textEl.textContent = message;
                    dialog.showModal();
                }

                // "Ya, hapus": kirim form yang menunggu konfirmasi
                okBtn.addEventListener('click', function() {
                    var p = pending;
                    pending = null;
                    dialog.close();
                    if (p) p.form.submit();
                });

                cancelBtn.addEventListener('click', function() {
                    dialog.close();
                });

                // Klik area gelap menutup dialog
                dialog.addEventListener('click', function(e) {
                    if (e.target === dialog) dialog.close();
                });

                // Ditutup tanpa konfirmasi (Batal, Esc, area gelap): batalkan aksi
                dialog.addEventListener('close', function() {
                    if (pending && pending.onCancel) pending.onCancel();
                    pending = null;
                });

                // Tombol Hapus
                document.querySelectorAll('form[data-confirm-delete]').forEach(function(form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        ask('“' + form.dataset.confirmDelete + '” akan dihapus dari keranjang.', form,
                        null);
                    });
                });

                // Form jumlah: tombol OK muncul saat angka berubah; jumlah 0 minta konfirmasi
                document.querySelectorAll('form[data-qty-form]').forEach(function(form) {
                    var input = form.querySelector('input[name="quantity"]');

                    function sync() {
                        var changed = input.value !== '' && Number(input.value) !== Number(input.defaultValue);
                        form.classList.toggle('is-changed', changed);
                    }

                    input.addEventListener('input', sync);

                    form.addEventListener('submit', function(e) {
                        if (input.value !== '' && Number(input.value) === 0) {
                            e.preventDefault();
                            ask('“' + form.dataset.qtyForm +
                                '” akan dihapus dari keranjang karena jumlahnya 0.', form,
                                function() {
                                    input.value = input.defaultValue;
                                    sync();
                                });
                        }
                    });
                });
            })();
        </script>
    @endif
@endsection
