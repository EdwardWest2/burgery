<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuItemRequest;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Support\MediaStore;
use App\Support\Slugger;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $category = (int) $request->query('category', 0);

        $items = MenuItem::with('category')
            ->when($search !== '', fn ($q) => $q->where('name', 'like', '%'.addcslashes($search, '%_\\').'%'))
            ->when($category > 0, fn ($q) => $q->where('menu_category_id', $category))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('admin.menu-items.index', [
            'items' => $items,
            'categories' => MenuCategory::orderBy('sort_order')->get(),
            'filters' => compact('search', 'category'),
        ]);
    }

    public function create()
    {
        return view('admin.menu-items.form', [
            'item' => new MenuItem(['is_available' => true, 'sort_order' => 0]),
            'categories' => MenuCategory::orderBy('sort_order')->get(),
        ]);
    }

    public function store(MenuItemRequest $request)
    {
        $data = $this->payload($request);
        $data['slug'] = Slugger::unique(MenuItem::class, $request->input('slug') ?: $request->input('name'));

        if ($request->hasFile('image')) {
            $data['image'] = MediaStore::put($request->file('image'), 'menu');
        }

        MenuItem::create($data);

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu ditambahkan.');
    }

    public function edit(MenuItem $item)
    {
        return view('admin.menu-items.form', [
            'item' => $item,
            'categories' => MenuCategory::orderBy('sort_order')->get(),
        ]);
    }

    public function update(MenuItemRequest $request, MenuItem $item)
    {
        $data = $this->payload($request);
        $data['slug'] = Slugger::unique(MenuItem::class, $request->input('slug') ?: $item->slug, $item->id);

        if ($request->boolean('remove_image') && ! $request->hasFile('image')) {
            MediaStore::delete($item->image);
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            $data['image'] = MediaStore::replace($request->file('image'), $item->image, 'menu');
        }

        $item->update($data);

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu diperbarui.');
    }

    public function destroy(MenuItem $item)
    {
        MediaStore::delete($item->image);
        $item->delete();

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu dihapus.');
    }

    private function payload(MenuItemRequest $request): array
    {
        $data = $request->safe()->only(['name', 'menu_category_id', 'description', 'price', 'image_alt', 'sort_order']);
        $data['is_available'] = $request->boolean('is_available');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
