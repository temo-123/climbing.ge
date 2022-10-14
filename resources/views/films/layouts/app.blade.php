<!DOCTYPE html>
{{-- <html lang="{{ app()->getLocale() }}"> --}}
{{-- {{ str_replace('_', '-', app()->getLocale()) }} --}}
<head>
    {{-- <meta name='locale' content='{{app()->getLocale()}}' /> --}}

    <link href="{{ asset('images/site_img/x.png') }}" rel="shortcut icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    {{-- analytics.google.com (Global site tag (gtag.js) - Google Analytics) --}}
    {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-101870435-2"></script>
    <script src="{{ asset('assets/js/analitic/shop.climbing.ge_analitic.js') }}"></script> --}}

    <style>
        /* Firefox scrollbar color*/
        html {
            scrollbar-color: #777777 white;
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
            background: #777777;
            border-radius: 5px;
            border-radius: 0%
        }
        /* Navbar collor */
        .top_menu {
            background-color: #777777;
        }
        .preloader {
            background: #777777;
        }
    </style>
</head>
<body>
    <div id="page-preloader" class="preloader">
        <div class="loader"></div>
    </div>

    <div id="app">
        @yield('content')
    </div>
    
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/my_js/my_scripts.js') }}" type="text/javascript" ></script>
    
</body>
</html>
