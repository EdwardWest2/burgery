<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\HtmlSanitizer;
use App\Support\MediaStore;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PageController extends Controller
{
    public function index()
    {
        return view('admin.pages.index', ['pages' => Page::orderBy('title')->get()]);
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', ['page' => $page]);
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:300000'],
            'image' => ['nullable', 'image', 'mimes:'.config('burgery.upload_mimes'), 'max:'.config('burgery.upload_max_kb')],
            'image_alt' => ['nullable', 'string', 'max:200'],
            'remove_image' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:200'],
            'meta_description' => ['nullable', 'string', 'max:320'],
        ]);

        $data['body'] = HtmlSanitizer::clean($data['body']);
        if (trim(strip_tags($data['body'], '<img>')) === '') {
            throw ValidationException::withMessages(['body' => 'Isi halaman tidak boleh kosong.']);
        }

        unset($data['image'], $data['remove_image']);

        if ($request->boolean('remove_image') && ! $request->hasFile('image')) {
            MediaStore::delete($page->image);
            $data['image'] = null;
        }
        if ($request->hasFile('image')) {
            $data['image'] = MediaStore::replace($request->file('image'), $page->image, 'pages');
        }

        $page->update($data);

        return redirect()->route('admin.pages.edit', $page)->with('success', 'Halaman disimpan.');
    }
}
