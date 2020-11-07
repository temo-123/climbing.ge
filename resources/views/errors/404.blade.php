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
    <link rel="stylesheet" href="{{ asset('assets/css/my_css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/my_css/diogram.css') }}"><!--fot news block-->
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
        <div class="header navbar-expand-lg navbar-right bg-light">
            <div class="row navbar-fixed-top top_menu">
                <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3 ">
                    <div class="site_title">
                        <a href="{{ route('user_index') }}" class="site_title">
                            404
                        </a>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-10 col-md-9 col-lg-9">
                    <nav class="navbar navbar-default  float-right float-top" role="navigation">
                        <div class="container-fluid">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                <i class="fa fa-angle-down"></i>
                            </button>                  
                            
                            <button type="button" class="navbar-toggle margin_right" data-toggle="dropdown">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>   
                            
                            <button type="button" class="navbar-toggle">
                                <li><a href="#"><i class="fa fa-user" style='color: #000;'></i></a></li>
                            </button>                      
                            
                        </div>
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav pull-right top_menu_buttons">
                                
                                <li class="nav navbar-nav pull-right top_menu_buttons">
                                    <a href="{{ URL::previous() }}" >Go Back</a>
                                </li>

                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="error-template top_menu_margin">
                        <h1>Oops! Error 404! Page Not Found</h1>
                        <div class="error-details">
                            Sorry, an error has occured, Requested page not found!
                        </div>
                        <div>
                            <div style="display: none;">{!!$image_num = rand (1, 3)!!}</div>
                            @if($image_num == 1)
                            <img class="article_404" src="{{asset('images/404/404_page/1.jpg')}}">
                            @elseif($image_num == 2)
                            <img class="article_404" src="{{asset('images/404/404_page/2.jpg')}}">
                            @else
                            <img class="article_404" src="{{asset('images/404/404_page/3.jpg')}}">
                            @endif
                        </div>
                        <div class="error-actions">
                            <a href="{{ URL::previous() }}" class="btn btn-primary btn-lg">
                                <span class="glyphicon glyphicon-home"></span>
                                Go Beck
                            </a>
                        </div>
                        <h2>Climbing.ge</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer__graphic"></div>

        <div class="footer"> 
            <div class="container"> 
                <div class="row">

                    <div class="col-md-4">
                        <div class="f-about" style='color: #fff;'>
                            
                            <h4>About as</h4>
                            
                            {!!$site -> short_description!!}
                            <a href="{{route('about_us_page')}}">More</a>
                            
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="f-links">
                            <h4>Links</h4>
                            <ul>
                            <li> <a href="{{route('indoor_list')}}">Indoor climbing</a> </li>
                            <li> <a href="{{route('outdoor_list')}}">Outdoor climbing</a> </li>
                            <li> <a href="{{route('mount_list')}}">Mountainering</a> </li>
                            <li> <a href="{{route('ice_list')}}">Ice and mix</a> </li>
                            <li> <a href="{{route('other_list')}}">Other</a> </li>
                            <li> <a href="{{route('about_us_page')}}">About as</a> </li>
                            <li> <a href="{{route('login')}}">Login</a> </li>
                            <li> <a href="{{url('sitemap')}}">Sitemap</a> </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="flick">
                            <h4>Share</h4>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</body>
</html>
