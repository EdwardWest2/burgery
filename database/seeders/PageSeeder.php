<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::firstOrCreate(['slug' => 'tentang-kami'], [
            'title' => 'Tentang Kami',
            'excerpt' => 'Kenali cerita, filosofi, dan nilai-nilai di balik Burgery.',
            'image' => 'assets/img/about1.jpg',
            'image_alt' => 'Suasana ruang makan restoran',
            'seo_title' => 'Tentang Kami - Burgery',
            'meta_description' => 'Kenali cerita, filosofi, komitmen kualitas, dan nilai-nilai Burgery.',
            'body' => <<<'HTML'
<h2>Cerita Burgery</h2>
<p>Burgery adalah restoran yang menjadikan burger sebagai pusat perhatian. Halaman ini adalah ruang untuk menceritakan bagaimana Burgery bermula, siapa yang ada di baliknya, dan apa yang ingin dihadirkan untuk setiap tamu.</p>
<p><em>Teks ini adalah konten contoh. Pemilik dapat menggantinya dengan cerita asli melalui Admin &rsaquo; Halaman &rsaquo; Tentang Kami.</em></p>
<h2>Filosofi</h2>
<p>Burger yang baik lahir dari keseimbangan: roti yang pas, isian yang berkarakter, dan saus yang melengkapi. Filosofi ini menjadi pegangan dalam menyusun setiap menu.</p>
<h2>Komitmen terhadap Kualitas</h2>
<ul>
<li>Menjaga konsistensi rasa di setiap penyajian.</li>
<li>Menjaga kebersihan dapur dan area makan.</li>
<li>Mendengarkan masukan tamu untuk terus memperbaiki diri.</li>
</ul>
<h2>Nilai Kami</h2>
<ul>
<li><strong>Jujur</strong> dalam menyampaikan informasi menu dan harga.</li>
<li><strong>Ramah</strong> kepada setiap tamu.</li>
<li><strong>Bertanggung jawab</strong> atas kualitas yang disajikan.</li>
</ul>
HTML,
        ]);

        Page::firstOrCreate(['slug' => 'kebijakan-privasi'], [
            'title' => 'Kebijakan Privasi',
            'excerpt' => 'Bagaimana Burgery memperlakukan data yang Anda kirimkan melalui website ini.',
            'seo_title' => 'Kebijakan Privasi - Burgery',
            'meta_description' => 'Kebijakan privasi website Burgery.',
            'body' => <<<'HTML'
<p><em>Teks berikut adalah contoh dasar dan perlu ditinjau serta disesuaikan oleh pemilik bisnis (idealnya bersama penasihat hukum) sebelum website dipublikasikan.</em></p>
<h2>Data yang kami kumpulkan</h2>
<p>Website ini hanya mengumpulkan data yang Anda isi sendiri melalui formulir kontak: nama, email, nomor telepon (opsional), subjek, dan isi pesan. Kami juga mencatat alamat IP pengirim untuk mencegah spam.</p>
<h2>Penggunaan data</h2>
<p>Data tersebut digunakan semata-mata untuk menanggapi pesan Anda. Kami tidak menjual data Anda kepada pihak lain.</p>
<h2>Penyimpanan dan penghapusan</h2>
<p>Pesan disimpan pada sistem kami. Anda dapat meminta penghapusan pesan Anda dengan menghubungi kami melalui informasi kontak yang tersedia di website.</p>
<h2>Perubahan kebijakan</h2>
<p>Kebijakan ini dapat diperbarui sewaktu-waktu. Versi terbaru selalu ditampilkan di halaman ini.</p>
HTML,
        ]);
    }
}
