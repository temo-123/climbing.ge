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
        $googleVerify   = config('services.seo.google_site_verification');
        $bingVerify     = config('services.seo.bing_site_verification');
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
