    {{-- SEO meta tags — injected server-side before Vue loads --}}
    @php
        $seo = $seo ?? [];
        $seoTitle       = $seo['title'] ?? config('app.name', 'climbing.ge');
        $seoDescription = $seo['description'] ?? '';
        $seoImage       = $seo['image'] ?? asset('images/meta_img/default.jpg');
        $seoType        = $seo['type'] ?? 'website';
        $seoUrl         = $seo['url'] ?? request()->url();
        $seoSchema      = $seo['schema'] ?? null;
        $seoLocale      = app()->getLocale() === 'ka' ? 'ka_GE' : 'en_US';
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:url" content="{{ $seoUrl }}">
    <meta property="og:type" content="{{ $seoType }}">
    <meta property="og:locale" content="{{ $seoLocale }}">
    <meta property="og:locale:alternate" content="{{ $seoLocale === 'ka_GE' ? 'en_US' : 'ka_GE' }}">
    <meta property="og:site_name" content="climbing.ge">

    {{-- Twitter / X --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">

    {{-- Canonical --}}
    <link rel="canonical" href="{{ $seoUrl }}">

    {{-- Hreflang (bilingual: English / Georgian) --}}
    @php
        $parsedSeoUrl = parse_url($seoUrl);
        $seoPath      = $parsedSeoUrl['path'] ?? '/';
        $seoBase      = ($parsedSeoUrl['scheme'] ?? 'https') . '://' . ($parsedSeoUrl['host'] ?? '');
        if (preg_match('#^/ka(/|$)#', $seoPath)) {
            $hreflangKa = $seoUrl;
            $hreflangEn = $seoBase . (preg_replace('#^/ka#', '', $seoPath) ?: '/');
        } else {
            $hreflangEn = $seoUrl;
            $hreflangKa = $seoBase . '/ka' . $seoPath;
        }
    @endphp
    <link rel="alternate" hreflang="en" href="{{ $hreflangEn }}">
    <link rel="alternate" hreflang="ka" href="{{ $hreflangKa }}">
    <link rel="alternate" hreflang="x-default" href="{{ $hreflangEn }}">

    {{-- JSON-LD Structured Data --}}
    @if($seoSchema)
    <script type="application/ld+json">
    {!! json_encode($seoSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @endif
