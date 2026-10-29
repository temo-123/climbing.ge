<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="ga-tag-id" content="{{ config('services.analytics.error') }}">
    <meta name="theme-color" content="#15212b">

    <title>@yield('title', 'Error') — climbing.ge</title>

    <link href="{{ asset('images/site_img/x.png') }}" rel="shortcut icon">
    @include('partials.assets')
</head>
<body data-service="guide" class="cg-error-page">

    <header class="cg-nav">
        <div class="cg-nav__inner">
            <a href="{{ config('app.url') }}" class="cg-brand">
                <span class="cg-brand__mark">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 20 9 8l3.2 5.4L15 9l7 11H2Z" fill="#fff"/><path d="M9 8l-2.1 3.6 1.1-.5 1 .9 1-.9 1.1.5Z" fill="rgba(21,33,43,.35)"/><path d="M15 9V3.6l3.4 1.3L15 6.2" fill="#ffc233"/></svg>
                </span>
                <span class="cg-brand__text">climbing.ge<small>Georgia climbing platform</small></span>
            </a>
        </div>
    </header>

    <main class="cg-error-main">
        @yield('content')
    </main>

    <div class="footer__graphic" style="background-image: url('{{ asset('images/svg/mountains.svg') }}')"></div>
    <footer class="footer">
        <div class="container text-center">&copy; {{ date('Y') }} climbing.ge</div>
    </footer>

</body>
</html>
