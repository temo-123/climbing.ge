<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>404</title>

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
    <div id="app">
        @include('components.header')

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
                            <a href="{{ route('index') }}" class="btn btn-primary btn-lg">
                                <span class="glyphicon glyphicon-home"></span>
                                Back to index page
                            </a>
                            @include('components.support_form')
                        </div>
                        <h2>Climbing.ge</h2>
                    </div>
                </div>
            </div>
        </div>

        @include('components.footer')
    </div>
</body>
</html>
