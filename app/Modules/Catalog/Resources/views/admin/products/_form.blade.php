@csrf

<div class="field-grid">
    <div class="span-2">
        <label for="name">Nama Produk</label>
        <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}"
            class="@error('name') is-invalid @enderror">
        @error('name')
            <p class="field-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="span-2">
        <label for="description">Deskripsi</label>
        <textarea id="description" name="description" class="@error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
        @error('description')
            <p class="field-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="price">Harga (Rp)</label>
        <input id="price" type="number" name="price" min="0" step="1"
            value="{{ old('price', $product->exists ? (int) $product->price : '') }}"
            class="@error('price') is-invalid @enderror">
        @error('price')
            <p class="field-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="stock">Stok</label>
        <input id="stock" type="number" name="stock" min="0"
            value="{{ old('stock', $product->exists ? $product->stock : 0) }}"
            class="@error('stock') is-invalid @enderror">
        @error('stock')
            <p class="field-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="span-2">
        <p class="field-label">Kategori</p>
        @php
            $selected = old('categories', $product->exists ? $product->categories->pluck('id')->all() : []);
        @endphp
        <div class="check-grid">
            @foreach ($categories as $category)
                <label class="radio">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" @checked(in_array($category->id, $selected))>
                    {{ $category->name }}
                </label>
            @endforeach
        </div>
        @error('categories')
            <p class="field-error">{{ $message }}</p>
        @enderror
        @error('categories.*')
            <p class="field-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="span-2">
        <label class="radio">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true))>
            Produk aktif (tampil di katalog)
        </label>
    </div>
</div>

<div class="form-actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-outline" href="{{ route('admin.products.index') }}">Batal</a>
</div>
