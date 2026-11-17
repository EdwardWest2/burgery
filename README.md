# Burgery - Website Restoran Burger (Laravel + MySQL + CMS)

Website company profile restoran **Burgery**: frontend berbasis template **Sarab** (lihat
`THIRD-PARTY-NOTICES.md`) dan panel admin CMS untuk mengelola artikel, menu, galeri, halaman,
testimoni, lokasi, pesan kontak, dan pengaturan website.

> **Tidak ada sistem pemesanan makanan.** Tidak ada keranjang, checkout, pembayaran, atau delivery.
> Tombol pada menu hanya mengarah ke detail menu, kategori, serta lokasi/kontak.

---

## STATUS PENGUJIAN (baca dulu)

Proyek ini ditulis di lingkungan tanpa PHP, Composer, MySQL, dan jaringan, sehingga **kode belum
pernah dijalankan oleh pembuatnya**. Yang sudah dilakukan hanyalah pemeriksaan statis
(keseimbangan tanda kurung PHP, keseimbangan direktif Blade, semua nama route yang dirujuk view ada,
semua file aset yang dirujuk ada, semua ikon Font Awesome yang dipakai ada).

Yang **belum** diuji: migration, seeder, login, CRUD, upload, form kontak, tampilan di browser,
responsivitas. Sangat mungkin masih ada bug kecil. Langkah 9 di bawah menjalankan rangkaian tes
otomatis (`php artisan test`) yang sudah disiapkan; mohon jalankan dan laporkan kegagalan jika ada.

---

## 1. Persyaratan

- **XAMPP dengan PHP 8.2 atau lebih baru** (Laravel 11 mewajibkan PHP 8.2+). Cek: buka CMD, ketik `php -v`.
  Jika XAMPP Anda memakai PHP 8.0/8.1, unduh XAMPP versi PHP 8.2+ dari apachefriends.org.
- Ekstensi PHP aktif (umumnya sudah aktif di XAMPP; cek di `C:\xampp\php\php.ini`, hapus tanda `;`
  di depan barisnya jika perlu): `mbstring`, `openssl`, `pdo_mysql`, `fileinfo`, `curl`, `gd`, `zip`,
  `pdo_sqlite` (hanya untuk menjalankan tes).
- **Composer** (https://getcomposer.org/download/ - pasang versi Windows installer).
- Koneksi internet saat `composer install` dan untuk memuat Google Fonts.
- **Node.js/npm TIDAK diperlukan.** Semua aset frontend sudah ada di `public/assets` (tanpa Vite).

## 2. Ekstrak ZIP

Ekstrak `burgery-restaurant-website.zip` sehingga hasilnya berada di:

```
C:\xampp\htdocs\burgery\
    app\  bootstrap\  config\  database\  public\  resources\  routes\  artisan  composer.json ...
```

(Pastikan tidak menjadi `C:\xampp\htdocs\burgery\burgery\`.)

## 3. Buka CMD di folder proyek

```
cd /d C:\xampp\htdocs\burgery
```

## 4. Pasang dependency PHP

```
composer install
```

Folder `vendor` belum disertakan, jadi langkah ini wajib. Proses beberapa menit.
(File `composer.lock` tidak disertakan karena tidak dapat dibuat tanpa jaringan; Composer akan membuatnya.)

## 5. Dependency frontend

Tidak ada. Lewati.

## 6. Buat database di phpMyAdmin

1. Jalankan **Apache** dan **MySQL** di XAMPP Control Panel.
2. Buka http://localhost/phpmyadmin
3. Klik **New** / **Baru**, isi nama database `burgery`, collation `utf8mb4_unicode_ci`, klik **Create**.

## 7. Atur `.env`

```
copy .env.example .env
notepad .env
```

Untuk XAMPP bawaan (user `root` tanpa password) isi bawaan sudah cocok:

```
APP_URL=http://localhost/burgery/public
DB_DATABASE=burgery
DB_USERNAME=root
DB_PASSWORD=
```

Jika MySQL Anda memakai password atau port lain, ubah `DB_PASSWORD` / `DB_PORT`.

## 8. APP_KEY, migration, dan seeder

```
php artisan key:generate
php artisan migrate --seed
```

`migrate --seed` membuat semua tabel, akun admin, pengaturan awal, halaman, contoh menu, contoh
artikel, galeri, dan testimoni contoh. Aman dijalankan ulang untuk seeder (tidak menimpa konten yang
sudah Anda ubah, dan tidak membuat akun admin ganda). Jangan jalankan `migrate:fresh` kecuali ingin
menghapus semua data.

**Symbolic link storage: tidak diperlukan.** Unggahan disimpan di `public\uploads` (disk `uploads`),
sehingga `php artisan storage:link` (yang sering gagal di Windows tanpa hak Administrator) tidak dipakai.

## 9. (Disarankan) Jalankan tes otomatis

```
php artisan test
```

Tes memakai SQLite in-memory dan tidak menyentuh database `burgery`.

## 10. Buka website

| Halaman | URL |
|---|---|
| Website | http://localhost/burgery/public |
| Login admin | http://localhost/burgery/public/admin/login |
| Dashboard admin | http://localhost/burgery/public/admin |

URL cantik lain: `/menu`, `/artikel`, `/galeri`, `/tentang-kami`, `/kontak`, `/kebijakan-privasi`.

## 11. Kredensial admin DEVELOPMENT

| | |
|---|---|
| Username | `admin` |
| Password | `Burgery@Admin2026` |

Dibuat oleh seeder dari nilai `ADMIN_*` di `.env` (bawaan = nilai di atas). Ini kredensial **development**;
ganti sebelum website dipublikasikan. Di lingkungan `APP_ENV=production`, seeder menolak password bawaan ini.

### Mengganti password
- Lewat panel: **Profil & Password** (sidebar) -> isi password saat ini + password baru.
- Lewat CMD (tanpa perlu login, mis. lupa password):
  ```
  php artisan burgery:admin --username=admin --password=PasswordBaruAnda123
  ```

### Membuat ulang akun admin
```
php artisan burgery:admin
```
Memakai nilai `ADMIN_*` dari `.env`. Idempoten: jika username sudah ada, akun diperbarui, bukan digandakan.
Opsi: `--username= --password= --name= --email=`.

## 12. Panduan singkat CMS

- **Artikel**: judul, slug otomatis, ringkasan, editor teks (heading, bold/italic, daftar, tautan, gambar),
  gambar utama + alt text, kategori, penulis, SEO title, meta description, artikel unggulan.
  Status **Draft** (tidak tampil publik) atau **Published**. Tombol **Pratinjau** menampilkan halaman publik
  walau masih draft. Hapus meminta konfirmasi dan ikut menghapus gambar utama.
- **Jadwal terbit**: pilih Published + tanggal di masa depan. Artikel otomatis tayang saat waktunya tiba,
  berdasarkan filter tanggal pada query. **Tidak perlu cron/scheduler.** Di daftar admin berstatus "Terjadwal".
- **Menu** dan **Kategori Menu**; **Galeri** (unggah banyak foto, caption, alt text, urutan); **Halaman**
  (Tentang Kami, Kebijakan Privasi); **Testimoni**; **Lokasi** tambahan; **Pesan Kontak** (cari, ubah status, hapus);
  **Pengaturan** (brand, logo, kontak, jam, sosial, teks beranda, footer, SEO).
- Gambar yang disisipkan di dalam isi artikel lewat editor tidak ikut terhapus saat artikel dihapus.
- Gambar di dalam isi artikel disimpan dengan URL lengkap; jika domain situs berubah, URL itu perlu disesuaikan.

## 13. Konten yang HARUS diganti sebelum publikasi

Semua ini adalah konten demonstrasi (bukan klaim bisnis):
nama/harga/deskripsi menu contoh, tiga artikel contoh, tiga testimoni contoh (bukan ulasan pelanggan nyata),
teks "Tentang Kami" dan "Kebijakan Privasi" contoh, alamat ("akan segera diumumkan"), email `halo@burgery.test`,
jam buka `10.00 - 22.00`, nomor telepon (kosong), tautan sosial (kosong), dan **semua foto** (foto contoh template).
Kebijakan Privasi sebaiknya ditinjau penasihat hukum.

## 14. Keamanan - ringkasan implementasi

Login dengan password ter-hash (bcrypt) dan pembatasan 5 percobaan/menit; session diregenerasi saat login;
CSRF aktif di semua form; semua route `/admin/*` (kecuali login) di balik middleware `auth`; tidak ada registrasi publik;
validasi server-side via Form Request; upload dibatasi JPG/PNG/WebP maks. 3 MB, nama file acak, dan
`public/uploads/.htaccess` melarang eksekusi skrip; `$fillable` pada semua model; output Blade di-escape;
isi artikel/halaman disanitasi dengan allowlist (`app/Support/HtmlSanitizer.php`) saat disimpan;
artikel draft/terjadwal memberi 404 untuk publik; `.htaccess` di root proyek memblokir akses web ke `.env`,
`app/`, `storage/`, `vendor/`, dll.; form kontak punya honeypot, batas 3/menit dan 20/hari per IP, dan validasi.

**Untuk production:** set `APP_ENV=production`, `APP_DEBUG=false` (agar stack trace tidak tampil), ganti password admin,
dan idealnya arahkan Document Root Apache ke folder `public`.

## 15. Mengatasi error umum

| Gejala | Solusi |
|---|---|
| `'composer' is not recognized` | Pasang Composer, tutup lalu buka ulang CMD. |
| `Your PHP version ... does not satisfy` | Butuh PHP 8.2+. Perbarui XAMPP. |
| `could not find driver` | Aktifkan `extension=pdo_mysql` di `C:\xampp\php\php.ini`, restart Apache. |
| `No application encryption key has been specified` | Jalankan `php artisan key:generate`. Pastikan file `.env` ada. |
| `SQLSTATE[HY000] [1049] Unknown database` | Database `burgery` belum dibuat (langkah 6) atau nama di `.env` berbeda. |
| `SQLSTATE[HY000] [2002]` / connection refused | MySQL belum jalan di XAMPP, atau `DB_PORT` salah. |
| `Class "..." not found` / `Target class does not exist` | `composer dump-autoload` lalu `php artisan optimize:clear`. |
| Halaman selain beranda 404 | Aktifkan `mod_rewrite` di Apache (aktif secara bawaan di XAMPP) dan pastikan `AllowOverride All`. |
| Tampilan tanpa CSS/ikon | Buka lewat `http://localhost/burgery/public` (bukan file langsung). Tekan Ctrl+F5. |
| Upload gambar gagal / "gagal diunggah" | `upload_max_filesize` dan `post_max_size` di `php.ini` XAMPP bawaan hanya 2M/8M; naikkan `upload_max_filesize=8M`, restart Apache. |
| Perubahan `.env` tak berlaku | `php artisan config:clear` |
| Lupa password admin | `php artisan burgery:admin --password=PasswordBaru123` |
| Halaman error 500 tanpa pesan | Lihat `storage\logs\laravel.log`. Pastikan folder `storage` dan `bootstrap\cache` dapat ditulis. |
| Font terlihat berbeda | Google Fonts butuh internet. |

## 16. Struktur

```
app/Http/Controllers/Site    controller halaman publik
app/Http/Controllers/Admin   controller CMS
app/Http/Requests            Form Request (validasi)
app/Models                   model Eloquent
app/Support                  HtmlSanitizer, MediaStore, Slugger, Settings, SettingDefaults, AdminAccount
app/Console/Commands         php artisan burgery:admin
database/migrations|seeders  skema dan data awal
resources/views              site/ (publik), admin/ (CMS), layouts/, partials/
public/assets                CSS/JS/gambar template Sarab + burgery.css, admin.css, admin.js
public/uploads               unggahan CMS (jangan dihapus)
tests                        tes otomatis PHPUnit
```

## 17. Perubahan terhadap template Sarab

Dipertahankan: palet warna, tipografi (Playfair Display/Poppins/Dancing Script), topbar, navbar, hero beserta kartu
melayang, marquee, kartu kategori, section tentang, kartu menu, galeri + popup, slider testimoni, section jam buka,
kartu artikel, footer, AOS, dan Swiper.

Dihapus/diganti karena tidak sesuai kebutuhan atau memuat klaim yang tidak diketahui: keranjang dan popup pesan
menu, penawaran diskon dan hitung mundur, form reservasi, newsletter, profil koki, linimasa sejarah, rating bintang,
statistik "pelanggan puas", dan lencana "12+ tahun". Angka di hero kini dihitung dari database
(jumlah menu, kategori, artikel).
