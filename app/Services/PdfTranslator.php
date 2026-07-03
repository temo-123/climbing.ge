<?php

namespace App\Services;

/**
 * Loads the same `shop.wall.*` strings (both the general ones and the
 * `pdf`-specific subset) the frontend's vue-i18n JSON files define
 * (resources/lang/i18n/{locale}.json), so the wall calculator's PDF export
 * can render in the user's own active locale (English or Georgian) instead
 * of always English — reusing one source of translated strings rather than
 * maintaining a second, Laravel-native copy that could drift out of sync
 * with the site's own translations. A key present in both wins from the
 * `pdf` subset, since a few (e.g. `side_label`) intentionally read
 * differently in each context.
 */
class PdfTranslator
{
    protected array $strings;

    public function __construct(string $locale = 'en')
    {
        $locale = in_array($locale, ['en', 'ka'], true) ? $locale : 'en';
        $this->strings = $this->loadStrings($locale);

        // English fills in anything the requested locale's own file is
        // missing, so a partially-translated locale still gets a usable PDF
        // instead of a raw untranslated key showing through.
        if ($locale !== 'en') {
            $this->strings = array_merge($this->loadStrings('en'), $this->strings);
        }
    }

    protected function loadStrings(string $locale): array
    {
        $path = resource_path("lang/i18n/{$locale}.json");
        if (!is_file($path)) return [];
        $data = json_decode(file_get_contents($path), true);
        $wall = $data['shop']['wall'] ?? [];
        $pdf = $wall['pdf'] ?? [];
        unset($wall['pdf']);

        // The general shop.wall.* strings (e.g. discipline_name_bouldering,
        // structure_name_outdoor) are reused here too, not just the
        // pdf-specific subset — one translated string per concept instead of
        // duplicating it under both places. Where a key exists in BOTH (e.g.
        // "side_label": "Side {n}" generally vs "Side" in the pdf block,
        // deliberately different for their own contexts), the pdf-specific
        // value wins.
        return array_merge($wall, $pdf);
    }

    /**
     * @param string $key Key directly under shop.wall.pdf (no dot-path — this
     *   PDF only ever reads from that one nested object).
     * @param array $replace :placeholder => value substitutions.
     */
    public function t(string $key, array $replace = []): string
    {
        $value = $this->strings[$key] ?? $key;
        foreach ($replace as $find => $val) {
            $value = str_replace(':' . $find, $val, $value);
        }
        return $value;
    }

    /**
     * The small subset of translated strings WallDrawingService's own
     * label() calls need — kept separate from the blade view's direct t()
     * calls since the drawing service has no view context of its own.
     */
    public function drawingLabels(): array
    {
        return [
            'support_truss' => $this->t('support_truss'),
            'support_prop' => $this->t('support_prop'),
            'foundation' => $this->t('foundation_label'),
            'roof' => $this->t('roof_label'),
            'overhang' => $this->t('overhang_label'),
            'overhang_direction' => $this->t('overhang_direction'),
            'side' => $this->t('side_label'),
        ];
    }
}
