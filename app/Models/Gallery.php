<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['image', 'caption', 'alt_text', 'sort_order'];

    public function getImageUrlAttribute(): string
    {
        return media_url($this->image);
    }
}
