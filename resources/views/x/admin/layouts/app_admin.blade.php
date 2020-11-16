<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ Auth::user()->name }} {{ Auth::user()->surname }} - {{ config('app.name') }}</title>
    <!--<title>{{-- config('app.name', 'Climbing.ge') --}}</title>-->
    
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link href="{{ asset('images/site_logo/'.$site->site_img) }}" rel="shortcut icon">

    <!-- Styles -->
    <!-- <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet"> -->
    
    <!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="{{ asset('assets/css/admin/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/my_css/diogram.css') }}">

    <!-- <link rel="stylesheet" href="{{ asset('assets/style.css') }}"> -->

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    
    <!-- For Filter filter.js-->
    <!-- <script src="//code.jquery.com/jquery-1.11.1.min.js"></script> -->
    
    <!-- slider edd window open -->
    <script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>

    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script><!--Alse need filtr (in add & edit page)-->
    <!--<script src="{{ asset('assets/js/my.js') }}"></script>-->
    <!---->
    
    
    <!-- text editor -->
    <!-- <script type="text/javascript" src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script> -->
    <script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script> 

    <!--font femyly in style.css (line 6.14.26.38) || style1.css (line 6)-->
    <link href="https://fonts.googleapis.com/css?family=Special+Elite" rel="stylesheet"><!--for titles style-->
    <link href="https://fonts.googleapis.com/css?family=Overlock" rel="stylesheet"><!--for text style-->

    <!-- <link rel="stylesheet" href="{{ asset('assets/style.css') }}"> -->

    <link rel="stylesheet" href="{{ asset('assets/css/diogram.css') }}"><!--fot news block-->
</head>
<body>
    <div id="app" class="top_menu_margin">

        @include('components.header')

        <div class="col-md-12">
        	@if(Session::has('message'))
        		{{Session::get('message')}}
        	@endif
        </div>
        
        @yield('content')
    </div>
    
    <footer>
        <div class="container">
           <div class="row">
                <!--<p class="pull-right"><a href="#">Back to top</a></p>-->
                <p>© 2014 Company, Inc.</p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <!-- <script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script> -->
    <script src="{{ asset('assets/js/filter.js') }}"></script> 
    <script src="{{ asset('assets/js/admin/more_info.js') }}" rel="stylesheet"></script>
    <!--analytics.google.com-->
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-101870435-1"></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());
            
              gtag('config', 'UA-101870435-1');
        </script>
    <!-- And analytics.google.com-->
    
        <!--Filter-->
    <script src="{{ asset('assets/js/filter.js') }}" type="text/javascript"></script>

</body>
</html>
