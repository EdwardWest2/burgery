<?php

namespace Database\Seeders;

use App\Support\AdminAccount;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = AdminAccount::ensure();

        $this->command?->info("Akun admin siap: username \"{$user->username}\"");
    }
}
