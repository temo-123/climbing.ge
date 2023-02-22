<!DOCTYPE html>
<html>
{{-- {{ str_replace('_', '-', app()->getLocale()) }} --}}
<head>
    
    <link href="{{ asset('images/site_img/x.png') }}" rel="shortcut icon">

    {{-- <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title') - shop.climibng.ge</title>
    <link href="{{ asset('images/site_img/site_logo/x.png') }}" rel="shortcut icon">

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name"               content="@yield('meta_title')">
    <meta itemprop="description"        content="@yield('meta_description')">
    <meta itemprop="image"              content="@yield('meta_img')">
    <meta name="keywords"               content="@yield('meta_keyword')">
    <meta name="description"            content="@yield('meta_description')" />

    <!-- Twitter Card data -->
    <meta name="twitter:card"           content="product">
    <meta name="twitter:site"           content="@publisher_handle">
    <meta name="twitter:title"          content="@yield('meta_title')">
    <meta name="twitter:description"    content="@yield('meta_description')">
    <meta name="twitter:creator"        content="@author_handle">
    <meta name="twitter:image"          content="@yield('meta_img')">

    @if(isset($products))
    @else
    <meta name="twitter:data1"          content="@yield('price')">
    <meta name="twitter:label1"         content="Price">
    <meta name="twitter:data2"          content="@yield('color')">
    <meta name="twitter:label2"         content="Color">
    @endif

    <!-- Open Graph data -->
    <meta property="og:title"           content="@yield('meta_title')" />
    <meta property="og:type"            content="website" />
    <meta property="og:url"             content="http://shop.climibng.ge" />
    <meta property="og:image"           content="@yield('meta_img')" />
    <meta property="og:description"     content="@yield('meta_description')" />
    <meta property="og:site_name"       content="climbing.ge" />
    
    @if(isset($products))
    @else

    <meta property="og:price:amount"    content="@yield('price')" />
    <meta property="og:price:currency"  content="USD" />
    @endif --}}

    {{-- <meta name='locale' content='{{app()->getLocale()}}' /> --}}


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    {{-- analytics.google.com (Global site tag (gtag.js) - Google Analytics) --}}
    {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-101870435-2"></script>
    <script src="{{ asset('assets/js/analitic/shop.climbing.ge_analitic.js') }}"></script> --}}

    <style>
        /* Firefox scrollbar color*/
        html {
            scrollbar-color: #27bb7d white;
            scrollbar-width: thin;
        }
        /* Other brouser scrollbar color*/
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
            background-color: white;
            border-radius: 0%
        }
        ::-webkit-scrollbar-thumb {
            background: #27bb7d;
            border-radius: 5px;
            border-radius: 0%
        }
        /* Navbar collor */
        .top_menu {
            background-color: #27bb7d;
        }
        .preloader {
            background: #27bb7d;
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
        
        {{-- @include('shop.components.footer') --}}
        {{-- <div class="footer__graphic" style="background-image: url({{ asset('images/svg/mountains.svg') }})"></div>

        <div class="footer"> 
            <div class="container"> 
                <div class="row">
        
                    <div class="col-md-4 display-none-720px">
                        <div class="f-about" style='color: #fff;'>
                           
                           <h4>About as</h4>
                           
                           {!!$site -> short_description!!}
                           
                       </div>
                   </div>
        
                   <div class="col-md-4 display-none-720px">
                       <div class="f-links">
                           <h4>Links</h4>
                           <ul> 
                             <li> <a href="#">@lang('shop.shop menu products')</a> </li>
                             <li> <a href="#">@lang('shop.shop menu about us')</a> </li>
                             <li> <a href="#">@lang('shop.shop login')</a> </li>
                           </ul>
                       </div>
                   </div>
        
                   <div class="col-md-4">
                       <div class="flick">
                           <h4>Share</h4>
                            <share-component route={{ URL::current() }}></share-component>
                       </div>
                   </div>
                    
                </div>
            </div>
        </div> --}}
        {{-- @if (session('status'))
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
            </div> --}}
            <!-- <script type="text/javascript">
             $(document).ready(function () {
                        $("#modalOnLoad").modal('show');
                    });
            </script> -->
        {{-- @endif --}}
    </div>

    {{-- <script>
        window.default_locale = “{{ config(‘app.locale’) }}”;
        window.fallback_locale = “{{ config(‘app.fallback_locale’) }}”;
    </script> --}}
    
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/my_js/my_scripts.js') }}" type="text/javascript" ></script>
    
</body>
</html>
