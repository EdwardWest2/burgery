<?php

return [
    // Batas upload (KB) dan tipe gambar yang diizinkan di CMS.
    'upload_max_kb' => 3072,
    'upload_mimes' => 'jpg,jpeg,png,webp',

    // Akun admin awal. Nilai berasal dari .env (lihat .env.example).
    'admin' => [
        'username' => env('ADMIN_USERNAME', 'admin'),
        'name' => env('ADMIN_NAME', 'Administrator Burgery'),
        'email' => env('ADMIN_EMAIL', 'admin@burgery.test'),
        'password' => env('ADMIN_PASSWORD', 'Burgery@Admin2026'),
    ],

    // Password development bawaan; seeder menolak memakainya di production.
    'default_dev_password' => 'Burgery@Admin2026',
];
