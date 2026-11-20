@extends('layouts.app')

@section('title', 'Tambah Alamat')

@section('content')
    <a class="back-link" href="{{ route('account') }}">&larr; Kembali ke akun</a>

    <div class="card form-card">
        <h1>Tambah Alamat</h1>
        <p class="muted">Alamat ini bisa dipilih saat checkout.</p>

        <form method="POST" action="{{ route('address.store') }}">
            @csrf
            <div class="field-grid">
                <div>
                    <label for="label">Label (Rumah, Kantor, dll.)</label>
                    <input id="label" type="text" name="label" value="{{ old('label') }}"
                        class="@error('label') is-invalid @enderror">
                    @error('label')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="recipient_name">Nama Penerima</label>
                    <input id="recipient_name" type="text" name="recipient_name" value="{{ old('recipient_name') }}"
                        class="@error('recipient_name') is-invalid @enderror">
                    @error('recipient_name')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone">No. Telepon</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                        class="@error('phone') is-invalid @enderror">
                    @error('phone')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="city">Kota</label>
                    <input id="city" type="text" name="city" value="{{ old('city') }}"
                        class="@error('city') is-invalid @enderror">
                    @error('city')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="span-2">
                    <label for="full_address">Alamat Lengkap</label>
                    <input id="full_address" type="text" name="full_address" value="{{ old('full_address') }}"
                        class="@error('full_address') is-invalid @enderror">
                    @error('full_address')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="postal_code">Kode Pos</label>
                    <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code') }}"
                        class="@error('postal_code') is-invalid @enderror">
                    @error('postal_code')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button class="btn" type="submit">Simpan Alamat</button>
                <a class="btn btn-outline" href="{{ route('account') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
