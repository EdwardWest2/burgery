<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $categorySlug = (string) $request->query('kategori', '');

        $items = MenuItem::with('category')
            ->when($categorySlug !== '', fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $categorySlug)))
            ->when($search !== '', function ($q) use ($search) {
                $like = '%'.addcslashes($search, '%_\\').'%';
                $q->where(fn ($w) => $w->where('name', 'like', $like)->orWhere('description', 'like', $like));
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return view('site.menu.index', [
            'items' => $items,
            'categories' => MenuCategory::withCount('items')->orderBy('sort_order')->get(),
            'activeCategory' => $categorySlug,
            'search' => $search,
        ]);
    }

    public function show(string $slug)
    {
        $item = MenuItem::with('category')->where('slug', $slug)->firstOrFail();

        $related = MenuItem::with('category')
            ->where('menu_category_id', $item->menu_category_id)
            ->where('id', '!=', $item->id)
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        return view('site.menu.show', ['item' => $item, 'related' => $related]);
    }
}
