@extends('errors.layouts.app')

@section('content')
    <div class="header navbar-expand-lg navbar-right bg-light">
        <div class="row navbar-fixed-top top_menu">
            <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3 ">
                <div class="site_title">
                    {{-- <a href="{{ route('user_index') }}" class="site_title"> --}}
                        <p class="site_title">404</p>
                    {{-- </a> --}}
                </div>
            </div>
            <div class="col-xs-12 col-sm-10 col-md-9 col-lg-9">
                <nav class="navbar navbar-default  float-right float-top" role="navigation">
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
@endsection
        
