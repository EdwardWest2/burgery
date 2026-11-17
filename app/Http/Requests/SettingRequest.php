<?php

namespace App\Http\Requests;

use App\Support\SettingDefaults;
use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'remove_logo' => ['nullable', 'boolean'],
        ];

        foreach (SettingDefaults::fields() as $key => $field) {
            $rules[$key] = match ($field['type']) {
                'image' => ['nullable', 'image', 'mimes:'.config('burgery.upload_mimes'), 'max:'.config('burgery.upload_max_kb')],
                'url' => ['nullable', 'url', 'max:255'],
                'email' => ['nullable', 'email', 'max:150'],
                'textarea' => ['nullable', 'string', 'max:2000'],
                default => ['nullable', 'string', 'max:255'],
            };
        }

        return $rules;
    }

    public function attributes(): array
    {
        return array_map(fn ($f) => $f['label'], SettingDefaults::fields());
    }
}
