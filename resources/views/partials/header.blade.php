@php
    $hours = parse_hours(setting('opening_hours'));
    $firstHour = $hours[0] ?? null;
    $logo = setting('logo');
    $isHome = request()->routeIs('home');
@endphp
<a class="skip-link" href="#main">Lewati ke konten</a>

<div id="topbar">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="top-contact d-flex flex-wrap">
                @if(setting('phone'))<span><i class="fas fa-phone-alt"></i>{{ setting('phone') }}</span>@endif
                @if(setting('email'))<span><i class="fas fa-envelope"></i>{{ setting('email') }}</span>@endif
                @if(setting('address'))<span class="d-none d-md-inline"><i class="fas fa-map-marker-alt"></i>{{ \Illuminate\Support\Str::limit(setting('address'), 60) }}</span>@endif
            </div>
            <div class="d-flex align-items-center gap-3">
                @if($firstHour)
                    <span class="ttag d-none d-sm-inline"><i class="fas fa-clock me-1"></i>{{ $firstHour['label'] }}: {{ $firstHour['time'] }}</span>
                @endif
                <div class="tsoc">
                    @foreach(social_links() as $s)
                        <a href="{{ $s['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $s['label'] }}"><i class="{{ $s['icon'] }}"></i></a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg" id="nav" aria-label="Navigasi utama">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <div class="blogo">
                @if($logo)
                    <img src="{{ media_url($logo) }}" alt="" class="blogo-img">
                @else
                    <div class="bico"><i class="fas fa-burger"></i></div>
                @endif
                <div>
                    <div class="bname">{{ brand_wordmark() }}</div>
                    <div class="bsub">{{ setting('brand_tagline') }}</div>
                </div>
            </div>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu" aria-controls="navmenu" aria-expanded="false" aria-label="Buka menu navigasi">
            <i class="fas fa-bars" style="color:var(--primary);font-size:1.35rem;"></i>
        </button>
        <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Tentang Kami</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('menu.*') ? 'active' : '' }}" href="{{ route('menu.index') }}">Menu</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">Artikel</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">Galeri</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Kontak</a></li>
            </ul>
            <div class="d-flex align-items-center gap-1">
                <button id="navSearchBtn" type="button" title="Cari menu" aria-label="Cari menu"><i class="fas fa-search"></i></button>
                <a href="{{ route('menu.index') }}" class="nav-link nav-cta"><i class="fas fa-book-open me-1"></i>Lihat Menu</a>
            </div>
        </div>
    </div>
</nav>

<div id="searchOv" role="dialog" aria-modal="true" aria-label="Cari menu">
    <button class="sovclose" id="searchClose" type="button" aria-label="Tutup"><i class="fas fa-times"></i></button>
    <div class="sovbox">
        <h4>Mau makan apa hari ini?</h4>
        <form action="{{ route('menu.index') }}" method="GET" class="sovinput">
            <input type="text" name="q" id="searchInput" placeholder="Cari nama menu..." autocomplete="off" maxlength="100"/>
            <button type="submit" aria-label="Cari"><i class="fas fa-search"></i></button>
        </form>
        <div class="sovcats">
            <a class="sovcat" href="{{ route('menu.index') }}"><img src="{{ asset('assets/img/menu/1.jpg') }}" alt=""/>Semua Menu</a>
            @foreach($navCategories as $c)
                <a class="sovcat" href="{{ route('menu.index', ['kategori' => $c->slug]) }}">{{ $c->name }}</a>
            @endforeach
        </div>
    </div>
</div>
