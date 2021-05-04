@extends('site.layouts.app')

@section('content')
    
@section('title',  $article[0]['title'] )
@section('meta_description',  $article[0]['short_description'] )
@section('meta_keyword',  $article[0]['title'] )
@section('meta_img',  asset('images/'.$image))

@section('created_at',  $article[0]['created_at'])
@section('updated_at',  $article[0]['updated_at'])

    <div class="container top_menu_margin">
        @if(isset($article))
            
        <div class="row">
            <div class="col-sm-8 blog-header">
                @if($article[0]['name'])
                <h1 class="blog-title">{{ $article[0]['name'] }}</h1> 
                @else
                <h1 class="blog-title">
                    {{ $article[0]['title'] }}
                </h1> 
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 blog-header">
                @component('site.components.breadcrumb')
                @slot('parent') Home @endslot
                @slot('link') {{route($all_article_but)}} @endslot
                @slot('active') {{$article_map}} @endslot
                @if($article [0]['title'])
                @slot('article') {{$article [0]['title']}} @endslot
                @else
                @slot('article') {{$article [0]['name']}} @endslot
                @endif
                @endcomponent

                <p class="blog-post-meta">{{ $article[0]['created_at'] }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8 blog-main">

                @if($article[0]['text'] != NULL)
                    {!!$article [0]['text']!!}
                @endif

                @if($article[0]['weather'] == NULL && $article[0]['best_time'] != NULL)
                    <h2 id="best_time_to_climb">Best time to climb</h2>
                    {!!$article[0]['best_time']!!}
                @elseif($article[0]['weather'] != NULL && $article[0]['best_time'] != NULL)
                    <h2 id="best_time_to_climb">Best time to climb</h2>

                    <div class="row">
                        <div class="col-md-6" style="margin-top: 5%;">
                            {!!$article[0]['best_time']!!}
                        </div>
                        <div class="col-md-6" style="text-align: center;">
                            {!!$article['weather']!!}
                        </div>
                    </div>
                @endif

                @if(($article[0]['address']) != NUll)
                    <h2 id="how_to_get_there">Address</h2>
                    {!!$article[0]['address']!!}
                @endif

                @if(($article[0]['how_get']) != NUll)
                    <h2 id="how_to_get_there">How to get there</h2>
                    {!!$article[0]['how_get']!!}
                @endif

                @if(($article['map']) != NULL)
                    <div class="article_map">
                        {!!$article['map']!!}
                    </div>
                @endif

                @if(($article[0]['prices_text']) != NUll)
                    <h2 id="how_to_get_there">Prices</h2>
                    {!!$article[0]['prices_text']!!}
                @endif

                @if(($article[0]['info']) != NUll)
                    <h2 id="how_to_get_there">Info</h2>
                    {!!$article[0]['info']!!}
                @endif

                @if(($article[0]['what_need']) != NUll)
                    <h2 id="what_you_need">What you need</h2>
                    {!!$article[0]['what_need']!!}
                @endif

                @if($article [0]['route'])
                <h2 id="routes">Route</h2>
                {!!$article [0]['route']!!}
                @endif             

                <ul class="social-network social-circle" style="text-align: center;"> 
                    @if(($article['fb_link']) != NULL)       
                    <li>
                        <a target="_blank" href="{{$article['fb_link']}}" class="icoFacebook ico_color" title="Facebook">
                            <i class="fa fa-facebook"></i>
                        </a>
                    </li>
                    @endif

                    @if(($article['twit_link']) != NULL) 
                    <li>
                        <a target="_blank" href="{{$article['twit_link']}}" class="icoTwitter ico_color" title="Twitter">
                            <i class="fa fa-twitter"></i>
                        </a>
                    </li>
                    @endif

                    @if(($article['google_link']) != NULL) 
                    <li>
                        <a target="_blank" href="{{$article['google_link']}}" class="icoGoogle ico_color" title="Google +">
                            <i class="fa fa-google-plus"></i>
                        </a>
                    </li>
                    @endif

                    @if(($article['inst_link']) != NULL) 
                    <li>
                        <a target="_blank" href="{{$article['inst_link']}}" class="icoLinkedin ico_color" title="Instagram">
                            <i class="fa fa-instagram"></i>
                        </a>
                    </li>
                    @endif

                    @if(($article['web_link']) != NULL) 
                    <li>
                        <a target="_blank" href="{{$article['web_link']}}" class="icoLinkedin ico_color" title="website">
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
                
                {{-- @include('site.components.article_gallery') --}}
            </div>
            @include('site.components.right_block')
            
        </div>

        @if(isset($comments))
        @if (Auth::guest())
            <comment-form-component :article_id="{{$article['id']}}"></comment-form-component>
        @else
            <comment-form-component :article_id="{{$article['id']}}"></comment-form-component>
        @endif
        @endif

        <a href="{{ URL::previous() }}" class='btn'>Go Back</a>
        @if(isset($all_article_but))
        <a href="{{ route($all_article_but) }}" class="btn">All Articles</a>
        @endif

        @if(isset($other_article))
        @include('site.components.other_article')
        @endif
        @endif
    </div>

@endsection