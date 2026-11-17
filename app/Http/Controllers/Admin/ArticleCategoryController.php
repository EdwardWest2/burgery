<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use App\Support\Slugger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArticleCategoryController extends Controller
{
    public function index()
    {
        return view('admin.article-categories.index', [
            'categories' => ArticleCategory::withCount('articles')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('article_categories', 'name')],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $data['slug'] = Slugger::unique(ArticleCategory::class, $data['name']);
        ArticleCategory::create($data);

        return redirect()->route('admin.article-categories.index')->with('success', 'Kategori ditambahkan.');
    }

    public function edit(ArticleCategory $category)
    {
        return view('admin.article-categories.edit', ['category' => $category->loadCount('articles')]);
    }

    public function update(Request $request, ArticleCategory $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('article_categories', 'name')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        if ($data['name'] !== $category->name) {
            $data['slug'] = Slugger::unique(ArticleCategory::class, $data['name'], $category->id);
        }

        $category->update($data);

        return redirect()->route('admin.article-categories.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(ArticleCategory $category)
    {
        if ($category->articles()->exists()) {
            return back()->with('error', 'Kategori masih dipakai oleh artikel. Pindahkan atau hapus artikelnya terlebih dahulu.');
        }

        $category->delete();

        return redirect()->route('admin.article-categories.index')->with('success', 'Kategori dihapus.');
    }
}
