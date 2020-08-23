@extends('site.layouts.app')

@section('content')


    @section('meta_title',  $article->title )
    @if ($article->short_description != NULL)
        @section('meta_description',  $article->short_description )
    @else
        @section('meta_description',  $article->title )
    @endif
    @if ($article->meta_keyword != NULL)
        @section('meta_keyword',  $article->meta_keyword )
    @else
        @section('meta_keyword',  $article->title )
    @endif
    @if(isset($image_dir))
        @section('meta_img',  asset('images/'.$image_dir.'/'.$article->image))
    @endif

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

                @if(isset($article_gallery_count))
                @if($article_gallery_count > 0)
                @include('site.components.article_gallery')
                @endif
                @endif
                
            </div>
            @include('site.components.right_block')
            
        </div>

        @if(isset($comments))
        @if (Auth::guest())
            <comment-form-component :article_id="{{$article->id}}"></comment-form-component>
        @else
            <comment-form-component :article_id="{{$article->id}}"></comment-form-component>
        @endif
        @endif

        <a href="{{ URL::previous() }}" class='btn'>Go Back</a>
        @if(isset($all_article_but))
        <a href="{{ route($all_article_but) }}" class="btn">All Articles</a>
        @endif

        @if(isset($other_article))
        @include('site.components.other_article')
        @endif
    </div>

@endsection