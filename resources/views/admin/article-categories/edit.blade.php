@extends('layouts.admin')
@section('title', 'Edit Kategori')
@section('heading', 'Edit Kategori Artikel')

@section('content')
<div class="card-a" style="max-width:560px;">
    <form method="POST" action="{{ route('admin.article-categories.update', $category) }}">
        @csrf @method('PUT')
        <div class="mb-3"><label class="form-label" for="name">Nama *</label><input id="name" name="name" class="form-control" value="{{ old('name', $category->name) }}" maxlength="100" required></div>
        <div class="mb-3"><label class="form-label" for="description">Deskripsi</label><textarea id="description" name="description" rows="3" class="form-control" maxlength="500">{{ old('description', $category->description) }}</textarea></div>
        <p class="hint">{{ $category->articles_count }} artikel memakai kategori ini. Mengubah nama juga mengubah slug kategori.</p>
        <button class="btn btn-brand" type="submit">Simpan</button>
        <a class="btn btn-line" href="{{ route('admin.article-categories.index') }}">Batal</a>
    </form>
</div>
@endsection
