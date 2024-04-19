<!DOCTYPE html>
<html>
<head>
    <link href="{{ asset('images/site_img/x.png') }}" rel="shortcut icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        /* Navbar collor */
        .top_menu {
            background-color: #27bb7d;
        }
        .preloader {
            background: #27bb7d;
        }

        .navbar {
            background-color: #09bb80 !important;
            border-color: #6a8991;
        }

        .navbar-inverse .navbar-nav>.open>a{
            background-color: #0e9f6f !important;
        }

        .navbar-inverse .navbar-nav>.open>a, .navbar-inverse .navbar-nav>.open>a:hover{
            background-color: #0c8a60 !important;
        }

        .navbar-inverse .navbar-collapse{
            border-color: #838383;
        }

        .navbar-collapse {
            max-height: none !important;
        }
    </style>
</head>
<body>
    <div id="page-preloader" class="preloader">
        <div class="loader"></div>
    </div>

    <div id="app">
        {{-- @include('shop.components.navbar') --}}

        @yield('content')
    </div>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/main_js/my_scripts.js') }}" type="text/javascript" ></script>

    <script src="{{ mix('assets/js/app.js') }}"></script>    
</body>
</html>
