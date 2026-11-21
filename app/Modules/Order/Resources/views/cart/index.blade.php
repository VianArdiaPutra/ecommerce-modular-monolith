@extends('layouts.app')

@section('title', 'Keranjang')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/order.css') }}?v={{ filemtime(public_path('css/order.css')) }}">
@endpush

@section('content')
    <div class="cl-title">
        <h1>Keranjang belanja</h1>
        @if ($items !== [])
            <span>{{ count($items) }} produk</span>
        @endif
    </div>

    @if ($items === [])
        <div class="cl-empty">
            <h2>Keranjangmu masih kosong</h2>
            <p>Temukan produk yang kamu suka di katalog.</p>
            <a class="cl-btn cl-btn-inline" href="{{ route('catalog.index') }}">Lihat katalog</a>
        </div>
    @else
        <div class="cl-layout">
            <section class="cl-list" aria-label="Isi keranjang">
                <div class="cl-cols">
                    <span>Produk</span>
                    <span>Jumlah</span>
                    <span class="r">Subtotal</span>
                    <span></span>
                </div>

                @foreach ($items as $item)
                    <div class="cl-row">
                        @php $photo = 'images/produk/' . \Illuminate\Support\Str::slug($item['name']) . '.webp'; @endphp
                        <span class="cl-thumb cl-tone-{{ $loop->index % 4 }}">
                            @if (file_exists(public_path($photo)))
                                <img src="{{ asset($photo) }}" alt="">
                            @else
                                {{ mb_strtoupper(mb_substr($item['name'], 0, 1)) }}
                            @endif
                        </span>

                        <div class="cl-name">
                            <strong>{{ $item['name'] }}</strong>
                            <span>Rp {{ number_format($item['price'], 0, ',', '.') }} per item</span>
                        </div>

                        <form method="POST" action="{{ route('cart.update', $item['product_id']) }}" class="cl-qty"
                            data-qty-form="{{ $item['name'] }}">
                            @csrf
                            @method('PATCH')
                            <div class="cl-stepper">
                                <button type="button" data-step="-1" aria-label="Kurangi jumlah">&minus;</button>
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0"
                                    aria-label="Jumlah {{ $item['name'] }}">
                                <button type="button" data-step="1" aria-label="Tambah jumlah">+</button>
                            </div>
                            <button type="submit" class="cl-ok">OK</button>
                        </form>

                        <span class="cl-sub">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>

                        <form method="POST" action="{{ route('cart.remove', $item['product_id']) }}" class="cl-del"
                            data-confirm-delete="{{ $item['name'] }}">
                            @csrf
                            @method('DELETE')
                            <button class="cl-remove" type="submit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path
                                        d="M4 7h16M10 11v6M14 11v6M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-12M9 7V4h6v3" />
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                @endforeach
            </section>

            <aside class="cl-summary">
                <h2>Ringkasan</h2>
                <div class="cl-sum-row">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="cl-sum-row">
                    <span>Ongkir</span>
                    <span class="cl-muted">Dihitung di checkout</span>
                </div>
                <div class="cl-total">
                    <span>Total</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <a class="cl-btn" href="{{ route('checkout.show') }}">Lanjut ke checkout</a>
                <a class="cl-link" href="{{ route('catalog.index') }}">&larr; Lanjut belanja</a>
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
                        ask('\u201C' + form.dataset.confirmDelete + '\u201D akan dihapus dari keranjang.',
                            form, null);
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

                    // Tombol - dan +
                    form.querySelectorAll('[data-step]').forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            var value = parseInt(input.value, 10);
                            if (isNaN(value)) value = Number(input.defaultValue);
                            value += parseInt(btn.dataset.step, 10);
                            if (value < 0) value = 0;
                            input.value = value;
                            sync();
                        });
                    });

                    form.addEventListener('submit', function(e) {
                        if (input.value !== '' && Number(input.value) === 0) {
                            e.preventDefault();
                            ask('\u201C' + form.dataset.qtyForm +
                                '\u201D akan dihapus dari keranjang karena jumlahnya 0.', form,
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
