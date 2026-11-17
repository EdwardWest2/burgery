<?php

namespace App\Support;

/**
 * Definisi seluruh pengaturan situs: label, tipe input, nilai bawaan.
 * Dipakai oleh seeder, form Pengaturan di admin, dan helper setting().
 *
 * Semua teks bawaan adalah KONTEN DEMONSTRASI yang netral. Ubahlah dari
 * Admin > Pengaturan sebelum website dipublikasikan.
 */
class SettingDefaults
{
    /**
     * @return array<string, array{label: string, fields: array<string, array{label: string, type: string, default: string, help?: string}>}>
     */
    public static function schema(): array
    {
        return [
            'brand' => [
                'label' => 'Brand',
                'fields' => [
                    'brand_name' => ['label' => 'Nama brand', 'type' => 'text', 'default' => 'Burgery'],
                    'brand_tagline' => ['label' => 'Tagline', 'type' => 'text', 'default' => 'Restoran Burger', 'help' => 'Teks kecil di bawah logo pada navigasi.'],
                    'brand_description' => ['label' => 'Deskripsi singkat', 'type' => 'textarea', 'default' => 'Burgery adalah restoran burger. Temukan menu, cerita, dan informasi lokasi kami di sini.', 'help' => 'Dipakai di footer.'],
                    'logo' => ['label' => 'Logo (opsional)', 'type' => 'image', 'default' => '', 'help' => 'Jika kosong, dipakai ikon burger bawaan template. JPG/PNG/WebP, maks. 3 MB.'],
                ],
            ],
            'contact' => [
                'label' => 'Kontak & Lokasi',
                'fields' => [
                    'email' => ['label' => 'Email', 'type' => 'email', 'default' => 'halo@burgery.test', 'help' => 'Alamat contoh. Ganti dengan email resmi.'],
                    'phone' => ['label' => 'Nomor telepon', 'type' => 'text', 'default' => ''],
                    'address' => ['label' => 'Alamat', 'type' => 'textarea', 'default' => 'Alamat restoran akan segera diumumkan.'],
                    'city' => ['label' => 'Kota (singkat)', 'type' => 'text', 'default' => '', 'help' => 'Tampil di kartu hero. Kosongkan untuk menyembunyikan.'],
                    'opening_hours' => ['label' => 'Jam operasional', 'type' => 'textarea', 'default' => "Setiap hari | 10.00 - 22.00", 'help' => 'Satu baris per hari/kelompok hari, format: Hari | Jam. Tulis "Tutup" pada jam untuk hari libur. Contoh: Senin - Jumat | 10.00 - 22.00'],
                ],
            ],
            'social' => [
                'label' => 'Media Sosial',
                'fields' => [
                    'facebook_url' => ['label' => 'Facebook', 'type' => 'url', 'default' => ''],
                    'instagram_url' => ['label' => 'Instagram', 'type' => 'url', 'default' => ''],
                    'tiktok_url' => ['label' => 'TikTok', 'type' => 'url', 'default' => ''],
                    'youtube_url' => ['label' => 'YouTube', 'type' => 'url', 'default' => ''],
                ],
            ],
            'home' => [
                'label' => 'Beranda',
                'fields' => [
                    'hero_badge' => ['label' => 'Hero: teks lencana', 'type' => 'text', 'default' => 'Selamat datang di Burgery'],
                    'hero_title' => ['label' => 'Hero: judul', 'type' => 'text', 'default' => 'Nikmati Burger Juicy untuk Setiap Momen'],
                    'hero_highlight' => ['label' => 'Hero: kata yang diberi warna', 'type' => 'text', 'default' => 'Burger Juicy', 'help' => 'Harus sama persis dengan sebagian judul.'],
                    'hero_text' => ['label' => 'Hero: paragraf', 'type' => 'textarea', 'default' => 'Jelajahi menu burger Burgery, dari pilihan klasik sampai ayam krispi. Lihat menunya, kenali kami, lalu mampir.'],
                    'video_url' => ['label' => 'Hero: tautan video (opsional)', 'type' => 'url', 'default' => '', 'help' => 'Tautan YouTube. Jika diisi, tombol "Tonton Cerita Kami" muncul di hero.'],
                    'about_heading' => ['label' => 'Tentang (beranda): judul', 'type' => 'text', 'default' => 'Selamat Datang di Burgery'],
                    'about_text' => ['label' => 'Tentang (beranda): paragraf', 'type' => 'textarea', 'default' => 'Di Burgery, burger menjadi pusat perhatian: roti, patty, dan saus disusun untuk dinikmati bersama keluarga, teman, atau rekan kerja. Kenali cerita dan nilai-nilai kami di halaman Tentang Kami.'],
                    'adv_1_title' => ['label' => 'Keunggulan 1: judul', 'type' => 'text', 'default' => 'Fokus pada Rasa'],
                    'adv_1_text' => ['label' => 'Keunggulan 1: teks', 'type' => 'textarea', 'default' => 'Setiap menu disusun agar rasa roti, isian, dan saus saling melengkapi.'],
                    'adv_2_title' => ['label' => 'Keunggulan 2: judul', 'type' => 'text', 'default' => 'Kualitas yang Dijaga'],
                    'adv_2_text' => ['label' => 'Keunggulan 2: teks', 'type' => 'textarea', 'default' => 'Kami berkomitmen menjaga konsistensi rasa dan kebersihan di setiap penyajian.'],
                    'adv_3_title' => ['label' => 'Keunggulan 3: judul', 'type' => 'text', 'default' => 'Suasana Nyaman'],
                    'adv_3_text' => ['label' => 'Keunggulan 3: teks', 'type' => 'textarea', 'default' => 'Tempat yang pas untuk makan bersama keluarga, teman, atau rekan kerja.'],
                ],
            ],
            'footer' => [
                'label' => 'Footer & SEO',
                'fields' => [
                    'footer_text' => ['label' => 'Teks hak cipta footer', 'type' => 'text', 'default' => 'Seluruh hak cipta dilindungi.'],
                    'seo_default_title' => ['label' => 'SEO: judul bawaan', 'type' => 'text', 'default' => 'Burgery - Restoran Burger'],
                    'seo_default_description' => ['label' => 'SEO: deskripsi bawaan', 'type' => 'textarea', 'default' => 'Burgery adalah restoran burger. Lihat menu, artikel, galeri, dan informasi lokasi kami.'],
                ],
            ],
        ];
    }

    /** @return array<string, array{label: string, type: string, default: string, help?: string}> */
    public static function fields(): array
    {
        $fields = [];
        foreach (self::schema() as $group) {
            $fields += $group['fields'];
        }

        return $fields;
    }

    public static function default(string $key): ?string
    {
        return self::fields()[$key]['default'] ?? null;
    }
}
