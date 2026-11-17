@extends('layouts.app')
@section('title', $item->name)
@section('description', \Illuminate\Support\Str::limit(strip_tags((string) $item->description), 155) ?: $item->name.' di '.setting('brand_name'))
@section('image', $item->image_url)

@section('content')
@include('partials.page-header', ['heading' => $item->name, 'crumbs' => ['Menu' => route('menu.index'), $item->category->name => route('menu.index', ['kategori' => $item->category->slug]), $item->name => null]])

<section class="pgbody">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="mdetimg">
                    <img src="{{ $item->image_url }}" alt="{{ $item->image_alt ?: $item->name }}"/>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <a class="mcat" href="{{ route('menu.index', ['kategori' => $item->category->slug]) }}">{{ $item->category->name }}</a>
                <h2 class="stitle text-start mt-1">{{ $item->name }}</h2>
                <div class="sline lft"></div>
                <div class="mdetprice">{{ $item->price_label }}</div>
                <div class="mdetstat {{ $item->is_available ? 'ok' : 'no' }}">
                    <i class="fas {{ $item->is_available ? 'fa-circle-check' : 'fa-ban' }}"></i>
                    {{ $item->is_available ? 'Tersedia' : 'Sedang habis' }}
                    @if($item->is_featured)<span class="mdetfeat"><i class="fas fa-star"></i> Menu unggulan</span>@endif
                </div>
                @if($item->description)<p class="sdesc mb-4">{{ $item->description }}</p>@endif
                <p class="sdesc small mb-4">Ingin datang atau bertanya tentang menu ini? Lihat lokasi dan jam buka kami.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('contact') }}" class="btn-red"><i class="fas fa-location-dot"></i>Lokasi &amp; Kontak</a>
                    <a href="{{ route('menu.index', ['kategori' => $item->category->slug]) }}" class="btn-red btn-ghost"><i class="fas fa-th-large"></i>Menu {{ $item->category->name }}</a>
                </div>
            </div>
        </div>

        @if($related->isNotEmpty())
            <div class="text-center mt-5 pt-4 mb-4">
                <span class="slbl">Mungkin Anda Suka</span>
                <h3 class="stitle">Menu Serupa</h3>
                <div class="sline"></div>
            </div>
            <div class="row g-4">
                @foreach($related as $i => $r)
                    @include('partials.menu-card', ['item' => $r, 'delay' => $i])
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
