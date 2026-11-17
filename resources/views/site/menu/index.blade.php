@extends('layouts.app')
@section('title', 'Menu')
@section('description', 'Daftar menu '.setting('brand_name').': lihat kategori, foto, deskripsi, harga, dan ketersediaan.')

@section('content')
@include('partials.page-header', ['heading' => 'Menu Kami', 'crumbs' => ['Menu' => null], 'lead' => 'Pilih kategori atau cari nama menu.'])

<section id="menu" class="pgbody">
    <div class="container">
        <form method="GET" action="{{ route('menu.index') }}" class="listsearch" role="search">
            @if($activeCategory !== '')<input type="hidden" name="kategori" value="{{ $activeCategory }}">@endif
            <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama menu..." maxlength="100" aria-label="Cari menu">
            <button type="submit" aria-label="Cari"><i class="fas fa-search"></i></button>
        </form>

        <div class="text-center mb-4" data-aos="fade-up">
            <a class="filtbtn {{ $activeCategory === '' ? 'active' : '' }}" href="{{ route('menu.index', array_filter(['q' => $search])) }}">Semua</a>
            @foreach($categories as $c)
                <a class="filtbtn {{ $activeCategory === $c->slug ? 'active' : '' }}" href="{{ route('menu.index', array_filter(['kategori' => $c->slug, 'q' => $search])) }}">{{ $c->name }} <small>({{ $c->items_count }})</small></a>
            @endforeach
        </div>

        @if($items->isEmpty())
            <div class="emptybox">
                <i class="fas fa-magnifying-glass"></i>
                <p>Tidak ada menu yang cocok{{ $search !== '' ? ' dengan "'.$search.'"' : '' }}.</p>
                <a href="{{ route('menu.index') }}" class="btn-red">Tampilkan semua menu</a>
            </div>
        @else
            <div class="row g-4" id="mgrid">
                @foreach($items as $i => $item)
                    @include('partials.menu-card', ['item' => $item, 'delay' => $i % 3])
                @endforeach
            </div>
            <div class="mt-5 d-flex justify-content-center">{{ $items->links() }}</div>
        @endif
    </div>
</section>
@endsection
