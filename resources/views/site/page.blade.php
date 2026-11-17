@extends('layouts.app')
@section('title', $page->seo_title ?: $page->title)
@section('description', $page->meta_description ?: $page->excerpt)
@if($page->image)@section('image', media_url($page->image))@endif

@section('content')
@include('partials.page-header', ['heading' => $page->title, 'crumbs' => [$page->title => null], 'lead' => $page->excerpt])

<section class="pgbody">
    <div class="container">
        <div class="row justify-content-center g-5 align-items-start">
            @if($page->image)
                <div class="col-lg-5" data-aos="fade-right">
                    <img src="{{ media_url($page->image) }}" alt="{{ $page->image_alt }}" class="pgimg" loading="lazy"/>
                </div>
            @endif
            <div class="{{ $page->image ? 'col-lg-7' : 'col-lg-8' }}">
                <div class="prose">{!! $page->body !!}</div>
                @if($page->slug === 'tentang-kami')
                    <div class="mt-4 d-flex flex-wrap gap-3">
                        <a href="{{ route('menu.index') }}" class="btn-red"><i class="fas fa-utensils"></i>Lihat Menu</a>
                        <a href="{{ route('contact') }}" class="btn-red btn-ghost"><i class="fas fa-envelope"></i>Hubungi Kami</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
