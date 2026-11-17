<?php

namespace App\Support;

use App\Models\User;
use RuntimeException;

class AdminAccount
{
    /**
     * Membuat atau memperbarui akun admin (idempoten: dijalankan berulang
     * kali tidak menghasilkan akun duplikat, kunci = username).
     */
    public static function ensure(?string $username = null, ?string $password = null, ?string $name = null, ?string $email = null): User
    {
        $username = trim($username ?: config('burgery.admin.username'));
        $password = $password ?: config('burgery.admin.password');
        $name = $name ?: config('burgery.admin.name');
        $email = $email ?: config('burgery.admin.email');

        if (app()->isProduction() && $password === config('burgery.default_dev_password')) {
            throw new RuntimeException(
                'Password development bawaan tidak boleh dipakai di production. '
                .'Isi ADMIN_PASSWORD di .env atau gunakan: php artisan burgery:admin --password=PASSWORD_BARU'
            );
        }

        if (mb_strlen($password) < 8) {
            throw new RuntimeException('Password admin minimal 8 karakter.');
        }

        $emailTaken = User::query()
            ->where('email', $email)
            ->where('username', '!=', $username)
            ->exists();

        if ($emailTaken) {
            throw new RuntimeException("Email {$email} sudah dipakai akun lain.");
        }

        return User::updateOrCreate(
            ['username' => $username],
            ['name' => $name, 'email' => $email, 'password' => $password]
        );
    }
}
