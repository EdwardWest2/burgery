<?php

return [
    'default' => env('FILESYSTEM_DISK', 'local'),
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL', 'http://localhost').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],
        // Disk khusus unggahan CMS (foto menu, artikel, galeri, logo).
        // Berada langsung di public/uploads sehingga TIDAK memerlukan
        // `php artisan storage:link` (sering bermasalah di Windows/XAMPP).
        'uploads' => [
            'driver' => 'local',
            'root' => public_path('uploads'),
            'visibility' => 'public',
            'throw' => false,
        ],
    ],
    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],
];
