<div class="header navbar-expand-lg navbar-right bg-light">
    <div class="row navbar-fixed-top top_menu">
        <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3" style="z-index: 1001;">
            <div class="site_title">
                <a href="{{ route('index') }}" class="site_title">@lang('site.site name')</a>
            </div>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <nav class="navbar navbar-default float-top" role="navigation">
                <div class="container-fluid">

                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <i class="fa fa-angle-down"></i>
                    </button>
                    
                    <button type="button" class="navbar-toggle margin_right" data-toggle="dropdown">
                        <i class="fa fa-language" aria-hidden="true"></i>
                    </button>
                    
                    <button type="button" class="navbar-toggle margin_right">
                        
                        <a style="margin-top: -5%; color: #333333;" href="{{route('user_index')}}">
                            <i class="fa fa-user" ></i>
                        </a>
                    </button>
                    
                    <ul class="dropdown-menu shadows" role="menu" >
                        <li><a href="{{ url('/'.$url_afret_locale) }}"><img style="width: 20%;  margin-left: 40%;" src="{{ asset('images/site_img/leng/en.png')}}" alt=""></a></li>
                        <li><a href="{{ url('ka/'.$url_afret_locale) }}"><img style="width: 20%;  margin-left: 40%;" src="{{ asset('images/site_img/leng/ka.webp')}}" alt=""></a></li>
                        <li><a href="{{ url('ru/'.$url_afret_locale) }}"><img style="width: 20%;  margin-left: 40%;" src="{{ asset('images/site_img/leng/ru.png')}}" alt=""></a></li>
                    </ul>
                </div>
                <div class="collapse navbar-collapse mobile_nav_menu" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav pull-right top_menu_buttons">
                        <li class="dropdown">
                            <a href="#" @if(isset($indoor_climbing) || isset($outdoor_climbing)) id='actyve' @endif data-toggle="dropdown">
                            @lang('site.menu climbing')
                            </a>

                            <ul class="dropdown-menu " role="menu" style="background: #1994b1; position: absolute;">
                                <li><a style="font-size: 1.5em;" href="{{route('indoor_list')}}">@lang('site.menu indoor climbing')</a></li>
                                <li><a style="font-size: 1.5em;" href="{{route('outdoor_list')}}">@lang('site.menu outdoor climbing')</a></li>
                            </ul>
                        </li>

                        <li><a @if(isset($mount)) id='actyve' @endif href="{{route('mount_list')}}">@lang('site.menu mountaineering')</a></li>
                        <li><a @if(isset($ice)) id='actyve' @endif href="{{route('ice_list')}}">@lang('site.menu ice climbing')</a></li>
                        <li><a @if(isset($other)) id='actyve' @endif href="{{route('other_list')}}">@lang('site.menu other')</a></li>
                        <li><a @if(isset($pages)) id='actyve' @endif href="{{route('about_us_page')}}">@lang('site.menu about us')</a></li>


                        <li class="dropdown">
                            <a href="#" class="margin_right admin_menu_for_desctop" data-toggle="dropdown">
                                <i class="fa fa-search" @if(isset($search)) id='actyve' @endif aria-hidden="true"></i>
                            </a>
                            <ul class="dropdown-menu shadows menu_margin_left_850" role="menu" style="width: 1000%;">
                                <li>
                                    <form action="/search" method="POST" role="search" class="navbar-form">
                                        {{csrf_field ()}} 
                                        <div class="form-group" style="display:inline;">
                                            <div class="input-group" style="display:table;">
                                            <span class="input-group"> 
                                                <input class="form-control" name="request" placeholder="Search Here" autocomplete="off" autofocus="autofocus" type="text">
                                                <button type="submit" class="input-group-addon"> 
                                                    <span class="glyphicon glyphicon-search"></span>
                                                </button>
                                            </span>
                                            </div>
                                        </div>
                                    </form>
                                    
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown display-none-720px">
                            <a href="#" data-toggle="dropdown">
                                <i class="fa fa-language" aria-hidden="true"></i>
                            </a>

                            <ul class="dropdown-menu shadows" role="menu">
                                <li><a href="{{ url('/'.$url_afret_locale) }}"><img style="width: 50%; margin-left: 25%;" src="{{ asset('images/site_img/leng/en.png')}}" alt=""></a></li>
                                <li><a href="{{ url('ka/'.$url_afret_locale) }}"><img style="width: 50%; margin-left: 25%;" src="{{ asset('images/site_img/leng/ka.webp')}}" alt=""></a></li>
                                <li><a href="{{ url('ru/'.$url_afret_locale) }}"><img style="width: 50%; margin-left: 25%;" src="{{ asset('images/site_img/leng/ru.png')}}" alt=""></a></li>
                            </ul>
                        </li>
                        
                        @if (Route::has('login'))
                            @auth
                            <li class="display-none-720px">
                                <a style="margin-top: -5%; font-size: 120%;" href="{{route('user_index')}}">
                                    <i class="fa fa-user-circle"></i>
                                </a>
                            </li>
                            @endauth
                        @endif
                        @if (Auth::guest())
                            <li class="display-none-720px">
                                <a style="margin-top: -5%; font-size: 120%;" href="{{route('user_index')}}">
                                    <i class="fa fa-user-circle"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>