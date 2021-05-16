@extends('site.layouts.app')

@section('content')

@section('title',  $article[0]['title'] )
@section('meta_description',  $article[0]['short_description'] )
@section('meta_keyword',  $article[0]['title'] )
@section('meta_img',  asset('images/'.$image))

@section('created_at',  $article[0]['created_at'])
@section('updated_at',  $article[0]['updated_at'])

    <div class="container top_menu_margin">
        <div class="row">
            @if(isset($article))  
            @if($article->name)
            <h1 class="blog-title">{{ $article->name }}</h1> 
            @else
            <h1 class="blog-title">
                {{ $article->title }}
                @auth
                @if(Auth::user()->hasRole('admin'))
                @auth
                (<a href="#">Edit</a>)
                @endauth
                @endif
                @endauth
            </h1> 
            @endif

            <div class="col-sm-8 blog-header">
                @component('site.components.breadcrumb')
                @slot('parent') Home @endslot
                @slot('link') {{route($all_article_but)}} @endslot
                @slot('active') {{$article_map}} @endslot
                @if($article -> title)
                @slot('article') {{$article -> title}} @endslot
                @else
                @slot('article') {{$article -> name}} @endslot
                @endif
                @endcomponent

                <p class="blog-post-meta">{{ $article->created_at }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 blog-main">

                @if(isset($mounts_system))
                    @foreach($mounts_system as $mount_system)

                        <h2>Mountain Massive - <strong style='font-size: 150%;'>{{$mount_system -> title}}</strong></h2>

                        {!!$mount_system -> text!!}

                        @if(($mount_system->what_need) != NUll)
                            <h2 id="what_you_need">What you need</h2>
                            {!!$mount_system->what_need!!}
                        @endif

                        @if(($mount_system->weather) != NULL)
                            <h2 id="best_time_to_climb">Best time to climb</h2>
                        @endif

                        @if(($mount_system->weather) == NULL)
                            {!!$mount_system->best_time!!}
                        @else 
                            <div class="row">
                                <div class="col-md-6" style="margin-top: 5%;">
                                    {!!$mount_system->best_time!!}
                                </div>
                                <div class="col-md-6" style="text-align: center;">
                                    <div class="col-xs-12">
                                        {!!$mount_system->weather!!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(($mount_system->how_get) != NUll)
                            <h2 id="how_to_get_there">How to get there</h2>
                            {!!$mount_system->how_get!!}
                        @endif

                        @if(($mount_system->map) != NULL)
                            <div class="article_map">
                                {!!$mount_system->map!!}
                            </div>
                        @endif

                        @if($article -> route_text)
                        <div class='col-xs-12 blog_main_img'>
                            <h2 id="routes">Route - {{$article -> name}}</h2>
                            <hr>
                            {!!$article -> route_text!!}
                        </div>
                        @endif 

                        @break
                    @endforeach
                @endif

                @if($article->text != NULL)
                    {!!$article -> text!!}
                @endif

                @if($article->weather == NULL && $article->best_time != NULL)
                    <h2 id="best_time_to_climb">Best time to climb</h2>
                    {!!$article->best_time!!}
                @elseif($article->weather != NULL && $article->best_time != NULL)
                    <h2 id="best_time_to_climb">Best time to climb</h2>

                    <div class="row">
                        <div class="col-md-6" style="margin-top: 5%;">
                            {!!$article->best_time!!}
                        </div>
                        <div class="col-md-6" style="text-align: center;">
                            {!!$article->weather!!}
                        </div>
                    </div>
                @endif

                @if(($article->address) != NUll)
                    <h2 id="how_to_get_there">Address</h2>
                    {!!$article->address!!}
                @endif

                @if(($article->how_get) != NUll)
                    <h2 id="how_to_get_there">How to get there</h2>
                    {!!$article->how_get!!}
                @endif

                @if(($article->map) != NULL)
                    <div class="article_map">
                        {!!$article->map!!}
                    </div>
                @endif

                @if(($article->prices_text) != NUll)
                    <h2 id="how_to_get_there">Prices</h2>
                    {!!$article->prices_text!!}
                @endif

                @if(($article->info) != NUll)
                    <h2 id="how_to_get_there">Info</h2>
                    {!!$article->info!!}
                @endif

                @if(($article->what_need) != NUll)
                    <h2 id="what_you_need">What you need</h2>
                    {!!$article->what_need!!}
                @endif

                @if($article -> route)
                <h2 id="routes">Route</h2>
                {!!$article -> route!!}
                @endif             

                <ul class="social-network social-circle" style="text-align: center;"> 
                    @if(($article->fb_link) != NULL)       
                    <li>
                        <a target="_blank" href="{{$article->fb_link}}" class="icoFacebook ico_color" title="Facebook">
                            <i class="fa fa-facebook"></i>
                        </a>
                    </li>
                    @endif

                    @if(($article->twit_link) != NULL) 
                    <li>
                        <a target="_blank" href="{{$article->twit_link}}" class="icoTwitter ico_color" title="Twitter">
                            <i class="fa fa-twitter"></i>
                        </a>
                    </li>
                    @endif

                    @if(($article->google_link) != NULL) 
                    <li>
                        <a target="_blank" href="{{$article->google_link}}" class="icoGoogle ico_color" title="Google +">
                            <i class="fa fa-google-plus"></i>
                        </a>
                    </li>
                    @endif

                    @if(($article->inst_link) != NULL) 
                    <li>
                        <a target="_blank" href="{{$article->inst_link}}" class="icoLinkedin ico_color" title="Instagram">
                            <i class="fa fa-instagram"></i>
                        </a>
                    </li>
                    @endif

                    @if(($article->web_link) != NULL) 
                    <li>
                        <a target="_blank" href="{{$article->web_link}}" class="icoLinkedin ico_color" title="website">
                            <i class="fa fa-globe "></i>
                        </a>
                    </li>
                    @endif
                </ul>

                <!--Right block--> 
                @if(isset($sport_route_sector))
                @include('site.components.route')
                @endif
                <!--And route block-->        

                <!--Slider-->
                @if(isset($article_gallery_count))
                @if($article_gallery_count > 0)
                @include('site.components.article_gallery')
                @endif
                @endif
                <!--And route block-->
            </div><!-- /.blog-main -->   
            <!--Right block-->
            @include('site.components.right_block')
            <!--And route block-->
        </div> <!-- /.row -->

        @if(isset($comments))
        @if (Auth::guest())
            <comment-form-component :article_id="{{$article->id}}"></comment-form-component>
        @else
            <comment-form-component :article_id="{{$article->id}}"></comment-form-component>
        @endif
        @endif

        <a href="{{ URL::previous() }}" class='btn btn-primary'>Go Back</a>
        @if(isset($all_article_but))
        <a href="{{ route($all_article_but) }}" class="btn btn-primary">All Articles</a>
        @endif

        <!--Comments And Form-->
        @if(isset($other_article))
        @include('site.components.other_article')
        @endif
        <!--And Comments And Form-->

        @else
        <h1>Variable Error 2! [Article block][$article]</h1>
        @endif
    </div>
@endsection