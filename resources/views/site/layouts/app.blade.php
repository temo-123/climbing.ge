<!DOCTYPE html>
<html>
<head>
    {{-- <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - climbing.ge</title>
    <link href="{{ asset('images/site_img/site_logo/x.png') }}" rel="shortcut icon">

    <meta name="description" content="@yield('meta_description')" />

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="@yield('title')">
    <meta itemprop="description" content="@yield('meta_description')">
    <meta itemprop="image" content="@yield('meta_img')">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="@yield('title')">
    <meta name="twitter:description" content="@yield('meta_description')">
    <meta name="twitter:creator" content="@author_handle">
    <!-- Twitter summary card with large image must be at least 280x150px -->
    <meta name="twitter:image:src" content="@yield('meta_img')">

    <!-- Open Graph data -->
    <meta property="og:title" content="@yield('title')" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://{{ config('app.url') }}" />
    <meta property="og:image" content="@yield('meta_img')" />
    <meta property="og:description" content="@yield('meta_description')" />
    <meta property="og:site_name" content="{{ config('app.url') }}" />
    
    <meta property="article:published_time" content="@yield('created_at')" />
    <meta property="article:modified_time" content="@yield('updated_at')" />
    <meta property="article:author" content="Temo Samsonadze" />
    <meta property="article:section" content="Climbing" />
    <meta property="article:tag" content="@yield('meta_keyword')" />
    <meta property="fb:admins" content="Facebook numberic ID" />

    <meta property="og:locale" content="en_GB" />
    <meta property="og:locale:alternate" content="ka_KA" />
    <meta property="og:locale:alternate" content="ru_RU" /> --}}
    {{-- <meta name='locale' content='{{app()->getLocale()}}' /> --}}

    <!-- Rendered HTML tags in your page -->
    {{-- <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> --}}

    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{-- And analytics.google.com --}}
    {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-YSKGLXMWGF"></script>
    <script src="{{ asset('assets/js/analitic/climbing.ge_analitic.js') }}"></script> --}}

    {{-- Google recaptcha --}}
    {{-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}

    <style> 
        /* Text copy block*/
        .unselectable { 
            -webkit-user-select: none; 
            -webkit-touch-callout: none; 
            -moz-user-select: none; 
            -ms-user-select: none; 
            user-select: none;
        }
        /* Firefox scrollbar color*/
        html {
            scrollbar-color: #279fbb white;
            scrollbar-width: thin;
            border-radius: 0%
        }
        /* Other brouser scrollbar color*/
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
            background-color: white;
            border-radius: 0%
        }
        ::-webkit-scrollbar-thumb {
            background: #279fbb;
            border-radius: 5px;
            border-radius: 0%
        }
    </style>
</head>
<body>
    {{-- {{ dd(App::currentLocale()) }} --}}
    <div id="page-preloader" class="preloader">
        <div class="loader"></div>
    </div>

    {{-- <div id="app" oncontextmenu="return false;" class="unselectable"> --}}
    <div id="app">
        {{-- @include('site.components.navbar') --}}

        @yield('content')
        
        {{-- @include('site.components.footer') --}}
    </div>

    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script> <!-- conflict to site menu -->
    <script src="{{ asset('assets/js/my_js/my_scripts.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('assets/js/app.js') }}"></script> 

    {{-- <script data-ad-client="ca-pub-6892953928713179" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> --}}
</body>
</html>
