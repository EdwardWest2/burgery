<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\MediaStore;
use Illuminate\Http\Request;

/** Upload gambar dari dalam editor artikel. Mengembalikan JSON {url}. */
class EditorUploadController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:'.config('burgery.upload_mimes'), 'max:'.config('burgery.upload_max_kb')],
        ], [
            'image.required' => 'Pilih gambar terlebih dahulu.',
            'image.image' => 'File harus berupa gambar JPG, PNG, atau WebP.',
            'image.max' => 'Ukuran gambar maksimal 3 MB.',
        ]);

        $path = MediaStore::put($request->file('image'), 'editor');

        return response()->json(['url' => media_url($path)]);
    }
}
