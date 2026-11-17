<?php

use App\Support\Settings;
use Illuminate\Support\HtmlString;

if (! function_exists('setting')) {
    /** Ambil nilai pengaturan situs (dengan nilai bawaan). */
    function setting(string $key, mixed $default = null): mixed
    {
        return Settings::get($key, $default);
    }
}

if (! function_exists('media_url')) {
    /**
     * URL publik sebuah gambar.
     * - "assets/..." = aset bawaan template (public/assets)
     * - lainnya     = file unggahan CMS (public/uploads)
     */
    function media_url(?string $path, ?string $fallback = null): ?string
    {
        if (blank($path)) {
            return $fallback ? asset($fallback) : null;
        }

        if (str_starts_with($path, 'assets/')) {
            return asset($path);
        }

        return asset('uploads/'.ltrim($path, '/'));
    }
}

if (! function_exists('rupiah')) {
    function rupiah(int|float|null $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }
}

if (! function_exists('highlight_text')) {
    /** Escape teks lalu bungkus kata tertentu dengan <span class="hl">. */
    function highlight_text(string $text, ?string $word, string $class = 'hl'): HtmlString
    {
        if ($word !== null && $word !== '' && ($pos = mb_stripos($text, $word)) !== false) {
            $length = mb_strlen($word);

            return new HtmlString(
                e(mb_substr($text, 0, $pos))
                .'<span class="'.e($class).'">'.e(mb_substr($text, $pos, $length)).'</span>'
                .e(mb_substr($text, $pos + $length))
            );
        }

        return new HtmlString(e($text));
    }
}

if (! function_exists('brand_wordmark')) {
    /** "Burgery" -> Burg<span>ery</span> (gaya wordmark Sarab). */
    function brand_wordmark(): HtmlString
    {
        $name = (string) setting('brand_name', 'Burgery');

        if (mb_strlen($name) >= 5) {
            return new HtmlString(e(mb_substr($name, 0, -3)).'<span>'.e(mb_substr($name, -3)).'</span>');
        }

        return new HtmlString(e($name));
    }
}

if (! function_exists('parse_hours')) {
    /**
     * Ubah teks "Hari | Jam" (satu per baris) menjadi array.
     *
     * @return array<int, array{label: string, time: string, closed: bool}>
     */
    function parse_hours(?string $text): array
    {
        $rows = [];

        foreach (preg_split('/\R/u', trim((string) $text)) ?: [] as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $parts = array_map('trim', explode('|', $line, 2));
            $label = $parts[0];
            $time = $parts[1] ?? '';

            $rows[] = [
                'label' => $label,
                'time' => $time,
                'closed' => stripos($time, 'tutup') !== false,
            ];
        }

        return $rows;
    }
}

if (! function_exists('social_links')) {
    /** @return array<string, array{url: string, icon: string, label: string}> */
    function social_links(): array
    {
        $map = [
            'facebook_url' => ['fab fa-facebook-f', 'Facebook'],
            'instagram_url' => ['fab fa-instagram', 'Instagram'],
            'tiktok_url' => ['fab fa-tiktok', 'TikTok'],
            'youtube_url' => ['fab fa-youtube', 'YouTube'],
        ];

        $links = [];
        foreach ($map as $key => [$icon, $label]) {
            $url = (string) setting($key, '');
            if ($url !== '') {
                $links[$key] = ['url' => $url, 'icon' => $icon, 'label' => $label];
            }
        }

        return $links;
    }
}
