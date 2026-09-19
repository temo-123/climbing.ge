    {{-- SEO meta tags — injected server-side before Vue loads --}}
    @php
        $seo = $seo ?? [];
        $seoTitle       = $seo['title'] ?? config('app.name', 'climbing.ge');
        $seoDescription = $seo['description'] ?? '';
        $seoImage       = $seo['image'] ?? asset('images/meta_img/default.jpg');
        $seoType        = $seo['type'] ?? 'website';
        $seoUrl         = $seo['url'] ?? request()->url();
        $seoSchema      = $seo['schema'] ?? null;
        $seoKeywords    = $seo['keywords'] ?? '';
        $seoPrice       = $seo['price'] ?? null;
        $seoLocale      = app()->getLocale() === 'ka' ? 'ka_GE' : 'en_US';
        $seoSubdomain   = $subdomain ?? 'site';
        $googleVerify   = config("services.seo.google_site_verification.{$seoSubdomain}");
        $bingVerify     = config("services.seo.bing_site_verification.{$seoSubdomain}");
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    @if($seoKeywords)
    <meta name="keywords" content="{{ $seoKeywords }}">
    @endif
    <meta name="robots" content="index, follow">

    {{-- Search engine ownership verification --}}
    @if($googleVerify)
    <meta name="google-site-verification" content="{{ $googleVerify }}">
    @endif
    @if($bingVerify)
    <meta name="msvalidate.01" content="{{ $bingVerify }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:image:secure_url" content="{{ $seoImage }}">
    <meta property="og:image:alt" content="{{ $seoTitle }}">
    <meta property="og:url" content="{{ $seoUrl }}">
    <meta property="og:type" content="{{ $seoType }}">
    <meta property="og:locale" content="{{ $seoLocale }}">
    <meta property="og:locale:alternate" content="{{ $seoLocale === 'ka_GE' ? 'en_US' : 'ka_GE' }}">
    <meta property="og:site_name" content="climbing.ge">

    {{-- Product price (Facebook/WhatsApp/Telegram product previews + Google) --}}
    @if($seoPrice)
    <meta property="product:price:amount" content="{{ $seoPrice['amount'] }}">
    <meta property="product:price:currency" content="{{ $seoPrice['currency'] }}">
    <meta property="product:availability" content="{{ $seoPrice['inStock'] ? 'in stock' : 'out of stock' }}">
    @endif

    {{-- Twitter / X --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">
    <meta name="twitter:image:alt" content="{{ $seoTitle }}">
    @if($seoPrice)
    <meta name="twitter:label1" content="Price">
    <meta name="twitter:data1" content="{{ $seoPrice['amount'] }} {{ $seoPrice['currency'] }}">
    @if(!$seoPrice['inStock'])
    <meta name="twitter:label2" content="Availability">
    <meta name="twitter:data2" content="Out of stock">
    @endif
    @endif

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

    {{--
        Sitewide entity identity — on EVERY page, independent of whatever
        content-specific schema exists above. Search/AI engines otherwise
        have no structured signal at all for the homepage or any listing/
        default page (SeoService's defaultsFor() passes schema: null there),
        and no way to recognize "climbing.ge" as the same Organization across
        5 separate subdomains without a shared @id to correlate on.
        SeoService::publisherSchema() (embedded as publisher/brand/author on
        article/product/service pages) uses this exact same @id, so all of
        it resolves to one entity instead of duplicate anonymous Organizations.
    --}}
    @php
        $orgId = 'https://climbing.ge/#organization';
        $siteInfo = \App\Models\Site::first();
        $orgSchema = [
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            '@id'      => $orgId,
            'name'     => 'Climbing.ge',
            'url'      => 'https://climbing.ge',
            'logo'     => asset('images/site_img/site_logo/climbing.ge.png'),
            'sameAs'   => \App\Models\Site_social_link::pluck('url')->values()->all(),
        ];
        if ($siteInfo?->email)  $orgSchema['email']     = $siteInfo->email;
        if ($siteInfo?->number) $orgSchema['telephone'] = $siteInfo->number;

        $subdomainNames = [
            'site'   => 'Climbing.ge Guidebook',
            'shop'   => 'Climbing.ge Shop',
            'blog'   => 'Climbing.ge Blog',
            'summit' => 'Climbing.ge Summit Log',
            'films'  => 'Climbing.ge Films',
        ];
        $websiteSchema = [
            '@context'   => 'https://schema.org',
            '@type'      => 'WebSite',
            'name'       => $subdomainNames[$seoSubdomain] ?? 'Climbing.ge',
            'url'        => $seoBase ?? $seoUrl,
            'inLanguage' => [$seoLocale === 'ka_GE' ? 'ka' : 'en', $seoLocale === 'ka_GE' ? 'en' : 'ka'],
            'publisher'  => ['@id' => $orgId],
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($orgSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($websiteSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
