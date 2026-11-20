@csrf

<label for="name">Nama Kategori</label>
<input id="name" type="text" name="name" value="{{ old('name', $category->name) }}"
    class="@error('name') is-invalid @enderror">
@error('name')
    <p class="field-error">{{ $message }}</p>
@enderror

<div class="form-actions" style="margin-top:20px">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-outline" href="{{ route('admin.categories.index') }}">Batal</a>
</div>
