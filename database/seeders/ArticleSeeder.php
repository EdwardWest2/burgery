<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /** Artikel CONTOH. Hapus atau ganti dari Admin > Artikel. */
    public function run(): void
    {
        $author = User::query()->orderBy('id')->first();

        $stories = ArticleCategory::firstOrCreate(['slug' => 'cerita-burger'], [
            'name' => 'Cerita Burger',
            'description' => 'Tulisan seputar dunia burger.',
        ]);
        $tips = ArticleCategory::firstOrCreate(['slug' => 'tips-kuliner'], [
            'name' => 'Tips Kuliner',
            'description' => 'Tips praktis menikmati makanan.',
        ]);
        $news = ArticleCategory::firstOrCreate(['slug' => 'kabar-burgery'], [
            'name' => 'Kabar Burgery',
            'description' => 'Kabar dan pengumuman dari restoran.',
        ]);

        Article::firstOrCreate(['slug' => 'mengenal-anatomi-burger-yang-seimbang'], [
            'article_category_id' => $stories->id,
            'author_id' => $author?->id,
            'title' => 'Mengenal Anatomi Burger yang Seimbang',
            'excerpt' => 'Roti, patty, keju, sayur, dan saus: bagaimana tiap bagian bekerja sama membentuk burger yang enak.',
            'featured_image' => 'assets/img/blog/1.jpg',
            'featured_image_alt' => 'Burger dengan dua patty, keju, selada, dan tomat',
            'status' => Article::STATUS_PUBLISHED,
            'is_featured' => true,
            'published_at' => now()->subDays(7),
            'seo_title' => 'Mengenal Anatomi Burger yang Seimbang',
            'meta_description' => 'Mengenal peran roti, patty, keju, sayur, dan saus dalam sebuah burger.',
            'body' => <<<'HTML'
<p>Burger terlihat sederhana, tetapi setiap lapisannya punya peran. Memahami peran itu membantu kita menilai mana burger yang seimbang.</p>
<h2>Roti: fondasi</h2>
<p>Roti harus cukup empuk untuk digigit, namun cukup kuat menahan isian dan saus tanpa hancur di tangan.</p>
<h2>Patty: karakter utama</h2>
<p>Patty memberi rasa gurih dan tekstur. Patty yang baik terasa juicy, tidak kering, dan tidak terlalu berminyak.</p>
<h2>Keju, sayur, dan saus: penyeimbang</h2>
<ul>
<li><strong>Keju</strong> menambah rasa gurih dan lembut.</li>
<li><strong>Sayur segar</strong> memberi kesegaran dan tekstur renyah.</li>
<li><strong>Saus</strong> menyatukan semuanya dan menambahkan sentuhan asam, manis, atau pedas.</li>
</ul>
<p><em>Artikel ini adalah contoh konten. Ganti atau hapus melalui Admin &rsaquo; Artikel.</em></p>
HTML,
        ]);

        Article::firstOrCreate(['slug' => 'tips-menikmati-burger-sampai-gigitan-terakhir'], [
            'article_category_id' => $tips->id,
            'author_id' => $author?->id,
            'title' => 'Tips Menikmati Burger Sampai Gigitan Terakhir',
            'excerpt' => 'Beberapa kebiasaan kecil yang membuat burger tetap enak dan tidak berantakan.',
            'featured_image' => 'assets/img/blog/3.jpg',
            'featured_image_alt' => 'Burger ayam dengan saus di atas kertas pembungkus',
            'status' => Article::STATUS_PUBLISHED,
            'is_featured' => false,
            'published_at' => now()->subDays(3),
            'meta_description' => 'Tips praktis menikmati burger agar tetap rapi dan nikmat.',
            'body' => <<<'HTML'
<p>Burger paling enak dinikmati selagi hangat. Berikut beberapa kebiasaan sederhana yang bisa membantu.</p>
<ol>
<li>Santap segera setelah disajikan agar roti dan patty tetap pada suhu terbaiknya.</li>
<li>Pegang burger dengan kedua tangan dan gigit dari sisi, bukan dari atas, supaya semua lapisan ikut terambil.</li>
<li>Sediakan tisu tambahan untuk saus yang melimpah.</li>
</ol>
<p><em>Artikel ini adalah contoh konten.</em></p>
HTML,
        ]);

        Article::firstOrCreate(['slug' => 'kabar-terbaru-dari-burgery'], [
            'article_category_id' => $news->id,
            'author_id' => $author?->id,
            'title' => 'Kabar Terbaru dari Burgery (Contoh Draft)',
            'excerpt' => 'Contoh artikel berstatus draft. Artikel ini tidak tampil di website publik.',
            'status' => Article::STATUS_DRAFT,
            'is_featured' => false,
            'published_at' => null,
            'body' => '<p>Ini adalah contoh artikel draft. Ubah statusnya menjadi Published untuk menampilkannya di website.</p>',
        ]);
    }
}
