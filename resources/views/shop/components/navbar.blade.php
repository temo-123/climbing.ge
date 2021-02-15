<div class="header navbar-expand-lg navbar-right bg-light">
    <div class="row navbar-fixed-top top_menu">
        <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3 ">
            <div class="site_title">
                <a href="{{ route('shop_index') }}" class="site_title">@lang('shop.shop name')</a>
            </div>
        </div>
        <div class="col-xs-12 col-sm-10 col-md-9 col-lg-9">
            <nav class="navbar navbar-default  float-right float-top" role="navigation">
                <div class="container-fluid">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <i class="fa fa-angle-down"></i>
                    </button>                  
                    
                    <button type="button" class="dropdown-toggle navbar-toggle margin_right" data-toggle="dropdown">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>   
                    
                    <button type="button" class="navbar-toggle">
                        <li><a href="#"><i class="fa fa-user" style='color: #000;'></i></a></li>
                    </button>                      
                    
                    <ul class="dropdown-menu shadows" role="menu" style="width: 100%;">
                        <li>
                            <form action="#" method="POST" role="search" class="navbar-form">
                                {{csrf_field ()}} 
                                <div class="form-group" style="display:inline;">
                                <div class="input-group" style="display:table;">
                                    <input class="form-control" name="q" placeholder="Search Here" autocomplete="off" autofocus="autofocus" type="text">
                                    
                                    <span class="input-group-btn"> 
                                        <button type="submit" class="input-group-addon"> 
                                            <span class="glyphicon glyphicon-search"></span>
                                        </button>
                                    </span>
                                </div>
                                </div>
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav pull-right top_menu_buttons">
                        
                        <li><a @if(isset($products)) id='actyve' @endif href="{{route('shop_index')}}">@lang('shop.shop menu products')</a></li>
                        <li><a @if(isset($about_us)) id='actyve' @endif href="{{route('shop_about_us')}}">@lang('shop.shop menu about us')</a> </li>

                        @if (Auth::guest())
                        <li>
                            <a href="{{route('login')}}">@lang('shop.shop login')</a>
                        </li>
                        @endif

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle margin_right admin_menu_for_desctop" data-toggle="dropdown">
                                <i class="fa fa-search" @if(isset($search)) id='actyve' @endif aria-hidden="true"></i>
                            </a>
                            <ul class="dropdown-menu shadows menu_margin_left_850" role="menu" style="width: 1000%;">
                                <li>
                                    <form action="#" method="POST" role="search" class="navbar-form">
                                        {{csrf_field ()}} 
                                        <div class="form-group" style="display:inline;">
                                            <div class="input-group" style="display:table;">
                                            <input class="form-control" name="q" placeholder="Search Here" autocomplete="off" autofocus="autofocus" type="text">
                                            
                                            <span class="input-group-btn"> 
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

                        @if (Route::has('login'))
                        @auth
                        <li>
                            <a style="margin-top: -5%; font-size: 150%;" href="{{route('user_index')}}">
                                <i class="fa fa-user-circle"></i>
                            </a>
                        </li>
                        @endauth
                        @endif
                        
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>