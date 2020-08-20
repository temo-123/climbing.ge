<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <!-- <title>{{ config('app.name') }}</title> -->
    <link href="{{ asset('images/site_logo/'.$site->site_img) }}" rel="shortcut icon">

    <!-- <meta property="og:image"           content=""> -->
    <meta property="og:title"           content="@yield('meta_title')"/>
    <meta property="og:description"     content="@yield('meta_description')"/>
    <meta name="keywords"               content="@yield('meta_keyword')">
    <meta property="og:image" 			content="@yield('meta_img')">
    <meta property="og:type"            content="website"/>
    <meta property="og:url"             content="http://www.climibng.ge" />
    <meta name="MobileOptimized" content="320">

    <!--font femyly in style.css (line 6.12.17.23.30) || style1.css (line 6)-->
    <link href="https://fonts.googleapis.com/css?family=Lora" rel="stylesheet">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="http://cdn.bootcss.com/animate.css/3.5.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css"><!--animate style-->

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-theme.min.css') }}">

    <!--font femyly in style.css (line 6.12.17.23.30) || style1.css (line 6)-->
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="https://fonts.googleapis.com/css?family=Lora" rel="stylesheet">
    <!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->

    <!-- My style -->
    <!-- <link rel="stylesheet" href="{{ asset('assets/css/my_css/main.min.css') }}" > -->
    <!-- <link rel="stylesheet" href="{{ asset('assets/css/my_css/news.css') }}" > -->
    <!-- <link rel="stylesheet" href="{{ asset('assets/css/my_css/diogram.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ asset('assets/css/my_css/my_diogram.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('assets/css/my_css/style.css') }}" >

    <!-- fonts -->
    <link rel="stylesheet" href="{{ asset('assets/css/animate-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">

    <!--For article slider-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <!--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>-->

    <!--For gallry -->
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <!--<link rel="stylesheet" href="css/style.css"> -->

    <!--For about us-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/css/bootstrapValidator.min.css"/>
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/js/bootstrapValidator.min.js"></script> 

</head>
<body>

    <div id="page-preloader" class="preloader">
        <div class="loader"></div>
    </div>

    <div id="app">
        @include('components.header')

        @yield('content')

        @include('components.footer')

        @if (session('status'))
            <div id="modalOnLoad" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    @if(session('status'))
                                      <div class="alert alert-danger">
                                        {{ session('status') }}
                                      </div>
                                    @endif
                                </div>
                            </div>
                            <button type="button" class="btn blue btn-outline" data-dismiss="modal" aria-hidden="true">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
             $(document).ready(function () {
                        $("#modalOnLoad").modal('show');
                    });
            </script>
        @endif
    </div>

    <!-- status script -->
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <!-- <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script> --> <!-- conflict to site menu -->

    <!-- Scripts -->
    <!-- <script src="{{ asset('assets/js/app.js') }}"></script> -->
    <script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/jquery.fractionslider.min.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('assets/js/animation.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="{{ asset('assets/js/less.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/isotop.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.colorbox.js') }}"></script>

    <!--Security system on index-->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.7/vue.js'></script>
    <script src='https://rawgit.com/Wlada/vue-carousel-3d/master/dist/vue-carousel-3d.min.js'></script>

    <!-- For gallery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>
    <!-- Comment text form color control -->
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.2/js/bootstrapValidator.min.js"></script>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script><!-- Google recaptcha -->

    <!-- My JS -->
    <script src="{{ asset('assets/js/my_js/my_scripts.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('assets/js/my_js/galery_image_open.js') }}" type="text/javascript" ></script><!-- For index gallery -->
    <script src="{{ asset('assets/js/my_js/indoor_stars.js') }}" type="text/javascript"></script><!--For indoor stars block-->
    
</body>
</html>
