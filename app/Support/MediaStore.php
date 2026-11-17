<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Penyimpanan gambar CMS pada disk "uploads" (public/uploads).
 * Nama file dibuat acak oleh Laravel; ekstensi diturunkan dari isi file,
 * bukan dari nama file asli, sehingga tidak ada file executable yang lolos.
 */
class MediaStore
{
    public static function put(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'uploads');
    }

    /** Hapus file hasil upload. Aset bawaan template (assets/...) tidak pernah dihapus. */
    public static function delete(?string $path): void
    {
        if (blank($path) || str_starts_with($path, 'assets/')) {
            return;
        }

        Storage::disk('uploads')->delete($path);
    }

    /** Ganti file lama dengan file baru; kembalikan path yang harus disimpan. */
    public static function replace(?UploadedFile $new, ?string $oldPath, string $directory): ?string
    {
        if (! $new) {
            return $oldPath;
        }

        $path = self::put($new, $directory);
        self::delete($oldPath);

        return $path;
    }
}
