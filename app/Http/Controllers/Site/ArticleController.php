<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $categorySlug = (string) $request->query('kategori', '');

        $articles = Article::live()
            ->with(['category', 'author'])
            ->when($categorySlug !== '', fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $categorySlug)))
            ->when($search !== '', function ($q) use ($search) {
                $like = '%'.addcslashes($search, '%_\\').'%';
                $q->where(fn ($w) => $w->where('title', 'like', $like)->orWhere('excerpt', 'like', $like));
            })
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('site.articles.index', [
            'articles' => $articles,
            'categories' => ArticleCategory::withCount(['articles' => fn ($q) => $q->live()])
                ->whereHas('articles', fn ($q) => $q->live())
                ->orderBy('name')
                ->get(),
            'activeCategory' => $categorySlug,
            'search' => $search,
        ]);
    }

    public function show(string $slug)
    {
        // Hanya artikel yang live; draft/terjadwal => 404 untuk publik.
        $article = Article::live()->with(['category', 'author'])->where('slug', $slug)->firstOrFail();

        return view('site.articles.show', [
            'article' => $article,
            'related' => $this->related($article),
            'preview' => false,
        ]);
    }

    public static function related(Article $article)
    {
        return Article::live()
            ->with('category')
            ->where('id', '!=', $article->id)
            ->orderByRaw('article_category_id = ? desc', [$article->article_category_id])
            ->orderByDesc('published_at')
            ->take(3)
            ->get();
    }
}
