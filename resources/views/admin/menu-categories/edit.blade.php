@extends('layouts.admin')
@section('title', 'Edit Kategori Menu')
@section('heading', 'Edit Kategori Menu')

@section('content')
<div class="card-a" style="max-width:560px;">
    <form method="POST" action="{{ route('admin.menu-categories.update', $category) }}">
        @csrf @method('PUT')
        <div class="mb-3"><label class="form-label" for="name">Nama *</label><input id="name" name="name" class="form-control" value="{{ old('name', $category->name) }}" maxlength="100" required></div>
        <div class="mb-3"><label class="form-label" for="sort_order">Urutan</label><input id="sort_order" name="sort_order" type="number" min="0" class="form-control" value="{{ old('sort_order', $category->sort_order) }}"></div>
        <button class="btn btn-brand" type="submit">Simpan</button>
        <a class="btn btn-line" href="{{ route('admin.menu-categories.index') }}">Batal</a>
    </form>
</div>
@endsection
