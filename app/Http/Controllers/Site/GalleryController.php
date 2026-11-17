<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        return view('site.gallery', [
            'photos' => Gallery::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }
}
