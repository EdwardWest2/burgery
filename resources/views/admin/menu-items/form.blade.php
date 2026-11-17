@extends('layouts.admin')
@php $isEdit = $item->exists; @endphp
@section('title', $isEdit ? 'Edit Menu' : 'Tambah Menu')
@section('heading', $isEdit ? 'Edit Menu' : 'Tambah Menu')

@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $isEdit ? route('admin.menu-items.update', $item) : route('admin.menu-items.store') }}">
    @csrf
    @if($isEdit) @method('PUT') @endif
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-a">
                <div class="mb-3"><label class="form-label" for="name">Nama menu *</label><input id="name" name="name" class="form-control" value="{{ old('name', $item->name) }}" maxlength="150" required data-slug-source></div>
                <div class="mb-3"><label class="form-label" for="slug">Slug</label><input id="slug" name="slug" class="form-control" value="{{ old('slug', $item->slug) }}" maxlength="180" placeholder="dibuat otomatis dari nama" data-slug-target></div>
                <div class="mb-3"><label class="form-label" for="description">Deskripsi</label><textarea id="description" name="description" rows="5" class="form-control" maxlength="2000">{{ old('description', $item->description) }}</textarea></div>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label" for="menu_category_id">Kategori *</label>
                        <select id="menu_category_id" name="menu_category_id" class="form-select" required>
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $c)<option value="{{ $c->id }}" @selected((int) old('menu_category_id', $item->menu_category_id) === $c->id)>{{ $c->name }}</option>@endforeach
                        </select>
                        @if($categories->isEmpty())<div class="hint text-danger"><a href="{{ route('admin.menu-categories.index') }}">Buat kategori menu dulu</a>.</div>@endif
                    </div>
                    <div class="col-md-3"><label class="form-label" for="price">Harga (Rp) *</label><input id="price" name="price" type="number" min="0" step="1" class="form-control" value="{{ old('price', $item->price) }}" required><div class="hint">Angka bulat, tanpa titik.</div></div>
                    <div class="col-md-3"><label class="form-label" for="sort_order">Urutan</label><input id="sort_order" name="sort_order" type="number" min="0" class="form-control" value="{{ old('sort_order', $item->sort_order) }}"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-a">
                <h2>Status</h2>
                <div class="form-check form-switch mb-2"><input class="form-check-input" type="checkbox" role="switch" name="is_available" id="is_available" value="1" @checked(old('is_available', $item->is_available))><label class="form-check-label" for="is_available">Tersedia</label></div>
                <div class="form-check form-switch mb-3"><input class="form-check-input" type="checkbox" role="switch" name="is_featured" id="is_featured" value="1" @checked(old('is_featured', $item->is_featured))><label class="form-check-label" for="is_featured">Menu unggulan (tampil di beranda)</label></div>
                <div class="d-grid gap-2"><button class="btn btn-brand" type="submit"><i class="fas fa-floppy-disk me-1"></i>Simpan</button><a class="btn btn-line" href="{{ route('admin.menu-items.index') }}">Batal</a></div>
            </div>
            <div class="card-a">
                <h2>Foto</h2>
                <img id="imgPrev" class="img-prev" src="{{ $item->image ? $item->image_url : '' }}" alt="" style="{{ $item->image ? '' : 'display:none' }}">
                <input class="form-control mb-2" type="file" name="image" accept="image/jpeg,image/png,image/webp" data-preview="imgPrev">
                <div class="hint mb-3">JPG, PNG, atau WebP, maks. 3 MB. Disarankan rasio 5:3.</div>
                <label class="form-label" for="image_alt">Alt text foto</label>
                <input id="image_alt" name="image_alt" class="form-control" value="{{ old('image_alt', $item->image_alt) }}" maxlength="200">
                @if($isEdit && $item->image)<div class="form-check mt-3"><input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1"><label class="form-check-label" for="remove_image">Hapus foto</label></div>@endif
            </div>
        </div>
    </div>
</form>
@endsection
