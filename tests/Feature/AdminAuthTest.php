<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_every_admin_area(): void
    {
        foreach (['/admin', '/admin/articles', '/admin/menu-items', '/admin/settings', '/admin/messages', '/admin/gallery'] as $url) {
            $this->get($url)->assertRedirect('/admin/login');
        }
    }

    public function test_seeder_creates_admin_once_and_credentials_work(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class); // idempoten

        $this->assertSame(1, User::where('username', 'admin')->count());

        $this->post('/admin/login', ['username' => 'admin', 'password' => 'Burgery@Admin2026'])
            ->assertRedirect('/admin');
        $this->assertAuthenticated();
    }

    public function test_wrong_password_is_rejected(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->from('/admin/login')
            ->post('/admin/login', ['username' => 'admin', 'password' => 'salah'])
            ->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_logout(): void
    {
        $user = User::create(['name' => 'T', 'username' => 'tester', 'email' => 't@x.test', 'password' => 'password123']);

        $this->actingAs($user)->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_admin_command_is_idempotent(): void
    {
        $this->artisan('burgery:admin', ['--username' => 'owner', '--password' => 'RahasiaBaru123'])->assertExitCode(0);
        $this->artisan('burgery:admin', ['--username' => 'owner', '--password' => 'RahasiaBaru123'])->assertExitCode(0);

        $this->assertSame(1, User::where('username', 'owner')->count());
    }
}
