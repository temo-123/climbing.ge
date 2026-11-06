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
        // Private areas (user.climbing.ge) pass 'noindex, nofollow' — they also skip the
        // sitewide Organization/WebSite schema below, which only makes sense on public pages.
        $seoRobots      = $seo['robots'] ?? 'index, follow';
        $seoNoindex     = str_contains($seoRobots, 'noindex');
        $seoLocale      = app()->getLocale() === 'ka' ? 'ka_GE' : 'en_US';
        $seoSubdomain   = $subdomain ?? 'site';
        $googleVerify   = config("services.seo.google_site_verification.{$seoSubdomain}");
        $bingVerify     = config("services.seo.bing_site_verification.{$seoSubdomain}");
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}" data-seo="server">
    @if($seoKeywords)
    <meta name="keywords" content="{{ $seoKeywords }}">
    @endif
    <meta name="robots" content="{{ $seoRobots }}">

    {{-- Search engine ownership verification --}}
    @if($googleVerify)
    <meta name="google-site-verification" content="{{ $googleVerify }}">
    @endif
    @if($bingVerify)
    <meta name="msvalidate.01" content="{{ $bingVerify }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $seoTitle }}" data-seo="server">
    <meta property="og:description" content="{{ $seoDescription }}" data-seo="server">
    <meta property="og:image" content="{{ $seoImage }}" data-seo="server">
    <meta property="og:image:secure_url" content="{{ $seoImage }}">
    <meta property="og:image:alt" content="{{ $seoTitle }}">
    <meta property="og:url" content="{{ $seoUrl }}" data-seo="server">
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
    <meta name="twitter:title" content="{{ $seoTitle }}" data-seo="server">
    <meta name="twitter:description" content="{{ $seoDescription }}" data-seo="server">
    <meta name="twitter:image" content="{{ $seoImage }}" data-seo="server">
    <meta name="twitter:image:alt" content="{{ $seoTitle }}">
    @if($seoPrice)
    <meta name="twitter:label1" content="Price">
    <meta name="twitter:data1" content="{{ $seoPrice['amount'] }} {{ $seoPrice['currency'] }}">
    @if(!$seoPrice['inStock'])
    <meta name="twitter:label2" content="Availability">
    <meta name="twitter:data2" content="Out of stock">
    @endif
    @endif

    {{-- Canonical + hreflang only on indexable pages: noindex (404s, "public by URL only",
         user.climbing.ge) with a canonical is a mixed signal --}}
    @if(!$seoNoindex)
    <link rel="canonical" href="{{ $seoUrl }}" data-seo="server">

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
            // home "/" → "/ka" (no trailing slash: that is the Georgian home's canonical)
            $hreflangKa = $seoBase . '/ka' . ($seoPath === '/' ? '' : $seoPath);
        }
    @endphp
    <link rel="alternate" hreflang="en" href="{{ $hreflangEn }}" data-seo="server">
    <link rel="alternate" hreflang="ka" href="{{ $hreflangKa }}" data-seo="server">
    <link rel="alternate" hreflang="x-default" href="{{ $hreflangEn }}" data-seo="server">
    @endif

    {{-- JSON-LD Structured Data --}}
    @if($seoSchema && !$seoNoindex)
    <script type="application/ld+json">
    {!! json_encode($seoSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @endif

    @if(!$seoNoindex)
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

        // Site name Google shows above the result: the plain brand for the guidebook, brand +
        // service for the subdomains; alternateName = other names people know it by.
        $subdomainNames = [
            'site'   => ['Climbing.ge', ['climbing.ge', 'Climbing.ge Guidebook', 'Georgian Climbing Guidebook']],
            'shop'   => ['Climbing.ge Shop', ['shop.climbing.ge', 'Climbing.ge Store']],
            'blog'   => ['Climbing.ge Blog', ['blog.climbing.ge']],
            'summit' => ['Climbing.ge Summits', ['summit.climbing.ge', 'Climbing.ge Summit Log']],
            'films'  => ['Climbing.ge Films', ['films.climbing.ge']],
        ];
        [$siteName, $siteAltNames] = $subdomainNames[$seoSubdomain] ?? ['Climbing.ge', []];
        $websiteSchema = [
            '@context'      => 'https://schema.org',
            '@type'         => 'WebSite',
            'name'          => $siteName,
            'alternateName' => $siteAltNames,
            // exactly the home page's canonical (with the slash) — Google matches the two
            'url'           => request()->getSchemeAndHttpHost() . '/',
            'inLanguage'    => [$seoLocale === 'ka_GE' ? 'ka' : 'en', $seoLocale === 'ka_GE' ? 'en' : 'ka'],
            'publisher'     => ['@id' => $orgId],
        ];
        // sitelinks search box — each subdomain's own search page (?query=)
        $searchPaths = ['site' => 'search_articles', 'shop' => 'search_products', 'blog' => 'search', 'films' => 'search_films'];
        if (isset($searchPaths[$seoSubdomain])) {
            $websiteSchema['potentialAction'] = [
                '@type'       => 'SearchAction',
                'target'      => [
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => request()->getSchemeAndHttpHost() . '/' . $searchPaths[$seoSubdomain] . '?query={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ];
        }
    @endphp
    <script type="application/ld+json">
    {!! json_encode($orgSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($websiteSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    @if(!empty($seo['breadcrumbs']['schema']))
    <script type="application/ld+json">
    {!! json_encode($seo['breadcrumbs']['schema'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    @endif
    @endif

    {{-- crawler HTML (partials/seo_body) is hidden as soon as JS runs; Vue replaces it on mount --}}
    <script>document.documentElement.classList.add('js')</script>
    <style>.js .cg-ssr{display:none}</style>
