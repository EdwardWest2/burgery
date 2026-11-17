<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Support\MediaStore;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        return view('admin.gallery.index', [
            'photos' => Gallery::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'images' => ['required', 'array', 'max:20'],
            'images.*' => ['image', 'mimes:'.config('burgery.upload_mimes'), 'max:'.config('burgery.upload_max_kb')],
            'caption' => ['nullable', 'string', 'max:150'],
            'alt_text' => ['nullable', 'string', 'max:200'],
        ], [
            'images.required' => 'Pilih minimal satu gambar.',
            'images.*.image' => 'Setiap file harus berupa gambar (JPG, PNG, atau WebP).',
            'images.*.max' => 'Setiap gambar maksimal 3 MB.',
        ]);

        $next = (int) Gallery::max('sort_order') + 1;

        foreach ($request->file('images') as $file) {
            Gallery::create([
                'image' => MediaStore::put($file, 'gallery'),
                'caption' => $request->input('caption'),
                'alt_text' => $request->input('alt_text'),
                'sort_order' => $next++,
            ]);
        }

        return redirect()->route('admin.gallery.index')->with('success', 'Gambar diunggah.');
    }

    public function edit(Gallery $photo)
    {
        return view('admin.gallery.edit', ['photo' => $photo]);
    }

    public function update(Request $request, Gallery $photo)
    {
        $data = $request->validate([
            'caption' => ['nullable', 'string', 'max:150'],
            'alt_text' => ['nullable', 'string', 'max:200'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'image' => ['nullable', 'image', 'mimes:'.config('burgery.upload_mimes'), 'max:'.config('burgery.upload_max_kb')],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        unset($data['image']);

        if ($request->hasFile('image')) {
            $data['image'] = MediaStore::replace($request->file('image'), $photo->image, 'gallery');
        }

        $photo->update($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Gambar diperbarui.');
    }

    public function destroy(Gallery $photo)
    {
        MediaStore::delete($photo->image);
        $photo->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Gambar dihapus.');
    }
}
