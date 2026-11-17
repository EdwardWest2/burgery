<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /** Foto CONTOH bawaan template. Ganti dengan foto restoran asli dari Admin > Galeri. */
    public function run(): void
    {
        $photos = [
            ['assets/img/portfolio/work1.jpg', 'Burger klasik', 'Burger klasik dengan roti bertabur wijen di atas meja kayu', 1],
            ['assets/img/about1.jpg', 'Ruang makan', 'Ruang makan restoran dengan deretan meja dan kursi', 2],
            ['assets/img/portfolio/work3.jpg', 'Burger ayam', 'Burger ayam dengan saus creamy di atas kertas pembungkus', 3],
            ['assets/img/off-img.jpg', 'Burger dan kentang goreng', 'Burger berdampingan dengan kentang goreng di atas talenan', 4],
            ['assets/img/portfolio/work4.jpg', 'Donat', 'Tumpukan donat dengan taburan sprinkle', 5],
            ['assets/img/about2.jpg', 'Hidangan di meja', 'Hidangan tersaji di atas piring di meja makan', 6],
        ];

        foreach ($photos as [$image, $caption, $alt, $order]) {
            Gallery::firstOrCreate(['image' => $image], [
                'caption' => $caption,
                'alt_text' => $alt,
                'sort_order' => $order,
            ]);
        }
    }
}
