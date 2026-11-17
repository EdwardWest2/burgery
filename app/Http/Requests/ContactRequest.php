<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    public const SUBJECTS = [
        'Pertanyaan Umum',
        'Acara & Katering',
        'Kritik & Saran',
        'Kerja Sama',
        'Media & Pers',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40', 'regex:/^[0-9+\-\s().]+$/'],
            'subject' => ['required', 'string', Rule::in(self::SUBJECTS)],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            'website' => ['nullable', 'string'], // honeypot
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka, spasi, dan tanda + - ( ) .',
            'message.min' => 'Pesan terlalu singkat (minimal 10 karakter).',
        ];
    }
}
