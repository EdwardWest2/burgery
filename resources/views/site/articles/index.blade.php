@extends('layouts.app')
@section('title', 'Artikel')
@section('description', 'Artikel, tips, dan kabar terbaru dari '.setting('brand_name').'.')

@section('content')
@include('partials.page-header', ['heading' => 'Artikel', 'crumbs' => ['Artikel' => null], 'lead' => 'Cerita, tips, dan kabar terbaru.'])

<section id="blog" class="pgbody">
    <div class="container">
        <form method="GET" action="{{ route('articles.index') }}" class="listsearch" role="search">
            @if($activeCategory !== '')<input type="hidden" name="kategori" value="{{ $activeCategory }}">@endif
            <input type="search" name="q" value="{{ $search }}" placeholder="Cari judul atau ringkasan artikel..." maxlength="100" aria-label="Cari artikel">
            <button type="submit" aria-label="Cari"><i class="fas fa-search"></i></button>
        </form>

        @if($categories->isNotEmpty())
        <div class="text-center mb-4">
            <a class="filtbtn {{ $activeCategory === '' ? 'active' : '' }}" href="{{ route('articles.index', array_filter(['q' => $search])) }}">Semua</a>
            @foreach($categories as $c)
                <a class="filtbtn {{ $activeCategory === $c->slug ? 'active' : '' }}" href="{{ route('articles.index', array_filter(['kategori' => $c->slug, 'q' => $search])) }}">{{ $c->name }} <small>({{ $c->articles_count }})</small></a>
            @endforeach
        </div>
        @endif

        @if($articles->isEmpty())
            <div class="emptybox">
                <i class="fas fa-newspaper"></i>
                <p>{{ ($search !== '' || $activeCategory !== '') ? 'Tidak ada artikel yang cocok dengan pencarian Anda.' : 'Belum ada artikel yang diterbitkan.' }}</p>
                @if($search !== '' || $activeCategory !== '')<a href="{{ route('articles.index') }}" class="btn-red">Tampilkan semua artikel</a>@endif
            </div>
        @else
            <div class="row g-4">
                @foreach($articles as $i => $article)
                    @include('partials.article-card', ['article' => $article, 'delay' => $i % 3])
                @endforeach
            </div>
            <div class="mt-5 d-flex justify-content-center">{{ $articles->links() }}</div>
        @endif
    </div>
</section>
@endsection
