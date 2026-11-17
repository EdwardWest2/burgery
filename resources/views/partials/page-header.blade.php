{{-- Header halaman dalam. Variabel: $heading, $crumbs (array label => url|null), $lead (opsional) --}}
<section class="pghead">
    <div class="container">
        <nav aria-label="Breadcrumb">
            <ol class="pgcrumb">
                <li><a href="{{ route('home') }}">Beranda</a></li>
                @foreach($crumbs ?? [] as $label => $url)
                    <li>@if($url)<a href="{{ $url }}">{{ $label }}</a>@else<span aria-current="page">{{ $label }}</span>@endif</li>
                @endforeach
            </ol>
        </nav>
        <h1 class="pgtitle">{{ $heading }}</h1>
        @isset($lead)<p class="pglead">{{ $lead }}</p>@endisset
    </div>
</section>
