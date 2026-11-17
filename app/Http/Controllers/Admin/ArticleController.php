<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Site\ArticleController as SiteArticleController;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use App\Support\HtmlSanitizer;
use App\Support\MediaStore;
use App\Support\Slugger;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');
        $category = (int) $request->query('category', 0);

        $articles = Article::with(['category', 'author'])
            ->when($search !== '', fn ($q) => $q->where('title', 'like', '%'.addcslashes($search, '%_\\').'%'))
            ->when(in_array($status, ['draft', 'published'], true), fn ($q) => $q->where('status', $status))
            ->when($category > 0, fn ($q) => $q->where('article_category_id', $category))
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.articles.index', [
            'articles' => $articles,
            'categories' => ArticleCategory::orderBy('name')->get(),
            'filters' => compact('search', 'status', 'category'),
        ]);
    }

    public function create()
    {
        return view('admin.articles.form', [
            'article' => new Article([
                'status' => Article::STATUS_DRAFT,
                'author_id' => auth()->id(),
            ]),
            'categories' => ArticleCategory::orderBy('name')->get(),
            'authors' => User::orderBy('name')->get(),
        ]);
    }

    public function store(ArticleRequest $request)
    {
        $data = $this->payload($request);
        $data['slug'] = Slugger::unique(Article::class, $request->input('slug') ?: $request->input('title'));

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = MediaStore::put($request->file('featured_image'), 'articles');
        }

        $article = Article::create($data);

        return redirect()->route('admin.articles.edit', $article)
            ->with('success', $article->status === Article::STATUS_PUBLISHED
                ? 'Artikel disimpan dan diterbitkan.'
                : 'Artikel disimpan sebagai draft.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.form', [
            'article' => $article,
            'categories' => ArticleCategory::orderBy('name')->get(),
            'authors' => User::orderBy('name')->get(),
        ]);
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $data = $this->payload($request);
        $data['slug'] = Slugger::unique(Article::class, $request->input('slug') ?: $article->slug, $article->id);

        if ($request->boolean('remove_image') && ! $request->hasFile('featured_image')) {
            MediaStore::delete($article->featured_image);
            $data['featured_image'] = null;
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = MediaStore::replace($request->file('featured_image'), $article->featured_image, 'articles');
        }

        $article->update($data);

        return redirect()->route('admin.articles.edit', $article)->with('success', 'Perubahan artikel disimpan.');
    }

    public function destroy(Article $article)
    {
        MediaStore::delete($article->featured_image);
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel dihapus.');
    }

    /** Pratinjau: tampilan publik, bisa dibuka walau masih draft. */
    public function preview(Article $article)
    {
        $article->load(['category', 'author']);

        return view('site.articles.show', [
            'article' => $article,
            'related' => SiteArticleController::related($article),
            'preview' => true,
        ]);
    }

    /** Data yang sudah divalidasi + disanitasi, tanpa slug dan gambar. */
    private function payload(ArticleRequest $request): array
    {
        $data = $request->safe()->only([
            'title', 'article_category_id', 'author_id', 'excerpt', 'featured_image_alt',
            'status', 'published_at', 'seo_title', 'meta_description',
        ]);

        $body = HtmlSanitizer::clean($request->input('body'));
        if (trim(strip_tags($body, '<img>')) === '') {
            throw ValidationException::withMessages(['body' => 'Isi artikel tidak boleh kosong.']);
        }

        $data['body'] = $body;
        $data['is_featured'] = $request->boolean('is_featured');
        $data['author_id'] = $data['author_id'] ?? auth()->id();

        // Published tanpa tanggal => terbit sekarang.
        if ($data['status'] === Article::STATUS_PUBLISHED && blank($data['published_at'] ?? null)) {
            $data['published_at'] = now();
        }
        if (blank($data['published_at'] ?? null)) {
            $data['published_at'] = null;
        }

        return $data;
    }
}
