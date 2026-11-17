<?php

namespace App\Console\Commands;

use App\Support\AdminAccount;
use Illuminate\Console\Command;
use RuntimeException;

class CreateAdminCommand extends Command
{
    protected $signature = 'burgery:admin
                            {--username= : Username admin (bawaan dari ADMIN_USERNAME di .env)}
                            {--password= : Password admin (bawaan dari ADMIN_PASSWORD di .env)}
                            {--name= : Nama tampilan}
                            {--email= : Email admin}';

    protected $description = 'Buat atau reset akun admin Burgery (aman dijalankan berulang kali).';

    public function handle(): int
    {
        try {
            $user = AdminAccount::ensure(
                $this->option('username'),
                $this->option('password'),
                $this->option('name'),
                $this->option('email'),
            );
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info("Akun admin siap. Username: {$user->username}");
        $this->line('Login di: '.url('admin/login'));

        return self::SUCCESS;
    }
}
