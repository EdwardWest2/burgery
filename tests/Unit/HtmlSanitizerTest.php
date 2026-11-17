<?php

namespace Tests\Unit;

use App\Support\HtmlSanitizer;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function test_removes_script_tags_and_event_handlers(): void
    {
        $out = HtmlSanitizer::clean('<p onclick="x()">Halo</p><script>alert(1)</script>');

        $this->assertStringNotContainsString('script', $out);
        $this->assertStringNotContainsString('onclick', $out);
        $this->assertStringContainsString('<p>Halo</p>', $out);
    }

    public function test_removes_javascript_urls(): void
    {
        $out = HtmlSanitizer::clean('<a href="javascript:alert(1)">klik</a><img src="data:text/html;base64,AAA">');

        $this->assertStringNotContainsString('javascript:', $out);
        $this->assertStringNotContainsString('data:', $out);
    }

    public function test_keeps_allowed_formatting_and_safe_links(): void
    {
        $out = HtmlSanitizer::clean('<h2>Judul</h2><p><strong>Tebal</strong> <em>miring</em> <a href="https://contoh.com">tautan</a></p><ul><li>a</li></ul>');

        $this->assertStringContainsString('<h2>Judul</h2>', $out);
        $this->assertStringContainsString('<strong>Tebal</strong>', $out);
        $this->assertStringContainsString('href="https://contoh.com"', $out);
        $this->assertStringContainsString('<li>a</li>', $out);
    }

    public function test_drops_iframes_and_unwraps_unknown_tags(): void
    {
        $out = HtmlSanitizer::clean('<iframe src="https://evil.test"></iframe><span style="color:red">teks</span>');

        $this->assertStringNotContainsString('iframe', $out);
        $this->assertStringNotContainsString('span', $out);
        $this->assertStringContainsString('teks', $out);
    }
}
