<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="ga-tag-id" content="{{ config('services.analytics.user') }}">
    <meta name="theme-color" content="#15212b">
    <link href="{{ asset('images/site_img/x.png') }}" rel="shortcut icon">
    {{-- Per-page title/description/OG tags (SeoService::forUser). Private dashboard — always
         noindex, including the error pages that render this layout without $seo. --}}
    @include('partials.seo', ['subdomain' => 'user', 'seo' => array_merge($seo ?? [], ['robots' => 'noindex, nofollow'])])
    @include('partials.assets')
    @include('partials.coefficients')
</head>
<body data-service="user">
    <div id="app">
        @yield('content')
    </div>

    <script src="{{ mix('assets/js/app.js') }}"></script>
</body>
</html>
