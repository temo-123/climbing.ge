@extends('site.layouts.app')

@section('content')

    @section('meta_title',  $article[0]['title'] )
    @if ($article[0]['short_description'] != NULL)
        @section('meta_description',  $article[0]['short_description'] )
    @else
        @section('meta_description',  $article[0]['title'] )
    @endif
    @if ($article[0]['meta_keyword'] != NULL)
        @section('meta_keyword',  $article[0]['meta_keyword'] )
    @else
        @section('meta_keyword',  $article[0]['title'] )
    @endif
    @if(isset($image))
        @section('meta_img',  asset('images/'.$image))
    @endif

    @section('created_at',  $article[0]['created_at'])
    @section('updated_at',  $article[0]['updated_at'])

        <div class="container top_menu_margin">

            <div class="row">
                @if(isset($article))  
                <h1 style="font-size: 200%">
                    {{ $article[0]['title'] }}
                </h1> 
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

                    @if(($article[0]['how_get']) != NUll)
                        <h2 id="how_to_get_there">How to get there</h2>
                        {!!$article[0]['how_get']!!}
                    @endif

                    @if(($article['map']) != NULL)
                        <div class="article_map">
                            {!!$article['map']!!}
                        </div>
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


                    @if(isset($sport_route_sector))
                        @include('site.components.route')
                    @endif
                    
                    @if(isset($article_gallery_count) && $article_gallery_count > 0)
                        @include('site.components.article_gallery')
                    @endif
                    
                </div>
                
                @include('site.components.right_block')
                
            </div> 
      
            @if(isset($comments))
            @if (Auth::guest())
                <comment-form-component :article_id="{{$article[0]['id']}}"></comment-form-component>
            @else
                <comment-form-component 
                        :article_id="{{$article[0]['id']}}" 
                        :user_name="name" 
                        :user_surname="surname" 
                        :is_login="true"
                ></comment-form-component>
            @endif
            @endif

            <a href="{{ URL::previous() }}" class='btn'>Go Back</a>
            @if(isset($all_article_but))
            <a href="{{ route($all_article_but) }}" class="btn">All Articles</a>
            @endif

            @if(isset($other_article))
            @include('site.components.other_article')
            @endif

            @else
            <h1>Variable Error 2! [Article block][$article]</h1>
            @endif
        </div>

        @endsection