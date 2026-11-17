@extends('layouts.admin')
@section('title', 'Artikel')
@section('heading', 'Artikel')
@section('actions')<a href="{{ route('admin.articles.create') }}" class="btn btn-brand btn-sm"><i class="fas fa-plus me-1"></i>Artikel baru</a>@endsection

@section('content')
<div class="card-a">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5"><input type="search" name="q" value="{{ $filters['search'] }}" class="form-control" placeholder="Cari judul artikel..."></div>
        <div class="col-6 col-md-3">
            <select name="status" class="form-select" aria-label="Status">
                <option value="">Semua status</option>
                <option value="published" @selected($filters['status'] === 'published')>Published</option>
                <option value="draft" @selected($filters['status'] === 'draft')>Draft</option>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="category" class="form-select" aria-label="Kategori">
                <option value="">Semua kategori</option>
                @foreach($categories as $c)<option value="{{ $c->id }}" @selected($filters['category'] === $c->id)>{{ $c->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2"><button class="btn btn-line flex-fill" type="submit">Cari</button>@if($filters['search'] || $filters['status'] || $filters['category'])<a href="{{ route('admin.articles.index') }}" class="btn btn-line">Reset</a>@endif</div>
    </form>

    @if($articles->isEmpty())
        <div class="empty-a"><i class="fas fa-newspaper"></i>
            {{ ($filters['search'] || $filters['status'] || $filters['category']) ? 'Tidak ada artikel yang cocok.' : 'Belum ada artikel.' }}
            <div class="mt-3"><a class="btn btn-brand btn-sm" href="{{ route('admin.articles.create') }}">Tulis artikel</a></div>
        </div>
    @else
        <div class="tbl-wrap"><table class="tbl">
            <thead><tr><th></th><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Status</th><th>Tanggal terbit</th><th></th></tr></thead>
            <tbody>
            @foreach($articles as $a)
                <tr>
                    <td><img class="thumb" src="{{ $a->image_url }}" alt=""></td>
                    <td><div class="title">{{ $a->title }} @if($a->is_featured)<i class="fas fa-star" style="color:var(--secondary);" title="Artikel unggulan"></i>@endif</div><div class="sub">/artikel/{{ $a->slug }}</div></td>
                    <td>{{ $a->category->name }}</td>
                    <td>{{ $a->author?->name ?? '-' }}</td>
                    <td>@include('admin.partials.article-status', ['article' => $a])</td>
                    <td class="sub">{{ $a->published_at?->translatedFormat('j M Y H:i') ?? '-' }}</td>
                    <td class="acts">
                        <a class="btn-line btn-sm-x" href="{{ route('admin.articles.edit', $a) }}">Edit</a>
                        <a class="btn-line btn-sm-x" href="{{ route('admin.articles.preview', $a) }}" target="_blank" rel="noopener">Pratinjau</a>
                        @include('admin.partials.delete-form', ['action' => route('admin.articles.destroy', $a), 'confirm' => 'Hapus artikel "'.$a->title.'"? Tindakan ini tidak dapat dibatalkan.'])
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
        <div class="mt-3">{{ $articles->links() }}</div>
    @endif
</div>
@endsection
