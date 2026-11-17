<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Article extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'article_category_id', 'author_id', 'title', 'slug', 'excerpt', 'body',
        'featured_image', 'featured_image_alt', 'status', 'is_featured',
        'published_at', 'seo_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Artikel yang boleh dilihat publik: status published DAN tanggal terbit
     * sudah tiba. Tanggal terbit di masa depan = artikel terjadwal (otomatis
     * tampil saat waktunya, tanpa cron/scheduler).
     */
    public function scopeLive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->where('published_at', '<=', now());
    }

    public function isLive(): bool
    {
        return $this->status === self::STATUS_PUBLISHED
            && $this->published_at !== null
            && $this->published_at->lte(now());
    }

    public function isScheduled(): bool
    {
        return $this->status === self::STATUS_PUBLISHED
            && $this->published_at !== null
            && $this->published_at->gt(now());
    }

    public function getImageUrlAttribute(): string
    {
        return media_url($this->featured_image, 'assets/img/blog/1.jpg');
    }

    public function getSummaryAttribute(): string
    {
        if (filled($this->excerpt)) {
            return $this->excerpt;
        }

        return Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags((string) $this->body))), 160);
    }

    public function getReadingMinutesAttribute(): int
    {
        $words = str_word_count(strip_tags((string) $this->body));

        return max(1, (int) ceil($words / 200));
    }
}
