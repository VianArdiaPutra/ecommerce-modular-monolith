@csrf

<label>Nama Produk</label>
<input type="text" name="name" value="{{ old('name', $product->name) }}">

<label>Deskripsi</label>
<input type="text" name="description" value="{{ old('description', $product->description) }}">

<label>Harga (Rp)</label>
<input type="number" name="price" min="0" step="1"
    value="{{ old('price', $product->exists ? (int) $product->price : '') }}">

<label>Stok</label>
<input type="number" name="stock" min="0" value="{{ old('stock', $product->exists ? $product->stock : 0) }}">

<label>Kategori</label>
@php
    $selected = old('categories', $product->exists ? $product->categories->pluck('id')->all() : []);
@endphp
@foreach ($categories as $category)
    <label class="radio">
        <input type="checkbox" name="categories[]" value="{{ $category->id }}" @checked(in_array($category->id, $selected))>
        {{ $category->name }}
    </label>
@endforeach

<label class="radio">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true))>
    Produk aktif (tampil di katalog)
</label>

<button class="btn" type="submit">Simpan</button>
<a href="{{ route('admin.products.index') }}" style="margin-left:12px">Batal</a>
