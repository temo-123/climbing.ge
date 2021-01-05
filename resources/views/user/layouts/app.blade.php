<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <link href="{{ asset('images/site_img/site_logo/'.$site->image) }}" rel="shortcut icon">

    <!-- <meta property="og:image"           content=""> -->
    <meta property="og:title"           content="@yield('meta_title')"/>
    <meta property="og:description"     content="@yield('meta_description')"/>
    <meta name="keywords"               content="@yield('meta_keyword')">
    <meta property="og:image" 			content="@yield('meta_img')">
    <meta property="og:type"            content="website"/>
    <meta property="og:url"             content="http://www.climibng.ge" />
    <meta name="MobileOptimized"        content="320">
    <meta name="csrf-token"             content="{{ csrf_token() }}">

     <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">


    {{-- <script src="../../../node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js"></script> --}}
    {{-- <script src="../../../node_modules/@ckeditor/ckeditor5-vue/dist/ckeditor.js"></script> --}}

    {{-- <link rel="stylesheet" href="{{ asset('assets/css/admin/style.css') }}"> --}}
    {{-- <script src="../node_modules/ckeditor4/ckeditor.js"></script> --}}
    {{-- <script src="../node_modules/ckeditor4-vue/dist/ckeditor.js"></script> --}}
    {{-- <script src="{{ asset('../node_modules/ckeditor4-vue/dist/ckeditor.js') }}"></script> --}}

</head>
<body>
    <style>
        .top_menu {
            background-color: #7427bb;
        }
        .preloader {
            background: #7427bb;
        }
    </style>
    <div id="page-preloader" class="preloader">
        <div class="loader"></div>
    </div>

    <div id="app">
        @include('user.components.navbar')

        @yield('content')
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- My JS -->
    <script src="{{ asset('assets/js/my_js/my_scripts.js') }}" type="text/javascript" ></script>

    <script>
        $('body').on('submit','form', function () {
            $(this).append('@csrf')
        })
    </script>    
</body>
</html>
