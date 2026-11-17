<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_category_id', 'name', 'slug', 'description', 'price', 'image',
        'image_alt', 'is_available', 'is_featured', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    public function getImageUrlAttribute(): string
    {
        return media_url($this->image, 'assets/img/menu/1.jpg');
    }

    public function getPriceLabelAttribute(): string
    {
        return rupiah($this->price);
    }
}
