<!DOCTYPE html>
<html>
<head>
    <link href="{{ asset('images/site_img/x.png') }}" rel="shortcut icon">
    
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}" /> --}}

    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}"> --}}
    <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
        
        .navbar {
            background-color: #279fbb !important;
            border-color: #6a8991;
        }

        .navbar-inverse .navbar-nav>.open>a{
            background-color: #2f869b !important;
        }

        .navbar-inverse .navbar-nav>.open>a, .navbar-inverse .navbar-nav>.open>a:hover{
            background-color: #287386 !important;
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

    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script> <!-- conflict to site menu -->
    <script src="{{ asset('assets/js/my_js/my_scripts.js') }}" type="text/javascript" ></script>
    {{-- <script src="{{ asset('assets/js/app.js') }}"></script>  --}}
    <script src="{{ mix('assets/js/app.js') }}"></script>
</body>
</html>
