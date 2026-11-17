<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleCmsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private ArticleCategory $category;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('uploads');
        $this->admin = User::create(['name' => 'Admin', 'username' => 'admin', 'email' => 'a@x.test', 'password' => 'password123']);
        $this->category = ArticleCategory::create(['name' => 'Tips', 'slug' => 'tips']);
    }

    private function payload(array $override = []): array
    {
        return array_merge([
            'title' => 'Artikel Percobaan',
            'article_category_id' => $this->category->id,
            'body' => '<p>Isi artikel</p>',
            'status' => 'draft',
        ], $override);
    }

    public function test_draft_is_hidden_from_public_and_published_is_visible(): void
    {
        $this->actingAs($this->admin)->post('/admin/articles', $this->payload())->assertRedirect();
        $article = Article::firstOrFail();

        $this->assertSame('artikel-percobaan', $article->slug);
        $this->get('/artikel/artikel-percobaan')->assertNotFound();
        $this->get('/artikel')->assertDontSee('Artikel Percobaan');

        $this->actingAs($this->admin)->put("/admin/articles/{$article->id}", $this->payload(['status' => 'published']))->assertRedirect();

        $this->get('/artikel/artikel-percobaan')->assertOk()->assertSee('Isi artikel', false);
        $this->get('/artikel')->assertSee('Artikel Percobaan');
    }

    public function test_future_publish_date_is_scheduled_and_hidden(): void
    {
        $this->actingAs($this->admin)->post('/admin/articles', $this->payload([
            'status' => 'published',
            'published_at' => now()->addDays(3)->format('Y-m-d\TH:i'),
        ]));

        $this->get('/artikel/artikel-percobaan')->assertNotFound();
        $this->assertTrue(Article::first()->isScheduled());
    }

    public function test_preview_works_for_admin_on_draft_only(): void
    {
        $this->actingAs($this->admin)->post('/admin/articles', $this->payload());
        $article = Article::firstOrFail();

        $this->actingAs($this->admin)->get("/admin/articles/{$article->id}/preview")->assertOk()->assertSee('Mode pratinjau');
        auth()->logout();
        $this->get("/admin/articles/{$article->id}/preview")->assertRedirect('/admin/login');
    }

    public function test_body_is_sanitized_on_save(): void
    {
        $this->actingAs($this->admin)->post('/admin/articles', $this->payload([
            'body' => '<p>Aman</p><script>alert(1)</script><a href="javascript:evil()">x</a>',
            'status' => 'published',
        ]));

        $body = Article::firstOrFail()->body;
        $this->assertStringNotContainsString('<script', $body);
        $this->assertStringNotContainsString('javascript:', $body);
    }

    public function test_slug_is_unique(): void
    {
        $this->actingAs($this->admin)->post('/admin/articles', $this->payload());
        $this->actingAs($this->admin)->post('/admin/articles', $this->payload());

        $this->assertEqualsCanonicalizing(['artikel-percobaan', 'artikel-percobaan-2'], Article::pluck('slug')->all());
    }

    public function test_image_upload_validation_and_delete_cleans_file(): void
    {
        $this->actingAs($this->admin)->post('/admin/articles', $this->payload([
            'featured_image' => UploadedFile::fake()->create('shell.php', 10, 'application/x-php'),
        ]))->assertSessionHasErrors('featured_image');

        $this->actingAs($this->admin)->post('/admin/articles', $this->payload([
            'featured_image' => UploadedFile::fake()->image('foto.jpg', 600, 400),
        ]))->assertSessionHasNoErrors();

        $article = Article::firstOrFail();
        Storage::disk('uploads')->assertExists($article->featured_image);

        $this->actingAs($this->admin)->delete("/admin/articles/{$article->id}")->assertRedirect('/admin/articles');
        Storage::disk('uploads')->assertMissing($article->featured_image);
        $this->assertDatabaseCount('articles', 0);
    }

    public function test_category_with_articles_cannot_be_deleted(): void
    {
        $this->actingAs($this->admin)->post('/admin/articles', $this->payload());

        $this->actingAs($this->admin)->delete("/admin/article-categories/{$this->category->id}")->assertSessionHas('error');
        $this->assertDatabaseHas('article_categories', ['id' => $this->category->id]);
    }

    public function test_article_search_in_admin(): void
    {
        $this->actingAs($this->admin)->post('/admin/articles', $this->payload(['title' => 'Burger Spesial']));
        $this->actingAs($this->admin)->post('/admin/articles', $this->payload(['title' => 'Kentang Goreng']));

        $this->actingAs($this->admin)->get('/admin/articles?q=Burger')->assertSee('Burger Spesial')->assertDontSee('Kentang Goreng');
    }
}
