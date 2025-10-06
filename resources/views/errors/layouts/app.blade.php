<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>404 - climbing.ge</title>

    <link href="{{ asset('assets/img/site_logo/x.png') }}" rel="shortcut icon">

    <meta property="og:title"           content="Error 404"/>
    <meta property="og:description"     content="Page Not Found"/>
    <meta name="keywords"               content="Climbing In Georgia">
    <meta property="og:image" 			content="{{ asset('assets/img/site_logo/x.png') }}">
    <meta property="og:type"            content="website"/>
    <meta property="og:url"             content="http://www.climibng.ge" />

    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>
    <style>
        .top_menu {
            background-color: #bb2727;
        }
        .preloader {
            background: #bb2727;
        }
    </style>

    <div id="app">
        @yield('content')
        {{-- @include('errors.components.footer') --}}
    </div>
    
    <!-- <script src="{{ asset('assets/js/my_js/my_scripts.js') }}" type="text/javascript" ></script> -->
</body>
</html>
