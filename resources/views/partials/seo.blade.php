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

    {{-- JSON-LD Structured Data --}}
    @if($seoSchema)
    <script type="application/ld+json">
    {!! json_encode($seoSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @endif
