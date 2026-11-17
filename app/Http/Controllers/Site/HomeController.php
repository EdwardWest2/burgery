<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $featured = MenuItem::with('category')
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        // Jika belum ada menu unggulan, tampilkan beberapa menu pertama.
        if ($featured->isEmpty()) {
            $featured = MenuItem::with('category')->orderBy('sort_order')->take(6)->get();
        }

        return view('site.home', [
            'featured' => $featured,
            'categories' => MenuCategory::withCount('items')
                ->with('items:id,menu_category_id,image,sort_order')
                ->orderBy('sort_order')
                ->get(),
            'articles' => Article::live()
                ->with(['category', 'author'])
                ->orderByDesc('is_featured')
                ->orderByDesc('published_at')
                ->take(3)
                ->get(),
            'gallery' => Gallery::orderBy('sort_order')->orderBy('id')->take(5)->get(),
            'testimonials' => Testimonial::where('is_active', true)->orderBy('sort_order')->get(),
            'stats' => [
                'menus' => MenuItem::count(),
                'categories' => MenuCategory::count(),
                'articles' => Article::live()->count(),
            ],
        ]);
    }
}
