<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') - Admin {{ setting('brand_name', 'Burgery') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@900&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}"/>
</head>
<body class="admin">
@php
    $is = fn (string $pattern) => request()->routeIs($pattern);
@endphp
<div class="adm-wrap">
    <aside class="adm-side" id="admSide">
        <div class="adm-brand">
            <a href="{{ route('admin.dashboard') }}">{{ brand_wordmark() }}</a>
            <small>PANEL ADMIN</small>
        </div>
        <nav class="adm-nav" aria-label="Menu admin">
            <a href="{{ route('admin.dashboard') }}" class="{{ $is('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-gauge"></i>Dashboard</a>

            <div class="grp">Konten</div>
            <a href="{{ route('admin.articles.index') }}" class="{{ $is('admin.articles.*') ? 'active' : '' }}"><i class="fas fa-newspaper"></i>Artikel</a>
            <a href="{{ route('admin.article-categories.index') }}" class="{{ $is('admin.article-categories.*') ? 'active' : '' }}"><i class="fas fa-tags"></i>Kategori Artikel</a>
            <a href="{{ route('admin.menu-items.index') }}" class="{{ $is('admin.menu-items.*') ? 'active' : '' }}"><i class="fas fa-burger"></i>Menu</a>
            <a href="{{ route('admin.menu-categories.index') }}" class="{{ $is('admin.menu-categories.*') ? 'active' : '' }}"><i class="fas fa-list"></i>Kategori Menu</a>
            <a href="{{ route('admin.gallery.index') }}" class="{{ $is('admin.gallery.*') ? 'active' : '' }}"><i class="fas fa-images"></i>Galeri</a>
            <a href="{{ route('admin.pages.index') }}" class="{{ $is('admin.pages.*') ? 'active' : '' }}"><i class="fas fa-file-lines"></i>Halaman</a>
            <a href="{{ route('admin.testimonials.index') }}" class="{{ $is('admin.testimonials.*') ? 'active' : '' }}"><i class="fas fa-comments"></i>Testimoni</a>
            <a href="{{ route('admin.locations.index') }}" class="{{ $is('admin.locations.*') ? 'active' : '' }}"><i class="fas fa-location-dot"></i>Lokasi</a>

            <div class="grp">Komunikasi</div>
            <a href="{{ route('admin.messages.index') }}" class="{{ $is('admin.messages.*') ? 'active' : '' }}"><i class="fas fa-envelope"></i>Pesan Kontak
                @if($newMessageCount > 0)<span class="badge-n">{{ $newMessageCount }}</span>@endif
            </a>

            <div class="grp">Sistem</div>
            <a href="{{ route('admin.settings.edit') }}" class="{{ $is('admin.settings.*') ? 'active' : '' }}"><i class="fas fa-gear"></i>Pengaturan</a>
            <a href="{{ route('admin.profile.edit') }}" class="{{ $is('admin.profile.*') ? 'active' : '' }}"><i class="fas fa-user"></i>Profil &amp; Password</a>
            <a href="{{ route('home') }}" target="_blank" rel="noopener"><i class="fas fa-arrow-up-right-from-square"></i>Lihat Website</a>
        </nav>
        <div class="adm-foot">
            <div class="who">{{ auth()->user()->name }}</div>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit"><i class="fas fa-right-from-bracket me-1"></i>Keluar</button></form>
        </div>
    </aside>

    <div class="adm-main">
        <header class="adm-top">
            <div class="d-flex align-items-center gap-3">
                <button class="adm-burger" id="admBurger" type="button" aria-label="Buka menu"><i class="fas fa-bars"></i></button>
                <h1>@yield('heading')</h1>
            </div>
            <div>@yield('actions')</div>
        </header>
        <div class="adm-body">
            @if(session('success'))<div class="flash ok" role="status"><i class="fas fa-check-circle me-1"></i>{{ session('success') }}</div>@endif
            @if(session('error'))<div class="flash err" role="alert"><i class="fas fa-circle-exclamation me-1"></i>{{ session('error') }}</div>@endif
            @if($errors->any())
                <div class="flash err" role="alert">
                    <strong>Data belum tersimpan.</strong> Periksa isian berikut:
                    <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/admin.js') }}"></script>
</body>
</html>
