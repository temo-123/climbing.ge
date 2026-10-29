<?php

namespace App\Services\Seo;

/**
 * Plain-HTML version of a page for crawlers that don't run JavaScript
 * (Bing, social previews, AI crawlers). SeoService fills it per page type and
 * partials/seo_body.blade.php prints it inside #app — Vue replaces it on mount,
 * and a <head> script hides it for JS users so there's no flash. It must only
 * ever contain what the Vue page itself shows (same title, text, links).
 */
class SeoPageBody
{
    /** Tags kept from admin (Quill) HTML; every attribute except a safe href is dropped. */
    private const ALLOWED_TAGS = ['p', 'br', 'h2', 'h3', 'h4', 'ul', 'ol', 'li', 'strong', 'b', 'em', 'i', 'a', 'blockquote', 'table', 'thead', 'tbody', 'tr', 'th', 'td'];

    /**
     * @param array $sections [heading|null => html]; numeric keys = no heading
     */
    public static function html(array $sections): string
    {
        $out = '';
        foreach ($sections as $heading => $html) {
            $clean = self::clean((string) $html);
            if ($clean === '') continue;
            if (is_string($heading) && $heading !== '') {
                $out .= '<h2>' . e($heading) . '</h2>';
            }
            $out .= $clean;
        }
        return $out;
    }

    private static function clean(string $html): string
    {
        if (trim(strip_tags($html)) === '' || trim(strip_tags($html)) === '.') return '';

        $html = strip_tags($html, self::ALLOWED_TAGS);

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8"?><div id="cg-root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $root = $doc->getElementById('cg-root');
        if (! $root) return '';

        foreach ((new \DOMXPath($doc))->query('//*[@*]', $root) as $el) {
            $href = $el->nodeName === 'a' ? $el->getAttribute('href') : '';
            while ($el->attributes->length) {
                $el->removeAttribute($el->attributes->item(0)->nodeName);
            }
            if ($href !== '' && preg_match('#^(https?://|/)#i', $href)) {
                $el->setAttribute('href', $href);
            }
        }
        $root->removeAttribute('id');

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $doc->saveHTML($child);
        }
        return trim($out);
    }
}
