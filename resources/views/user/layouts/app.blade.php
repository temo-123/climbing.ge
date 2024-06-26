<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link href="{{ asset('images/site_img/x.png') }}" rel="shortcut icon">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>climbing.ge user</title>

    <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .top_menu {
            background-color: #7427bb;
        }
        .preloader {
            background: #7427bb;
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

    <script src="{{ mix('assets/js/app.js') }}"></script>

    <script src="{{ asset('assets/main_js/my_scripts.js') }}" type="text/javascript" ></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
