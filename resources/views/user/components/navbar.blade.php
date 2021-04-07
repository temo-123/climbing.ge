<div class="header navbar-expand-lg navbar-right bg-light">
    <div class="row fixed-top top_menu">
        <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3 ">
            <div class="site_title">
                <a href="{{ route('user_index') }}" class="site_title">
                    @if (Route::has('login'))
                    @auth
                        Hi {{ Auth::user()->name }}
                    @endauth
                    @endif
                </a>
            </div>
        </div>
        <div class="col-xs-12 col-sm-10 col-md-9 col-lg-9">
            <nav class="navbar navbar-default  float-right float-top" role="navigation">
                <div class="container-fluid">                    
                    
                    <ul class="dropdown-menu shadows" role="menu" style="width: 100%;">
                        <li>
                            <form action="eeeee" method="POST" role="search" class="navbar-form">
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
                        
                        <li class="nav navbar-nav pull-right top_menu_buttons">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>