<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="ga-tag-id" content="{{ config('services.analytics.blog') }}">
    <meta name="theme-color" content="#15212b">
    <link href="{{ asset('images/site_img/x.png') }}" rel="shortcut icon">
    @include('partials.seo', ['subdomain' => 'blog'])
    @include('partials.adsense')
    @include('partials.assets')
    @include('partials.coefficients')
</head>
<body data-service="blog">
    <div id="app">
        @include('partials.seo_body')
        @yield('content')
    </div>

    <script src="{{ mix('assets/js/app.js') }}"></script>
</body>
</html>
