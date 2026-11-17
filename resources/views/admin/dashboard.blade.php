@extends('layouts.admin')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('actions')<a href="{{ route('admin.articles.create') }}" class="btn btn-brand btn-sm"><i class="fas fa-plus me-1"></i>Artikel baru</a>@endsection

@section('content')
<div class="row g-3 mb-4">
    @foreach([
        ['Total artikel', $stats['articles'], 'fa-newspaper', route('admin.articles.index')],
        ['Artikel published', $stats['published'], 'fa-circle-check', route('admin.articles.index', ['status' => 'published'])],
        ['Artikel draft', $stats['drafts'], 'fa-pen-to-square', route('admin.articles.index', ['status' => 'draft'])],
        ['Kategori artikel', $stats['categories'], 'fa-tags', route('admin.article-categories.index')],
        ['Item menu', $stats['menus'], 'fa-burger', route('admin.menu-items.index')],
        ['Pesan kontak', $stats['messages'], 'fa-envelope', route('admin.messages.index')],
    ] as [$label, $n, $icon, $url])
        <div class="col-6 col-lg-4 col-xl-2">
            <a class="stat" href="{{ $url }}"><i class="fas {{ $icon }}"></i><div class="n">{{ $n }}</div><div class="l">{{ $label }}</div></a>
        </div>
    @endforeach
</div>

@if($stats['new_messages'] > 0)
    <div class="flash ok"><i class="fas fa-envelope me-1"></i>Ada <strong>{{ $stats['new_messages'] }}</strong> pesan baru. <a href="{{ route('admin.messages.index', ['status' => 'new']) }}">Buka pesan</a></div>
@endif

<div class="card-a">
    <h2>Artikel terbaru diperbarui</h2>
    @if($latest->isEmpty())
        <div class="empty-a"><i class="fas fa-newspaper"></i>Belum ada artikel. <a href="{{ route('admin.articles.create') }}">Tulis artikel pertama</a>.</div>
    @else
        <div class="tbl-wrap"><table class="tbl">
            <thead><tr><th>Judul</th><th>Kategori</th><th>Status</th><th>Diperbarui</th><th></th></tr></thead>
            <tbody>
            @foreach($latest as $a)
                <tr>
                    <td class="title">{{ $a->title }}</td>
                    <td>{{ $a->category->name }}</td>
                    <td>@include('admin.partials.article-status', ['article' => $a])</td>
                    <td class="sub">{{ $a->updated_at->translatedFormat('j M Y H:i') }}</td>
                    <td class="acts"><a class="btn-line btn-sm-x" href="{{ route('admin.articles.edit', $a) }}">Edit</a></td>
                </tr>
            @endforeach
            </tbody>
        </table></div>
    @endif
</div>
@endsection
