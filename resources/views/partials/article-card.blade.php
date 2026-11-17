<div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($delay ?? 0) * 80 }}">
    <div class="blcard">
        <div class="blimg">
            <a href="{{ route('articles.show', $article->slug) }}" tabindex="-1" aria-hidden="true">
                <img src="{{ $article->image_url }}" alt="{{ $article->featured_image_alt }}" loading="lazy"/>
            </a>
            <div class="bldatebdg">
                <span class="bd">{{ $article->published_at->format('d') }}</span>
                <span class="bm">{{ $article->published_at->translatedFormat('M') }}</span>
            </div>
        </div>
        <div class="blbody">
            <div class="bltag">{{ $article->category->name }}</div>
            <div class="bltit"><a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a></div>
            <p class="blexc">{{ \Illuminate\Support\Str::limit($article->summary, 110) }}</p>
            <div class="blmeta">
                @if($article->author)<span><i class="fas fa-user"></i>{{ $article->author->name }}</span>@endif
                <span><i class="fas fa-clock"></i>{{ $article->reading_minutes }} menit baca</span>
            </div>
            <a href="{{ route('articles.show', $article->slug) }}" class="blmore">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</div>
