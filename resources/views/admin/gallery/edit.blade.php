@extends('layouts.admin')
@section('title', 'Edit Foto')
@section('heading', 'Edit Foto Galeri')

@section('content')
<div class="card-a" style="max-width:640px;">
    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.gallery.update', $photo) }}">
        @csrf @method('PUT')
        <img id="imgPrev" class="img-prev" src="{{ $photo->image_url }}" alt="{{ $photo->alt_text }}">
        <div class="mb-3"><label class="form-label" for="image">Ganti foto (opsional)</label><input id="image" type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp" data-preview="imgPrev"></div>
        <div class="mb-3"><label class="form-label" for="caption">Caption</label><input id="caption" name="caption" class="form-control" maxlength="150" value="{{ old('caption', $photo->caption) }}"></div>
        <div class="mb-3"><label class="form-label" for="alt_text">Alt text</label><input id="alt_text" name="alt_text" class="form-control" maxlength="200" value="{{ old('alt_text', $photo->alt_text) }}"><div class="hint">Deskripsi gambar untuk pembaca layar dan SEO.</div></div>
        <div class="mb-3"><label class="form-label" for="sort_order">Urutan tampil</label><input id="sort_order" name="sort_order" type="number" min="0" class="form-control" value="{{ old('sort_order', $photo->sort_order) }}"></div>
        <button class="btn btn-brand" type="submit">Simpan</button> <a class="btn btn-line" href="{{ route('admin.gallery.index') }}">Batal</a>
    </form>
</div>
@endsection
