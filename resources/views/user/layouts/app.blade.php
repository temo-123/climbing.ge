<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <link href="{{ asset('images/site_img/site_logo/'.$site->image) }}" rel="shortcut icon">

    {{-- <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> --}}
    

    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    {{-- Google analitics (Global site tag (gtag.js) - Google Analytics) --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-101870435-3"></script>
    <script src="{{ asset('assets/js/analitic/user.climbing.ge_analitic.js') }}"></script>

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

        @include('user.components.footer')
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
