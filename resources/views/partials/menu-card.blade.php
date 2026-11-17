<div class="col-sm-6 col-lg-4 mwrap" data-aos="fade-up" data-aos-delay="{{ ($delay ?? 0) * 80 }}">
    <a href="{{ route('menu.show', $item->slug) }}" class="mcard mcard-link">
        <div class="mimg">
            <img src="{{ $item->image_url }}" alt="{{ $item->image_alt ?: $item->name }}" loading="lazy"/>
            @if(! $item->is_available)
                <div class="mbdg sold">Sedang habis</div>
            @elseif($item->is_featured)
                <div class="mbdg hot"><i class="fas fa-star"></i> Unggulan</div>
            @endif
        </div>
        <div class="mbody">
            <div class="mcat">{{ $item->category->name }}</div>
            <div class="mtit">{{ $item->name }}</div>
            <div class="mdesc">{{ \Illuminate\Support\Str::limit((string) $item->description, 95) }}</div>
            <div class="mfoot">
                <div class="mprice">{{ $item->price_label }}</div>
                <span class="madd" aria-hidden="true"><i class="fas fa-arrow-right"></i></span>
            </div>
        </div>
    </a>
</div>
