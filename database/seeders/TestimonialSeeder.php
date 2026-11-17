<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Ulasan CONTOH (bukan ulasan pelanggan nyata). Ganti dengan ulasan asli
     * dari Admin > Testimoni, atau nonaktifkan agar bagian ini tersembunyi.
     */
    public function run(): void
    {
        if (Testimonial::query()->exists()) {
            return;
        }

        $samples = [
            ['Pelanggan Burgery', 'Contoh ulasan', 'Ini contoh teks ulasan. Ganti dengan ulasan pelanggan yang sebenarnya melalui panel admin.'],
            ['Pelanggan Burgery', 'Contoh ulasan', 'Ini contoh teks ulasan kedua. Pastikan Anda memiliki izin pelanggan sebelum menampilkan ulasan mereka.'],
            ['Pelanggan Burgery', 'Contoh ulasan', 'Ini contoh teks ulasan ketiga. Bagian testimoni akan tersembunyi jika tidak ada ulasan aktif.'],
        ];

        foreach ($samples as $i => [$name, $role, $content]) {
            Testimonial::create([
                'name' => $name,
                'role' => $role,
                'content' => $content,
                'is_active' => true,
                'sort_order' => $i + 1,
            ]);
        }
    }
}
