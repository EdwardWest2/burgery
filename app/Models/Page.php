<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug', 'title', 'excerpt', 'body', 'image', 'image_alt',
        'seo_title', 'meta_description',
    ];
}
