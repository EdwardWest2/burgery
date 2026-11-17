@extends('layouts.app')
@section('title', 'Galeri')
@section('description', 'Galeri foto '.setting('brand_name').'.')

@section('content')
@include('partials.page-header', ['heading' => 'Galeri', 'crumbs' => ['Galeri' => null], 'lead' => 'Foto-foto dari dapur dan ruang makan kami.'])

<section id="gallery" class="pgbody">
    <div class="container">
        @if($photos->isEmpty())
            <div class="emptybox"><i class="fas fa-images"></i><p>Belum ada foto di galeri.</p></div>
        @else
            <div class="ggrid ggrid-flow">
                @foreach($photos as $i => $photo)
                    <button type="button" class="gitem" data-gi="{{ $i }}"
                        data-gimg="{{ $photo->image_url }}"
                        data-gtitle="{{ $photo->caption }}"
                        data-gdesc="{{ $photo->alt_text }}">
                        <img src="{{ $photo->image_url }}" alt="{{ $photo->alt_text ?: $photo->caption }}" loading="lazy"/>
                        <div class="gover"><span><i class="fas fa-expand-alt"></i> {{ $photo->caption ?: 'Perbesar' }}</span></div>
                    </button>
                @endforeach
            </div>
            @include('partials.gallery-popup')
        @endif
    </div>
</section>
@endsection
