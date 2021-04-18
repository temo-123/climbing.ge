<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
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
    {{-- <meta property="fb:admins" content="Facebook numberic ID" /> --}}

    <meta property="og:locale" content="en_GB" />
    <meta property="og:locale:alternate" content="ka_KA" />
    <meta property="og:locale:alternate" content="ru_RU" />




    {{-- animate style --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css"> --}}

    <!-- font femyly in style.css (line 6.12.17.23.30) || style1.css (line 6) -->
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    {{-- <!-- <link rel="stylesheet" href="{{ asset('assets/css/my_css/style.css') }}" > --> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

    <!-- fonts -->
    <link rel="stylesheet" href="{{ asset('assets/css/animate-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">

    <!--For article slider-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <!--For gallry -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"> --}}

    <!--For about us-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    {{-- <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/css/bootstrapValidator.min.css"/> --}}
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/js/bootstrapValidator.min.js"></script> 

    {{-- And analytics.google.com --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-YSKGLXMWGF"></script>
    <script src="{{ asset('assets/js/analitic/climbing.ge_analitic.js') }}"></script>

</head>
<body>

    <div id="page-preloader" class="preloader">
        <div class="loader"></div>
    </div>

    <div id="app">
        @include('site.components.navbar')

        @yield('content')
        
        @include('site.components.footer')
    </div>

    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script> <!-- conflict to site menu -->

    <!--Security system on index-->
    {{-- <script src='https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.7/vue.js'></script> --}}
    {{-- <script src='https://rawgit.com/Wlada/vue-carousel-3d/master/dist/vue-carousel-3d.min.js'></script> --}}

    <!-- For gallery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>
    
    {{-- <script src="https://www.google.com/recaptcha/api.js" async defer></script><!-- Google recaptcha --> --}}

    <!-- My JS -->
    <script src="{{ asset('assets/js/my_js/my_scripts.js') }}" type="text/javascript" ></script>
    {{-- <script src="{{ asset('assets/js/my_js/galery_image_open.js') }}" type="text/javascript" ></script><!--For index gallery--> --}}

    <script src="{{ asset('assets/js/app.js') }}"></script> 
</body>
</html>
