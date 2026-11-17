<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;

/**
 * Pembersih HTML berbasis allowlist untuk isi dari editor rich-text.
 * Semua tag/atribut yang tidak diizinkan dibuang; URL berbahaya
 * (javascript:, data:, dll.) dihapus.
 */
class HtmlSanitizer
{
    private const ALLOWED = [
        'p', 'br', 'h2', 'h3', 'h4', 'strong', 'b', 'em', 'i', 'u', 's',
        'ul', 'ol', 'li', 'a', 'blockquote', 'img', 'hr', 'figure',
        'figcaption', 'code', 'pre',
    ];

    /** Tag berbahaya: dibuang beserta isinya. */
    private const DROP = [
        'script', 'style', 'iframe', 'object', 'embed', 'form', 'input',
        'button', 'textarea', 'select', 'option', 'svg', 'math', 'link',
        'meta', 'noscript', 'template', 'base', 'applet', 'frame',
        'frameset', 'audio', 'video', 'canvas', 'head', 'title',
    ];

    /** Tag yang diubah menjadi tag lain yang diizinkan. */
    private const RENAME = ['h1' => 'h2', 'h5' => 'h4', 'h6' => 'h4', 'div' => 'p'];

    private const ATTRIBUTES = [
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'width', 'height'],
    ];

    public static function clean(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="UTF-8"><div id="sanitize-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $dom->getElementsByTagName('div')->item(0);
        if (! $root) {
            return htmlspecialchars(strip_tags($html), ENT_QUOTES, 'UTF-8');
        }

        self::sanitizeChildren($root);

        $output = '';
        foreach ($root->childNodes as $child) {
            $output .= $dom->saveHTML($child);
        }

        return trim($output);
    }

    private static function sanitizeChildren(DOMNode $node): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof DOMText) {
                continue;
            }

            if (! $child instanceof DOMElement) {
                // komentar, processing instruction, dll.
                $node->removeChild($child);

                continue;
            }

            $tag = strtolower($child->tagName);

            if (in_array($tag, self::DROP, true)) {
                $node->removeChild($child);

                continue;
            }

            self::sanitizeChildren($child);

            if (isset(self::RENAME[$tag])) {
                $renamed = $node->ownerDocument->createElement(self::RENAME[$tag]);
                while ($child->firstChild) {
                    $renamed->appendChild($child->firstChild);
                }
                $node->replaceChild($renamed, $child);
                $child = $renamed;
                $tag = self::RENAME[$tag];
            }

            if (! in_array($tag, self::ALLOWED, true)) {
                // Tag tidak dikenal: buang tag-nya, pertahankan isinya.
                while ($child->firstChild) {
                    $node->insertBefore($child->firstChild, $child);
                }
                $node->removeChild($child);

                continue;
            }

            self::sanitizeAttributes($child, $tag);

            if ($tag === 'img' && ! $child->hasAttribute('src')) {
                $node->removeChild($child);
            }
        }
    }

    private static function sanitizeAttributes(DOMElement $element, string $tag): void
    {
        $allowed = self::ATTRIBUTES[$tag] ?? [];

        foreach (iterator_to_array($element->attributes) as $attribute) {
            $name = strtolower($attribute->name);

            if (! in_array($name, $allowed, true)) {
                $element->removeAttribute($attribute->name);

                continue;
            }

            if (($name === 'href' || $name === 'src') && ! self::isSafeUrl($attribute->value, $name === 'src')) {
                $element->removeAttribute($attribute->name);
            }
        }

        if ($tag === 'a') {
            if (strtolower($element->getAttribute('target')) === '_blank') {
                $element->setAttribute('rel', 'noopener noreferrer');
            } else {
                $element->removeAttribute('target');
                $element->removeAttribute('rel');
            }
        }
    }

    private static function isSafeUrl(string $url, bool $isImage): bool
    {
        $clean = preg_replace('/[\x00-\x20\x7F]+/', '', $url) ?? '';

        if ($clean === '') {
            return false;
        }

        if (preg_match('/^([a-z][a-z0-9+.\-]*):/i', $clean, $match)) {
            $allowed = $isImage ? ['http', 'https'] : ['http', 'https', 'mailto', 'tel'];

            return in_array(strtolower($match[1]), $allowed, true);
        }

        // URL relatif (/path, #anchor, path/file.jpg, //host/...) dianggap aman.
        return true;
    }
}
