<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // route sudah dilindungi middleware auth
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'article_category_id' => ['required', 'exists:article_categories,id'],
            'author_id' => ['nullable', 'exists:users,id'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:300000'],
            'featured_image' => ['nullable', 'image', 'mimes:'.config('burgery.upload_mimes'), 'max:'.config('burgery.upload_max_kb')],
            'featured_image_alt' => ['nullable', 'string', 'max:200'],
            'remove_image' => ['nullable', 'boolean'],
            'status' => ['required', 'in:draft,published'],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string', 'max:200'],
            'meta_description' => ['nullable', 'string', 'max:320'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung (contoh: judul-artikel-saya).',
            'featured_image.max' => 'Ukuran gambar maksimal 3 MB.',
        ];
    }
}
