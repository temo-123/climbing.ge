<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    {{-- CSRF Token --}}
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

    <title>@yield('title')</title>
    {{-- <link href="{{ asset('images/site_img/site_logo/'.$site->image) }}" rel="shortcut icon"> --}}
    {{-- <script src="https://sasensi.github.io/paperjs-layers-panel/build/paperjs-layers-panel.js"></script> --}}

    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    {{-- Google analitics (Global site tag (gtag.js) - Google Analytics) --}}
    {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-101870435-3"></script>
    <script src="{{ asset('assets/js/analitic/user.climbing.ge_analitic.js') }}"></script> --}}

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
    {{-- <div id="page-preloader" class="preloader">
        <div class="loader"></div>
    </div> --}}

    <div id="app">
        {{-- @include('user.components.navbar') --}}

        @yield('content')

        {{-- @include('user.components.footer') --}}
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- My JS -->
    <script src="{{ asset('assets/js/my_js/my_scripts.js') }}" type="text/javascript" ></script>

    {{-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script> --}}
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> --}}

    {{-- <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> --}}
    {{-- <script>
        $('body').on('submit','form', function () {
            $(this).append('@csrf')
        })
    </script>     --}}
</body>
</html>
