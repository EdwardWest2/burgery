<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Support\Slugger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuCategoryController extends Controller
{
    public function index()
    {
        return view('admin.menu-categories.index', [
            'categories' => MenuCategory::withCount('items')->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('menu_categories', 'name')],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        $data['slug'] = Slugger::unique(MenuCategory::class, $data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        MenuCategory::create($data);

        return redirect()->route('admin.menu-categories.index')->with('success', 'Kategori menu ditambahkan.');
    }

    public function edit(MenuCategory $category)
    {
        return view('admin.menu-categories.edit', ['category' => $category->loadCount('items')]);
    }

    public function update(Request $request, MenuCategory $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('menu_categories', 'name')->ignore($category->id)],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        if ($data['name'] !== $category->name) {
            $data['slug'] = Slugger::unique(MenuCategory::class, $data['name'], $category->id);
        }
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $category->update($data);

        return redirect()->route('admin.menu-categories.index')->with('success', 'Kategori menu diperbarui.');
    }

    public function destroy(MenuCategory $category)
    {
        if ($category->items()->exists()) {
            return back()->with('error', 'Kategori masih berisi menu. Pindahkan atau hapus menunya terlebih dahulu.');
        }

        $category->delete();

        return redirect()->route('admin.menu-categories.index')->with('success', 'Kategori menu dihapus.');
    }
}
