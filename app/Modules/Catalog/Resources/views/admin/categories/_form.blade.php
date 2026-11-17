@csrf

<label>Nama Kategori</label>
<input type="text" name="name" value="{{ old('name', $category->name) }}">

<button class="btn" type="submit">Simpan</button>
<a href="{{ route('admin.categories.index') }}" style="margin-left:12px">Batal</a>
