<?php

namespace App\Services\Seo;

class KeywordGeneratorService
{
    private const STOPWORDS_EN = [
        'this', 'that', 'these', 'those', 'with', 'from', 'your', 'have', 'has',
        'had', 'were', 'will', 'would', 'could', 'should', 'about', 'into', 'over',
        'under', 'after', 'before', 'between', 'they', 'them', 'their', 'there',
        'here', 'than', 'then', 'when', 'where', 'what', 'which', 'while', 'because',
        'also', 'just', 'only', 'very', 'more', 'most', 'some', 'such', 'each',
        'other', 'being', 'been', 'does', 'doing', 'each', 'into', 'such', 'both',
        'through', 'during', 'above', 'below', 'again', 'further', 'once', 'same',
        'climbing', 'georgia', 'georgian',
    ];

    private const STOPWORDS_KA = [
        'არის', 'იყო', 'იქნება', 'ეს', 'ის', 'რომ', 'და', 'მაგრამ', 'ან', 'რადგან',
        'როცა', 'სადაც', 'როგორც', 'თუმცა', 'მათ', 'მისი', 'მისთვის', 'ჩვენ',
        'თქვენ', 'მათი', 'აქ', 'იქ', 'როდის', 'რატომ', 'როგორ', 'ძალიან', 'მხოლოდ',
        'ერთი', 'ერთ', 'ყველაზე', 'ასევე', 'შეგიძლიათ', 'შეიძლება', 'უნდა',
        'რამდენიმე', 'სხვადასხვა', 'სხვა', 'ისეთი', 'ამის', 'ამიტომ', 'კიდევ',
        'უკვე', 'მაინც', 'საკმაოდ', 'ალბათ', 'სავარაუდოდ',
    ];

    /**
     * Cross a list of topics with a list of locations to get phrases like
     * "rock climbing Georgia" / "rock climbing in Georgia" (or the Georgian
     * equivalents), plus the bare topics. Locations should already be in
     * whatever inflected forms make sense for the locale — see
     * locationVariants(). Used to push country/region/city names into the
     * keyword list.
     */
    public function withLocations(array $topics, array $locations): array
    {
        $topics    = array_values(array_unique(array_filter(array_map('trim', $topics))));
        $locations = array_values(array_unique(array_filter(array_map('trim', $locations))));

        if (empty($locations)) {
            return $topics;
        }

        $phrases = $topics;
        foreach ($topics as $topic) {
            foreach ($locations as $location) {
                $phrases[] = "{$topic} {$location}";
            }
        }

        return $phrases;
    }

    /**
     * Bare + "in <location>" forms for a place name, locale-aware. English
     * just prepends "in"; Georgian needs the locative case (-ში), which for
     * most place names is formed by dropping a trailing "ი" before adding it.
     */
    public function locationVariants(string $location, string $locale = 'us'): array
    {
        $location = trim($location);
        if ($location === '') {
            return [];
        }

        if ($locale === 'ka') {
            return [$location, $this->kaLocative($location)];
        }

        return [$location, "in {$location}"];
    }

    private function kaLocative(string $word): string
    {
        if (mb_substr($word, -1) === 'ი') {
            return mb_substr($word, 0, -1) . 'ში';
        }

        return $word . 'ში';
    }

    /**
     * Derive a comma-separated keyword list from free text plus
     * boost phrases (title, category label, etc.) already on hand.
     * No persistence — caller decides whether/how to cache the result.
     */
    public function generate(string $text, array $phrases = [], string $locale = 'us', int $limit = 10): string
    {
        $phrases = array_values(array_unique(array_filter(array_map('trim', $phrases))));

        $stopwords = $locale === 'ka' ? self::STOPWORDS_KA : self::STOPWORDS_EN;
        $usedWords = [];
        foreach ($phrases as $phrase) {
            foreach (preg_split('/\s+/u', mb_strtolower($phrase)) as $word) {
                $usedWords[$word] = true;
            }
        }

        $plain = mb_strtolower(strip_tags($text));
        $plain = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $plain);
        $words = preg_split('/\s+/u', trim($plain), -1, PREG_SPLIT_NO_EMPTY);

        $frequency = [];
        foreach ($words as $word) {
            if (mb_strlen($word) < 4 || isset($usedWords[$word]) || in_array($word, $stopwords, true)) {
                continue;
            }
            $frequency[$word] = ($frequency[$word] ?? 0) + 1;
        }
        arsort($frequency);

        $remaining = max(0, $limit - count($phrases));
        $topWords  = array_slice(array_keys($frequency), 0, $remaining);

        return implode(', ', array_merge($phrases, $topWords));
    }
}
