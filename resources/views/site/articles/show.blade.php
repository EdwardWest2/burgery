@extends('layouts.app')
@section('title', $article->seo_title ?: $article->title)
@section('description', $article->meta_description ?: $article->summary)
@section('image', $article->image_url)
@section('og_type', 'article')

@section('content')
@include('partials.page-header', [
    'heading' => $article->title,
    'crumbs' => ['Artikel' => route('articles.index'), $article->category->name => route('articles.index', ['kategori' => $article->category->slug])],
])

<section class="pgbody">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <article>
                    <div class="armeta">
                        <span><i class="fas fa-tag"></i>{{ $article->category->name }}</span>
                        @if($article->author)<span><i class="fas fa-user"></i>{{ $article->author->name }}</span>@endif
                        @if($article->published_at)<span><i class="fas fa-calendar-day"></i><time datetime="{{ $article->published_at->toDateString() }}">{{ $article->published_at->translatedFormat('j F Y') }}</time></span>@endif
                        <span><i class="fas fa-clock"></i>{{ $article->reading_minutes }} menit baca</span>
                    </div>

                    @if($article->featured_image)
                        <figure class="arhero">
                            <img src="{{ $article->image_url }}" alt="{{ $article->featured_image_alt }}"/>
                        </figure>
                    @endif

                    @if($article->excerpt)<p class="arlead">{{ $article->excerpt }}</p>@endif

                    <div class="prose">{!! $article->body !!}</div>
                </article>

                <div class="arfoot">
                    <a href="{{ route('articles.index') }}" class="btn-red btn-ghost"><i class="fas fa-arrow-left"></i>Semua artikel</a>
                </div>
            </div>
        </div>

        @if($related->isNotEmpty())
            <div class="text-center mt-5 pt-4 mb-4">
                <span class="slbl">Baca Juga</span>
                <h3 class="stitle">Artikel Terkait</h3>
                <div class="sline"></div>
            </div>
            <div class="row g-4">
                @foreach($related as $i => $r)
                    @include('partials.article-card', ['article' => $r, 'delay' => $i])
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
