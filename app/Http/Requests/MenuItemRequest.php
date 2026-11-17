<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'menu_category_id' => ['required', 'exists:menu_categories,id'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'integer', 'min:0', 'max:100000000'],
            'image' => ['nullable', 'image', 'mimes:'.config('burgery.upload_mimes'), 'max:'.config('burgery.upload_max_kb')],
            'image_alt' => ['nullable', 'string', 'max:200'],
            'remove_image' => ['nullable', 'boolean'],
            'is_available' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung.',
            'price.integer' => 'Harga harus berupa angka bulat dalam Rupiah (tanpa titik/koma).',
        ];
    }
}
