<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MenuSettingsContactTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create(['name' => 'Admin', 'username' => 'admin', 'email' => 'a@x.test', 'password' => 'password123']);
    }

    public function test_menu_crud_reflects_on_frontend(): void
    {
        Storage::fake('uploads');
        $cat = MenuCategory::create(['name' => 'Burger', 'slug' => 'burger', 'sort_order' => 1]);

        $this->actingAs($this->admin())->post('/admin/menu-items', [
            'name' => 'Burger Uji', 'menu_category_id' => $cat->id, 'price' => 45000,
            'description' => 'Deskripsi uji', 'is_available' => 1, 'is_featured' => 1,
        ])->assertRedirect('/admin/menu-items');

        $this->get('/menu')->assertOk()->assertSee('Burger Uji')->assertSee('Rp 45.000');
        $this->get('/menu/burger-uji')->assertOk()->assertSee('Tersedia');
        $this->get('/')->assertOk()->assertSee('Burger Uji');

        $item = MenuItem::firstOrFail();
        $this->actingAs($this->admin())->put("/admin/menu-items/{$item->id}", [
            'name' => 'Burger Uji', 'menu_category_id' => $cat->id, 'price' => 50000,
        ])->assertRedirect();

        $this->get('/menu/burger-uji')->assertSee('Rp 50.000')->assertSee('Sedang habis');

        $this->get('/menu?q=tidakada')->assertOk()->assertSee('Tidak ada menu');
    }

    public function test_settings_update_is_reflected_on_frontend(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->actingAs(User::where('username', 'admin')->first())
            ->put('/admin/settings', ['brand_name' => 'Burgery Uji', 'phone' => '021-123456', 'email' => 'halo@burgery.test'])
            ->assertRedirect('/admin/settings');

        $this->get('/kontak')->assertSee('021-123456');
    }

    public function test_contact_form_saves_message_and_honeypot_blocks_bots(): void
    {
        $data = ['name' => 'Budi', 'email' => 'budi@contoh.test', 'subject' => 'Pertanyaan Umum', 'message' => 'Apakah buka hari Minggu?'];

        $this->post('/kontak', $data)->assertSessionHas('success');
        $this->assertDatabaseHas('contact_messages', ['email' => 'budi@contoh.test', 'status' => 'new']);

        $this->post('/kontak', $data + ['website' => 'spam.test']);
        $this->assertSame(1, ContactMessage::count());

        $this->post('/kontak', ['name' => ''])->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }

    public function test_admin_can_read_change_status_and_delete_message(): void
    {
        $m = ContactMessage::create(['name' => 'A', 'email' => 'a@a.test', 'subject' => 'Pertanyaan Umum', 'message' => 'Halo dunia ini pesan', 'status' => 'new']);
        $admin = $this->admin();

        $this->actingAs($admin)->get("/admin/messages/{$m->id}")->assertOk();
        $this->assertSame('read', $m->fresh()->status);

        $this->actingAs($admin)->put("/admin/messages/{$m->id}", ['status' => 'replied'])->assertSessionHas('success');
        $this->assertSame('replied', $m->fresh()->status);

        $this->actingAs($admin)->delete("/admin/messages/{$m->id}");
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_public_pages_render_after_seeding(): void
    {
        $this->seed(DatabaseSeeder::class);

        foreach (['/', '/tentang-kami', '/kebijakan-privasi', '/menu', '/artikel', '/galeri', '/kontak'] as $url) {
            $this->get($url)->assertOk();
        }
        $this->get('/artikel/mengenal-anatomi-burger-yang-seimbang')->assertOk();
        $this->get('/artikel/kabar-terbaru-dari-burgery')->assertNotFound(); // draft
        $this->get('/artikel')->assertDontSee('Contoh Draft');
        $this->get('/menu/classic-double-burger')->assertOk();
    }

    public function test_no_ordering_features_exist(): void
    {
        $this->seed(DatabaseSeeder::class);

        foreach (['/cart', '/checkout', '/order', '/keranjang'] as $url) {
            $this->get($url)->assertNotFound();
        }
        $this->get('/')->assertDontSee('Add to Cart', false)->assertDontSee('Order Now', false);
    }
}
