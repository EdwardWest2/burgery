@if($article->status === 'draft')
    <span class="pill draft">Draft</span>
@elseif($article->isScheduled())
    <span class="pill scheduled" title="Tayang otomatis pada {{ $article->published_at->translatedFormat('j M Y H:i') }}">Terjadwal</span>
@else
    <span class="pill published">Published</span>
@endif
