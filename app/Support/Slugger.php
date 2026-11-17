<?php

namespace App\Support;

use Illuminate\Support\Str;

class Slugger
{
    /**
     * Membuat slug unik untuk sebuah model. Jika slug sudah dipakai,
     * ditambahkan akhiran -2, -3, dst.
     *
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $modelClass
     */
    public static function unique(string $modelClass, string $source, ?int $ignoreId = null, string $column = 'slug'): string
    {
        $base = Str::slug($source);
        if ($base === '') {
            $base = 'item';
        }
        $base = Str::limit($base, 180, '');

        $candidate = $base;
        $i = 2;

        while ($modelClass::query()
            ->where($column, $candidate)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $candidate = $base.'-'.$i;
            $i++;
        }

        return $candidate;
    }
}
