<!DOCTYPE html>
<html>
{{-- {{ str_replace('_', '-', app()->getLocale()) }} --}}
<head>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
    {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> --}}
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Rokkitt" rel="stylesheet"> 
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>


</head>
<body>
    <style>
    .top_menu {
        background-color: #005f43;
    }
    .preloader {
        background: #005f43;
    }
    </style>
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
