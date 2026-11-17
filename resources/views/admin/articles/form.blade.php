@extends('layouts.admin')
@php $isEdit = $article->exists; @endphp
@section('title', $isEdit ? 'Edit Artikel' : 'Artikel Baru')
@section('heading', $isEdit ? 'Edit Artikel' : 'Artikel Baru')
@section('actions')
    @if($isEdit)<a href="{{ route('admin.articles.preview', $article) }}" target="_blank" rel="noopener" class="btn btn-line btn-sm"><i class="fas fa-eye me-1"></i>Pratinjau</a>
        @if($article->isLive())<a href="{{ route('articles.show', $article->slug) }}" target="_blank" rel="noopener" class="btn btn-line btn-sm"><i class="fas fa-arrow-up-right-from-square me-1"></i>Lihat di website</a>@endif
    @endif
@endsection

@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $isEdit ? route('admin.articles.update', $article) : route('admin.articles.store') }}">
    @csrf
    @if($isEdit) @method('PUT') @endif
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card-a">
                <div class="mb-3">
                    <label class="form-label" for="title">Judul *</label>
                    <input id="title" name="title" type="text" class="form-control form-control-lg" value="{{ old('title', $article->title) }}" maxlength="200" required data-slug-source>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="slug">Slug (alamat artikel)</label>
                    <div class="input-group"><span class="input-group-text">/artikel/</span>
                        <input id="slug" name="slug" type="text" class="form-control" value="{{ old('slug', $article->slug) }}" maxlength="200" placeholder="dibuat otomatis dari judul" data-slug-target></div>
                    <div class="hint">Huruf kecil, angka, dan tanda hubung. Jika kosong atau sudah dipakai, sistem membuat slug unik otomatis.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="excerpt">Ringkasan</label>
                    <textarea id="excerpt" name="excerpt" rows="3" class="form-control" maxlength="500">{{ old('excerpt', $article->excerpt) }}</textarea>
                    <div class="hint">Tampil di daftar artikel dan sebagai pembuka artikel. Jika kosong, diambil dari isi artikel.</div>
                </div>
                <div>
                    <label class="form-label" for="body">Isi artikel *</label>
                    @include('admin.partials.editor', ['name' => 'body', 'id' => 'body', 'value' => old('body', $article->body)])
                </div>
            </div>

            <div class="card-a">
                <h2>SEO</h2>
                <div class="mb-3">
                    <label class="form-label" for="seo_title">SEO title</label>
                    <input id="seo_title" name="seo_title" type="text" class="form-control" value="{{ old('seo_title', $article->seo_title) }}" maxlength="200" placeholder="Jika kosong, memakai judul artikel">
                </div>
                <div>
                    <label class="form-label" for="meta_description">Meta description</label>
                    <textarea id="meta_description" name="meta_description" rows="2" class="form-control" maxlength="320" data-counter="mdCount">{{ old('meta_description', $article->meta_description) }}</textarea>
                    <div class="hint"><span id="mdCount"></span> &middot; ideal 120-160 karakter. Jika kosong, memakai ringkasan.</div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card-a">
                <h2>Penerbitan</h2>
                <div class="mb-3">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="draft" @selected(old('status', $article->status) === 'draft')>Draft (tidak tampil di website)</option>
                        <option value="published" @selected(old('status', $article->status) === 'published')>Published (tayang)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="published_at">Tanggal terbit</label>
                    <input id="published_at" name="published_at" type="datetime-local" class="form-control" value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}">
                    <div class="hint">Kosong + Published = terbit sekarang. Tanggal di masa depan + Published = <strong>terjadwal</strong>: artikel otomatis tayang saat waktunya tiba (tanpa cron).</div>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" @checked(old('is_featured', $article->is_featured))>
                    <label class="form-check-label" for="is_featured">Artikel unggulan (diutamakan di beranda)</label>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-brand" type="submit"><i class="fas fa-floppy-disk me-1"></i>Simpan</button>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-line">Kembali ke daftar</a>
                </div>
            </div>

            <div class="card-a">
                <h2>Kategori &amp; penulis</h2>
                <div class="mb-3">
                    <label class="form-label" for="article_category_id">Kategori *</label>
                    <select id="article_category_id" name="article_category_id" class="form-select" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $c)<option value="{{ $c->id }}" @selected((int) old('article_category_id', $article->article_category_id) === $c->id)>{{ $c->name }}</option>@endforeach
                    </select>
                    @if($categories->isEmpty())<div class="hint text-danger">Belum ada kategori. <a href="{{ route('admin.article-categories.index') }}">Buat kategori dulu</a>.</div>@endif
                </div>
                <div>
                    <label class="form-label" for="author_id">Penulis</label>
                    <select id="author_id" name="author_id" class="form-select">
                        @foreach($authors as $u)<option value="{{ $u->id }}" @selected((int) old('author_id', $article->author_id ?: auth()->id()) === $u->id)>{{ $u->name }}</option>@endforeach
                    </select>
                </div>
            </div>

            <div class="card-a">
                <h2>Gambar utama</h2>
                <img id="imgPrev" class="img-prev" src="{{ $article->featured_image ? $article->image_url : '' }}" alt="" style="{{ $article->featured_image ? '' : 'display:none' }}">
                <input class="form-control mb-2" type="file" name="featured_image" accept="image/jpeg,image/png,image/webp" data-preview="imgPrev">
                <div class="hint mb-3">JPG, PNG, atau WebP, maks. 3 MB. Disarankan rasio 16:9.</div>
                <label class="form-label" for="featured_image_alt">Alt text gambar</label>
                <input id="featured_image_alt" name="featured_image_alt" type="text" class="form-control" value="{{ old('featured_image_alt', $article->featured_image_alt) }}" maxlength="200" placeholder="Deskripsi singkat gambar">
                @if($isEdit && $article->featured_image)
                    <div class="form-check mt-3"><input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1"><label class="form-check-label" for="remove_image">Hapus gambar utama</label></div>
                @endif
            </div>

            @if($isEdit)
            <div class="card-a">
                <h2>Zona berbahaya</h2>
                @include('admin.partials.delete-form', ['action' => route('admin.articles.destroy', $article), 'confirm' => 'Hapus artikel ini beserta gambar utamanya? Tindakan ini tidak dapat dibatalkan.', 'label' => 'Hapus artikel'])
            </div>
            @endif
        </div>
    </div>
</form>
@endsection
