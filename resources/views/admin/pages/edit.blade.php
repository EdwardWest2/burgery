@extends('layouts.admin')
@section('title', 'Edit '.$page->title)
@section('heading', 'Edit Halaman: '.$page->title)
@section('actions')<a class="btn btn-line btn-sm" href="{{ url($page->slug) }}" target="_blank" rel="noopener"><i class="fas fa-arrow-up-right-from-square me-1"></i>Lihat halaman</a>@endsection

@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ route('admin.pages.update', $page) }}">
    @csrf @method('PUT')
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card-a">
                <div class="mb-3"><label class="form-label" for="title">Judul *</label><input id="title" name="title" class="form-control" value="{{ old('title', $page->title) }}" maxlength="200" required></div>
                <div class="mb-3"><label class="form-label" for="excerpt">Ringkasan</label><textarea id="excerpt" name="excerpt" rows="2" class="form-control" maxlength="500">{{ old('excerpt', $page->excerpt) }}</textarea></div>
                <div><label class="form-label" for="body">Isi halaman *</label>
                    @include('admin.partials.editor', ['name' => 'body', 'id' => 'body', 'value' => old('body', $page->body)])</div>
            </div>
            <div class="card-a">
                <h2>SEO</h2>
                <div class="mb-3"><label class="form-label" for="seo_title">SEO title</label><input id="seo_title" name="seo_title" class="form-control" value="{{ old('seo_title', $page->seo_title) }}" maxlength="200"></div>
                <div><label class="form-label" for="meta_description">Meta description</label><textarea id="meta_description" name="meta_description" rows="2" class="form-control" maxlength="320">{{ old('meta_description', $page->meta_description) }}</textarea></div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card-a">
                <button class="btn btn-brand w-100" type="submit"><i class="fas fa-floppy-disk me-1"></i>Simpan halaman</button>
            </div>
            <div class="card-a">
                <h2>Foto pendukung</h2>
                <img id="imgPrev" class="img-prev" src="{{ $page->image ? media_url($page->image) : '' }}" alt="" style="{{ $page->image ? '' : 'display:none' }}">
                <input class="form-control mb-2" type="file" name="image" accept="image/jpeg,image/png,image/webp" data-preview="imgPrev">
                <label class="form-label mt-2" for="image_alt">Alt text</label>
                <input id="image_alt" name="image_alt" class="form-control" value="{{ old('image_alt', $page->image_alt) }}" maxlength="200">
                @if($page->image)<div class="form-check mt-3"><input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1"><label class="form-check-label" for="remove_image">Hapus foto</label></div>@endif
            </div>
        </div>
    </div>
</form>
@endsection
