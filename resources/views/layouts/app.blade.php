<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $brand = setting('brand_name', 'Burgery');
        $pageTitle = trim($__env->yieldContent('title'));
        $pageDesc = trim($__env->yieldContent('description')) ?: setting('seo_default_description');
        $fullTitle = $pageTitle !== '' ? $pageTitle.' - '.$brand : setting('seo_default_title');
        $ogImage = trim($__env->yieldContent('image'));
    @endphp
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">
    <meta name="author" content="{{ $brand }}">
    <meta property="og:site_name" content="{{ $brand }}">
    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($ogImage !== '')<meta property="og:image" content="{{ $ogImage }}">@endif
    @yield('head')
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet"/>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/aos.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/swiper-bundle.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/burgery.css') }}"/>
</head>
<body>
@include('partials.header')

@if(isset($preview) && $preview)
    <div class="preview-bar">
        <i class="fas fa-eye"></i> Mode pratinjau admin: halaman ini
        {{ $article->isLive() ? 'sudah tayang.' : 'belum tayang untuk publik.' }}
        <a href="{{ route('admin.articles.edit', $article) }}">Kembali ke editor</a>
    </div>
@endif

<main id="main">
    @yield('content')
</main>

@include('partials.footer')

<button id="btt" type="button" aria-label="Kembali ke atas"><i class="fas fa-chevron-up"></i></button>

<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/aos.js') }}"></script>
<script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
@stack('scripts')
</body>
</html>
