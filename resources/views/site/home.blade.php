@extends('layouts.app')
@section('og_type', 'website')

@section('content')
@php
    $hours = parse_hours(setting('opening_hours'));
    $firstHour = $hours[0] ?? null;
    $marquee = $categories->pluck('name')->merge($featured->pluck('name'))->unique()->values();
@endphp

{{-- HERO --}}
<section id="hero">
    <div class="hs hs1"></div>
    <div class="hs hs2"></div>
    <div class="hbgtxt" aria-hidden="true">BURGER</div>
    <div class="container">
        <div class="row align-items-center g-5" style="min-height:88vh;">
            <div class="col-lg-6">
                <div class="hbadge">
                    <div class="hbi"><i class="fas fa-burger"></i></div>
                    <span>{{ setting('hero_badge') }}</span>
                </div>
                <h1 class="htitle">{{ highlight_text(setting('hero_title'), setting('hero_highlight'), 'hl') }}</h1>
                <p class="hdesc">{{ setting('hero_text') }}</p>
                <div class="d-flex flex-wrap gap-3 mb-2">
                    <a href="{{ route('menu.index') }}" class="btn-red"><i class="fas fa-utensils"></i>Lihat Menu</a>
                    @if(setting('video_url'))
                        <a href="{{ setting('video_url') }}" class="magnific_popup btn-play popup-youtube">
                            <div class="pico"><i class="fas fa-play"></i></div>
                            <span>Tonton Cerita Kami</span>
                        </a>
                    @else
                        <a href="{{ route('about') }}" class="btn-play">
                            <div class="pico"><i class="fas fa-book-open"></i></div>
                            <span>Kenali Kami</span>
                        </a>
                    @endif
                </div>
                <div class="hstats d-flex gap-3 flex-wrap mt-4">
                    <div class="hstat"><span class="snum">{{ $stats['menus'] }}</span><small>Pilihan Menu</small></div>
                    <div class="sdiv"></div>
                    <div class="hstat"><span class="snum">{{ $stats['categories'] }}</span><small>Kategori</small></div>
                    <div class="sdiv"></div>
                    <div class="hstat"><span class="snum">{{ $stats['articles'] }}</span><small>Artikel</small></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div style="position:relative;text-align:center;">
                    <div class="hcircle">
                        <img src="{{ asset('assets/img/banner-img.jpg') }}" alt="Burger dengan dua patty, keju, selada, dan tomat"/>
                    </div>
                    <div class="fcard fc1">
                        <div class="fcoi r"><i class="fas fa-fire"></i></div>
                        <div><span class="fcnum">{{ $stats['menus'] }} Menu</span><span class="fcsm">{{ $stats['categories'] }} kategori</span></div>
                    </div>
                    @if($firstHour)
                    <div class="fcard fc2">
                        <div class="fcoi y"><i class="fas fa-clock"></i></div>
                        <div><span class="fcnum">{{ $firstHour['time'] ?: 'Jam buka' }}</span><span class="fcsm">{{ $firstHour['label'] }}</span></div>
                    </div>
                    @endif
                    <div class="fcard fc3">
                        <div class="fcoi g"><i class="fas fa-location-dot"></i></div>
                        <div><span class="fcnum">{{ setting('city') ?: 'Kunjungi kami' }}</span><span class="fcsm"><a href="{{ route('contact') }}" style="color:inherit;">Lihat lokasi</a></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- MARQUEE --}}
@if($marquee->isNotEmpty())
<div class="mqsec" aria-hidden="true">
    <div class="mqtrack">
        @for($i = 0; $i < 2; $i++)
            @foreach($marquee as $name)
                <div class="mqitem"><i class="fas fa-circle"></i>{{ $name }}</div>
            @endforeach
        @endfor
    </div>
</div>
@endif

{{-- KATEGORI --}}
@if($categories->isNotEmpty())
<section id="category">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="slbl">Yang Kami Sajikan</span>
            <h2 class="stitle">Jelajahi <span>Kategori</span> Menu</h2>
            <div class="sline"></div>
        </div>
        <div class="row g-3 justify-content-center">
            @foreach($categories as $i => $cat)
                @php $thumb = $cat->items->sortBy('sort_order')->first(); @endphp
                <div class="col-6 col-sm-4 col-md-3 col-lg-2" data-aos="zoom-in" data-aos-delay="{{ $i * 70 }}">
                    <a class="catcard d-block" href="{{ route('menu.index', ['kategori' => $cat->slug]) }}">
                        <img class="catimg" src="{{ media_url($thumb?->image, 'assets/img/menu/1.jpg') }}" alt=""/>
                        <div class="catnm">{{ $cat->name }}</div>
                        <div class="catct">{{ $cat->items_count }} menu</div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- TENTANG --}}
<section id="about">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5" data-aos="fade-right">
                <div class="astack">
                    <div class="aexp"><span class="anum">{{ $stats['menus'] }}</span><small>Pilihan<br/>Menu</small></div>
                    <div class="amain"><img src="{{ asset('assets/img/about1.jpg') }}" alt="Ruang makan restoran" loading="lazy"/></div>
                    <div class="asm"><img src="{{ asset('assets/img/about2.jpg') }}" alt="Hidangan di atas meja" loading="lazy"/></div>
                </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <span class="slbl">Cerita Kami</span>
                <h2 class="stitle text-start">{{ setting('about_heading') }}</h2>
                <div class="sline lft"></div>
                <p class="sdesc mb-4">{{ setting('about_text') }}</p>
                <div class="mb-4">
                    @foreach([['leaf', 'r', 1], ['award', 'y', 2], ['heart', 'g', 3]] as [$icon, $tone, $n])
                        <div class="fti">
                            <div class="ftico {{ $tone }}"><i class="fas fa-{{ $icon === 'heart' ? 'heart' : $icon }}"></i></div>
                            <div>
                                <h6>{{ setting('adv_'.$n.'_title') }}</h6>
                                <p>{{ setting('adv_'.$n.'_text') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('about') }}" class="btn-red"><i class="fas fa-book-open"></i>Baca Selengkapnya</a>
            </div>
        </div>
    </div>
</section>

{{-- MENU UNGGULAN --}}
<section id="menu">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="slbl">Pilihan Kami</span>
            <h2 class="stitle">Menu <span>Unggulan</span></h2>
            <div class="sline"></div>
        </div>
        @if($featured->isEmpty())
            <p class="text-center sdesc">Menu akan segera hadir.</p>
        @else
            <div class="row g-4" id="mgrid">
                @foreach($featured as $i => $item)
                    @include('partials.menu-card', ['item' => $item, 'delay' => $i % 3])
                @endforeach
            </div>
            <div class="text-center mt-5"><a href="{{ route('menu.index') }}" class="btn-red"><i class="fas fa-th-large"></i>Lihat Semua Menu</a></div>
        @endif
    </div>
</section>

{{-- GALERI --}}
@if($gallery->isNotEmpty())
<section id="gallery">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="slbl">Galeri</span>
            <h2 class="stitle">Sekilas <span>Burgery</span></h2>
            <div class="sline"></div>
        </div>
        <div class="ggrid" data-aos="fade-up">
            @foreach($gallery as $i => $photo)
                <button type="button" class="gitem" data-gi="{{ $i }}"
                    data-gimg="{{ $photo->image_url }}"
                    data-gtitle="{{ $photo->caption }}"
                    data-gdesc="{{ $photo->alt_text }}">
                    <img src="{{ $photo->image_url }}" alt="{{ $photo->alt_text ?: $photo->caption }}" loading="lazy"/>
                    <div class="gover"><span><i class="fas fa-expand-alt"></i> {{ $photo->caption ?: 'Perbesar' }}</span></div>
                </button>
            @endforeach
        </div>
        <div class="text-center mt-5"><a href="{{ route('gallery') }}" class="btn-red"><i class="fas fa-images"></i>Lihat Galeri</a></div>
    </div>
</section>
@include('partials.gallery-popup')
@endif

{{-- TESTIMONI --}}
@if($testimonials->isNotEmpty())
<section id="testimonials">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="slbl">Kata Mereka</span>
            <h2 class="stitle">Ulasan <span>Pelanggan</span></h2>
            <div class="sline"></div>
        </div>
        <div class="swiper tesSwiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                @foreach($testimonials as $t)
                    <div class="swiper-slide">
                        <div class="tescard">
                            <div class="tesq" aria-hidden="true">&ldquo;</div>
                            <p class="testxt">{{ $t->content }}</p>
                            <div class="tesauth">
                                <span class="tesinit" aria-hidden="true">{{ mb_strtoupper(mb_substr($t->name, 0, 1)) }}</span>
                                <div>
                                    <div class="tesnm">{{ $t->name }}</div>
                                    @if($t->role)<div class="tesrl">{{ $t->role }}</div>@endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination mt-4" style="position:static;"></div>
        </div>
    </div>
</section>
@endif

{{-- JAM & LOKASI --}}
<section id="hours">
    <div class="hrsbg"></div>
    <div class="container" style="position:relative;z-index:2;">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="slbl" style="color:#a5d6bc;">Jam Operasional</span>
            <h2 class="stitle" style="color:#fff;">Kami Siap <span style="color:var(--secondary);">Menyambut Anda</span></h2>
            <div class="sline"></div>
        </div>
        <div class="row g-4 align-items-start justify-content-center">
            <div class="col-lg-5" data-aos="fade-right">
                <div class="hrscard">
                    @forelse($hours as $h)
                        <div class="hrsrow">
                            <span class="hrsday"><i class="fas fa-calendar-day me-2" style="color:var(--secondary);"></i>{{ $h['label'] }}</span>
                            <div class="d-flex align-items-center gap-2">
                                <div class="hdot {{ $h['closed'] ? 'off' : 'on' }}"></div>
                                <span class="hrstime" @if($h['closed']) style="color:#ff6b6b;" @endif>{{ $h['time'] }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="hrsrow"><span class="hrsday">Jam operasional belum diatur.</span></div>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-3" data-aos="zoom-in">
                <div class="hrscta">
                    <i class="fas fa-envelope-open-text fa-2x mb-3" style="color:rgba(255,255,255,.8);"></i>
                    <h4>Ada Pertanyaan?</h4>
                    <p>Kirim pesan, kami akan membalas secepatnya.</p>
                    <a href="{{ route('contact') }}" class="btnw">Hubungi Kami</a>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-left">
                <div class="hrscard">
                    <h5 style="color:#fff;margin-bottom:18px;font-family:'Poppins',sans-serif;font-size:.95rem;font-weight:700;"><i class="fas fa-map-marker-alt me-2" style="color:var(--secondary);"></i>Temukan Kami</h5>
                    @if(setting('address'))<div class="hrsrow"><span class="hrsday"><i class="fas fa-location-dot me-2" style="color:var(--secondary);"></i>Alamat</span><span class="hrstime" style="font-size:.8rem;text-align:right;">{{ setting('address') }}</span></div>@endif
                    @if(setting('phone'))<div class="hrsrow"><span class="hrsday"><i class="fas fa-phone me-2" style="color:var(--secondary);"></i>Telepon</span><span class="hrstime" style="font-size:.8rem;">{{ setting('phone') }}</span></div>@endif
                    @if(setting('email'))<div class="hrsrow"><span class="hrsday"><i class="fas fa-envelope me-2" style="color:var(--secondary);"></i>Email</span><span class="hrstime" style="font-size:.8rem;">{{ setting('email') }}</span></div>@endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ARTIKEL --}}
@if($articles->isNotEmpty())
<section id="blog">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="slbl">Kabar &amp; Cerita</span>
            <h2 class="stitle">Artikel <span>Terbaru</span></h2>
            <div class="sline"></div>
        </div>
        <div class="row g-4">
            @foreach($articles as $i => $article)
                @include('partials.article-card', ['article' => $article, 'delay' => $i])
            @endforeach
        </div>
        <div class="text-center mt-5"><a href="{{ route('articles.index') }}" class="btn-red"><i class="fas fa-newspaper"></i>Semua Artikel</a></div>
    </div>
</section>
@endif
@endsection
