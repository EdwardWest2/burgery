<?php

namespace App\Support;

use App\Models\SiteSetting;

/** Akses pengaturan situs (tabel site_settings) dengan cache per-request. */
class Settings
{
    /** @var array<string, string|null>|null */
    private static ?array $cache = null;

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = self::all();

        if (isset($all[$key]) && $all[$key] !== '') {
            return $all[$key];
        }

        return $default ?? SettingDefaults::default($key);
    }

    /** @return array<string, string|null> */
    public static function all(): array
    {
        if (self::$cache === null) {
            try {
                self::$cache = SiteSetting::query()->pluck('value', 'key')->all();
            } catch (\Throwable $e) {
                // Tabel belum ada (mis. saat composer install / sebelum migrate).
                self::$cache = [];
            }
        }

        return self::$cache;
    }

    public static function forget(): void
    {
        self::$cache = null;
    }
}
